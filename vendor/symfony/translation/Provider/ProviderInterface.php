<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Translation\Provider;

use easy-admin\Component\Translation\TranslatorBag;
use easy-admin\Component\Translation\TranslatorBagInterface;

interface ProviderInterface
{
    public function __toString(): string;

    /**
     * Translations available in the TranslatorBag only must be created.
     * Translations available in both the TranslatorBag and on the provider
     * must be overwritten.
     * Translations available on the provider only must be kept.
     */
    public function write(TranslatorBagInterface $translatorBag): void;

    public function read(array $domains, array $locales): TranslatorBag;

    public function delete(TranslatorBagInterface $translatorBag): void;
}
