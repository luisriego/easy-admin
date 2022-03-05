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

use Doctrine\Common\Annotations\AnnotationException;
use easy-admin\Component\Cache\Adapter\ArrayAdapter;
use easy-admin\Component\Cache\Adapter\PhpArrayAdapter;
use easy-admin\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use easy-admin\Component\Validator\Mapping\Loader\LoaderChain;
use easy-admin\Component\Validator\Mapping\Loader\LoaderInterface;
use easy-admin\Component\Validator\Mapping\Loader\XmlFileLoader;
use easy-admin\Component\Validator\Mapping\Loader\YamlFileLoader;
use easy-admin\Component\Validator\ValidatorBuilder;

/**
 * Warms up XML and YAML validator metadata.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class ValidatorCacheWarmer extends AbstractPhpFileCacheWarmer
{
    private $validatorBuilder;

    /**
     * @param string $phpArrayFile The PHP file where metadata are cached
     */
    public function __construct(ValidatorBuilder $validatorBuilder, string $phpArrayFile)
    {
        parent::__construct($phpArrayFile);
        $this->validatorBuilder = $validatorBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWarmUp(string $cacheDir, ArrayAdapter $arrayAdapter): bool
    {
        if (!method_exists($this->validatorBuilder, 'getLoaders')) {
            return false;
        }

        $loaders = $this->validatorBuilder->getLoaders();
        $metadataFactory = new LazyLoadingMetadataFactory(new LoaderChain($loaders), $arrayAdapter);

        foreach ($this->extractSupportedLoaders($loaders) as $loader) {
            foreach ($loader->getMappedClasses() as $mappedClass) {
                try {
                    if ($metadataFactory->hasMetadataFor($mappedClass)) {
                        $metadataFactory->getMetadataFor($mappedClass);
                    }
                } catch (AnnotationException $e) {
                    // ignore failing annotations
                } catch (\Exception $e) {
                    $this->ignoreAutoloadException($mappedClass, $e);
                }
            }
        }

        return true;
    }

    /**
     * @return string[] A list of classes to preload on PHP 7.4+
     */
    protected function warmUpPhpArrayAdapter(PhpArrayAdapter $phpArrayAdapter, array $values): array
    {
        // make sure we don't cache null values
        $values = array_filter($values, function ($val) { return null !== $val; });

        return parent::warmUpPhpArrayAdapter($phpArrayAdapter, $values);
    }

    /**
     * @param LoaderInterface[] $loaders
     *
     * @return XmlFileLoader[]|YamlFileLoader[]
     */
    private function extractSupportedLoaders(array $loaders): array
    {
        $supportedLoaders = [];

        foreach ($loaders as $loader) {
            if ($loader instanceof XmlFileLoader || $loader instanceof YamlFileLoader) {
                $supportedLoaders[] = $loader;
            } elseif ($loader instanceof LoaderChain) {
                $supportedLoaders = array_merge($supportedLoaders, $this->extractSupportedLoaders($loader->getLoaders()));
            }
        }

        return $supportedLoaders;
    }
}
