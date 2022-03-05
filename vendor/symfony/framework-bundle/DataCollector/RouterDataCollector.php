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

use easy-admin\Bundle\FrameworkBundle\Controller\RedirectController;
use easy-admin\Component\HttpFoundation\Request;
use easy-admin\Component\HttpKernel\DataCollector\RouterDataCollector as BaseRouterDataCollector;

/**
 * @author Fabien Potencier <fabien@easy-admin.com>
 *
 * @final
 */
class RouterDataCollector extends BaseRouterDataCollector
{
    public function guessRoute(Request $request, mixed $controller)
    {
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof RedirectController) {
            return $request->attributes->get('_route');
        }

        return parent::guessRoute($request, $controller);
    }
}
