<?php

/*
 * This file is part of the easy-admin MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\MakerBundle;

final class InputConfiguration
{
    private $nonInteractiveArguments = [];

    /**
     * Call in MakerInterface::configureCommand() to disable the automatic interactive
     * prompt for an argument.
     */
    public function setArgumentAsNonInteractive(string $argumentName): void
    {
        $this->nonInteractiveArguments[] = $argumentName;
    }

    public function getNonInteractiveArguments(): array
    {
        return $this->nonInteractiveArguments;
    }
}
