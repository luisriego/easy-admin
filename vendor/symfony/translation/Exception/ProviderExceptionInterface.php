<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Translation\Exception;

/**
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
interface ProviderExceptionInterface extends ExceptionInterface
{
    /*
     * Returns debug info coming from the easy-admin\Contracts\HttpClient\ResponseInterface
     */
    public function getDebug(): string;
}
