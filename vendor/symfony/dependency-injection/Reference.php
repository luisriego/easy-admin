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
 * Reference represents a service reference.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
class Reference
{
    private string $id;
    private int $invalidBehavior;

    public function __construct(string $id, int $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $this->id = $id;
        $this->invalidBehavior = $invalidBehavior;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    /**
     * Returns the behavior to be used when the service does not exist.
     */
    public function getInvalidBehavior(): int
    {
        return $this->invalidBehavior;
    }
}
