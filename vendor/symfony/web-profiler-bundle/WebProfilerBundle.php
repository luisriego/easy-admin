<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\WebProfilerBundle;

use easy-admin\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
class WebProfilerBundle extends Bundle
{
    public function boot()
    {
        if ('prod' === $this->container->getParameter('kernel.environment')) {
            @trigger_error('Using WebProfilerBundle in production is not supported and puts your project at risk, disable it.', \E_USER_WARNING);
        }
    }
}
