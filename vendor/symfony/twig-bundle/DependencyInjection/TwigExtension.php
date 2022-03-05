<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\TwigBundle\DependencyInjection;

use easy-admin\Component\Config\FileLocator;
use easy-admin\Component\Config\Resource\FileExistenceResource;
use easy-admin\Component\Console\Application;
use easy-admin\Component\DependencyInjection\ContainerBuilder;
use easy-admin\Component\DependencyInjection\Loader\PhpFileLoader;
use easy-admin\Component\DependencyInjection\Reference;
use easy-admin\Component\Form\Form;
use easy-admin\Component\HttpKernel\DependencyInjection\Extension;
use easy-admin\Component\Mailer\Mailer;
use easy-admin\Component\Translation\Translator;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\Loader\LoaderInterface;

/**
 * TwigExtension.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 * @author Jeremy Mikola <jmikola@gmail.com>
 */
class TwigExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('twig.php');

        if ($container::willBeAvailable('easy-admin/form', Form::class, ['easy-admin/twig-bundle'])) {
            $loader->load('form.php');
        }

        if ($container::willBeAvailable('easy-admin/console', Application::class, ['easy-admin/twig-bundle'])) {
            $loader->load('console.php');
        }

        if ($container::willBeAvailable('easy-admin/mailer', Mailer::class, ['easy-admin/twig-bundle'])) {
            $loader->load('mailer.php');
        }

        if (!$container::willBeAvailable('easy-admin/translation', Translator::class, ['easy-admin/twig-bundle'])) {
            $container->removeDefinition('twig.translation.extractor');
        }

        foreach ($configs as $key => $config) {
            if (isset($config['globals'])) {
                foreach ($config['globals'] as $name => $value) {
                    if (\is_array($value) && isset($value['key'])) {
                        $configs[$key]['globals'][$name] = [
                            'key' => $name,
                            'value' => $value,
                        ];
                    }
                }
            }
        }

        $configuration = $this->getConfiguration($configs, $container);

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('twig.form.resources', $config['form_themes']);
        $container->setParameter('twig.default_path', $config['default_path']);
        $defaultTwigPath = $container->getParameterBag()->resolveValue($config['default_path']);

        $envConfiguratorDefinition = $container->getDefinition('twig.configurator.environment');
        $envConfiguratorDefinition->replaceArgument(0, $config['date']['format']);
        $envConfiguratorDefinition->replaceArgument(1, $config['date']['interval_format']);
        $envConfiguratorDefinition->replaceArgument(2, $config['date']['timezone']);
        $envConfiguratorDefinition->replaceArgument(3, $config['number_format']['decimals']);
        $envConfiguratorDefinition->replaceArgument(4, $config['number_format']['decimal_point']);
        $envConfiguratorDefinition->replaceArgument(5, $config['number_format']['thousands_separator']);

        $twigFilesystemLoaderDefinition = $container->getDefinition('twig.loader.native_filesystem');

        // register user-configured paths
        foreach ($config['paths'] as $path => $namespace) {
            if (!$namespace) {
                $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$path]);
            } else {
                $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$path, $namespace]);
            }
        }

        // paths are modified in ExtensionPass if forms are enabled
        $container->getDefinition('twig.template_iterator')->replaceArgument(1, $config['paths']);

        foreach ($this->getBundleTemplatePaths($container, $config) as $name => $paths) {
            $namespace = $this->normalizeBundleName($name);
            foreach ($paths as $path) {
                $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$path, $namespace]);
            }

            if ($paths) {
                // the last path must be the bundle views directory
                $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$path, '!'.$namespace]);
            }
        }

        if (file_exists($defaultTwigPath)) {
            $twigFilesystemLoaderDefinition->addMethodCall('addPath', [$defaultTwigPath]);
        }
        $container->addResource(new FileExistenceResource($defaultTwigPath));

        if (!empty($config['globals'])) {
            $def = $container->getDefinition('twig');
            foreach ($config['globals'] as $key => $global) {
                if (isset($global['type']) && 'service' === $global['type']) {
                    $def->addMethodCall('addGlobal', [$key, new Reference($global['id'])]);
                } else {
                    $def->addMethodCall('addGlobal', [$key, $global['value']]);
                }
            }
        }

        if (isset($config['autoescape_service']) && isset($config['autoescape_service_method'])) {
            $config['autoescape'] = [new Reference($config['autoescape_service']), $config['autoescape_service_method']];
        }

        $container->getDefinition('twig')->replaceArgument(1, array_intersect_key($config, [
            'debug' => true,
            'charset' => true,
            'base_template_class' => true,
            'strict_variables' => true,
            'autoescape' => true,
            'cache' => true,
            'auto_reload' => true,
            'optimizations' => true,
        ]));

        $container->registerForAutoconfiguration(\Twig_ExtensionInterface::class)->addTag('twig.extension');
        $container->registerForAutoconfiguration(\Twig_LoaderInterface::class)->addTag('twig.loader');
        $container->registerForAutoconfiguration(ExtensionInterface::class)->addTag('twig.extension');
        $container->registerForAutoconfiguration(LoaderInterface::class)->addTag('twig.loader');
        $container->registerForAutoconfiguration(RuntimeExtensionInterface::class)->addTag('twig.runtime');

        if (false === $config['cache']) {
            $container->removeDefinition('twig.template_cache_warmer');
        }
    }

    private function getBundleTemplatePaths(ContainerBuilder $container, array $config): array
    {
        $bundleHierarchy = [];
        foreach ($container->getParameter('kernel.bundles_metadata') as $name => $bundle) {
            $defaultOverrideBundlePath = $container->getParameterBag()->resolveValue($config['default_path']).'/bundles/'.$name;

            if (file_exists($defaultOverrideBundlePath)) {
                $bundleHierarchy[$name][] = $defaultOverrideBundlePath;
            }
            $container->addResource(new FileExistenceResource($defaultOverrideBundlePath));

            if (file_exists($dir = $bundle['path'].'/Resources/views') || file_exists($dir = $bundle['path'].'/templates')) {
                $bundleHierarchy[$name][] = $dir;
            }
            $container->addResource(new FileExistenceResource($dir));
        }

        return $bundleHierarchy;
    }

    private function normalizeBundleName(string $name): string
    {
        if (str_ends_with($name, 'Bundle')) {
            $name = substr($name, 0, -6);
        }

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath(): string|false
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace(): string
    {
        return 'http://easy-admin.com/schema/dic/twig';
    }
}
