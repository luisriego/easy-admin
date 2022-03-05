<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Console\CommandLoader;

use easy-admin\Component\Console\Command\Command;
use easy-admin\Component\Console\Exception\CommandNotFoundException;

/**
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface CommandLoaderInterface
{
    /**
     * Loads a command.
     *
     * @throws CommandNotFoundException
     */
    public function get(string $name): Command;

    /**
     * Checks if a command exists.
     */
    public function has(string $name): bool;

    /**
     * @return string[]
     */
    public function getNames(): array;
}
