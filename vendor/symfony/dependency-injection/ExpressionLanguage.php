<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\DependencyInjection;

use Psr\Cache\CacheItemPoolInterface;
use easy-admin\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;

if (!class_exists(BaseExpressionLanguage::class)) {
    return;
}

/**
 * Adds some function to the default ExpressionLanguage.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 *
 * @see ExpressionLanguageProvider
 */
class ExpressionLanguage extends BaseExpressionLanguage
{
    /**
     * {@inheritdoc}
     */
    public function __construct(CacheItemPoolInterface $cache = null, array $providers = [], callable $serviceCompiler = null)
    {
        // prepend the default provider to let users override it easily
        array_unshift($providers, new ExpressionLanguageProvider($serviceCompiler));

        parent::__construct($cache, $providers);
    }
}
