<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Translation\Writer;

use easy-admin\Component\Translation\Exception\InvalidArgumentException;
use easy-admin\Component\Translation\MessageCatalogue;

/**
 * TranslationWriter writes translation messages.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
interface TranslationWriterInterface
{
    /**
     * Writes translation from the catalogue according to the selected format.
     *
     * @param string $format  The format to use to dump the messages
     * @param array  $options Options that are passed to the dumper
     *
     * @throws InvalidArgumentException
     */
    public function write(MessageCatalogue $catalogue, string $format, array $options = []);
}
