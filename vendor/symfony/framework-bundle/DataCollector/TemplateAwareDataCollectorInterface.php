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

use easy-admin\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * @author Laurent VOULLEMIER <laurent.voullemier@gmail.com>
 */
interface TemplateAwareDataCollectorInterface extends DataCollectorInterface
{
    public static function getTemplate(): ?string;
}
