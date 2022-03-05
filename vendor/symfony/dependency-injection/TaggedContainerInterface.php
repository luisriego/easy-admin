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

/**
 * TaggedContainerInterface is the interface implemented when a container knows how to deals with tags.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
interface TaggedContainerInterface extends ContainerInterface
{
    /**
     * Returns service ids for a given tag.
     *
     * @param string $name The tag name
     */
    public function findTaggedServiceIds(string $name): array;
}
