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
 * Thrown when a resource cannot be loaded.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
class InvalidResourceException extends \InvalidArgumentException implements ExceptionInterface
{
}
