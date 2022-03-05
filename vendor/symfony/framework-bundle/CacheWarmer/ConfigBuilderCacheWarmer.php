<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\FrameworkBundle\CacheWarmer;

use Psr\Log\LoggerInterface;
use easy-admin\Component\Config\Builder\ConfigBuilderGenerator;
use easy-admin\Component\Config\Builder\ConfigBuilderGeneratorInterface;
use easy-admin\Component\Config\Definition\ConfigurationInterface;
use easy-admin\Component\DependencyInjection\ContainerBuilder;
use easy-admin\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;
use easy-admin\Component\DependencyInjection\Extension\ExtensionInterface;
use easy-admin\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use easy-admin\Component\HttpKernel\KernelInterface;

/**
 * Generate all config builders.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ConfigBuilderCacheWarmer implements CacheWarmerInterface
{
    private $kernel;
    private $logger;

    public function __construct(KernelInterface $kernel, LoggerInterface $logger = null)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    public function warmUp(string $cacheDir): array
    {
        $generator = new ConfigBuilderGenerator($cacheDir);

        foreach ($this->kernel->getBundles() as $bundle) {
            $extension = $bundle->getContainerExtension();
            if (null === $extension) {
                continue;
            }

            try {
                $this->dumpExtension($extension, $generator);
            } catch (\Exception $e) {
                if ($this->logger) {
                    $this->logger->warning('Failed to generate ConfigBuilder for extension {extensionClass}.', ['exception' => $e, 'extensionClass' => \get_class($extension)]);
                }
            }
        }

        // No need to preload anything
        return [];
    }

    private function dumpExtension(ExtensionInterface $extension, ConfigBuilderGeneratorInterface $generator): void
    {
        $configuration = null;
        if ($extension instanceof ConfigurationInterface) {
            $configuration = $extension;
        } elseif ($extension instanceof ConfigurationExtensionInterface) {
            $configuration = $extension->getConfiguration([], new ContainerBuilder($this->kernel->getContainer()->getParameterBag()));
        }

        if (!$configuration) {
            return;
        }

        $generator->build($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional(): bool
    {
        return true;
    }
}
