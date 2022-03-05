<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\FrameworkBundle\DataCollector;

use easy-admin\Component\HttpKernel\DataCollector\DataCollector;

/**
 * @author Laurent VOULLEMIER <laurent.voullemier@gmail.com>
 */
abstract class AbstractDataCollector extends DataCollector implements TemplateAwareDataCollectorInterface
{
    public function getName(): string
    {
        return static::class;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    public static function getTemplate(): ?string
    {
        return null;
    }
}
