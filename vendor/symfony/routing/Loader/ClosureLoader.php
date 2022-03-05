<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Routing\Loader;

use easy-admin\Component\Config\Loader\Loader;
use easy-admin\Component\Routing\RouteCollection;

/**
 * ClosureLoader loads routes from a PHP closure.
 *
 * The Closure must return a RouteCollection instance.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
class ClosureLoader extends Loader
{
    /**
     * Loads a Closure.
     */
    public function load(mixed $closure, string $type = null): RouteCollection
    {
        return $closure($this->env);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(mixed $resource, string $type = null): bool
    {
        return $resource instanceof \Closure && (!$type || 'closure' === $type);
    }
}
