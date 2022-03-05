<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Component\Translation\Loader;

use easy-admin\Component\Config\Resource\DirectoryResource;
use easy-admin\Component\Translation\Exception\InvalidResourceException;
use easy-admin\Component\Translation\Exception\NotFoundResourceException;
use easy-admin\Component\Translation\MessageCatalogue;

/**
 * IcuResFileLoader loads translations from a resource bundle.
 *
 * @author stealth35
 */
class IcuResFileLoader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(mixed $resource, string $locale, string $domain = 'messages'): MessageCatalogue
    {
        if (!stream_is_local($resource)) {
            throw new InvalidResourceException(sprintf('This is not a local file "%s".', $resource));
        }

        if (!is_dir($resource)) {
            throw new NotFoundResourceException(sprintf('File "%s" not found.', $resource));
        }

        try {
            $rb = new \ResourceBundle($locale, $resource);
        } catch (\Exception $e) {
            $rb = null;
        }

        if (!$rb) {
            throw new InvalidResourceException(sprintf('Cannot load resource "%s".', $resource));
        } elseif (intl_is_failure($rb->getErrorCode())) {
            throw new InvalidResourceException($rb->getErrorMessage(), $rb->getErrorCode());
        }

        $messages = $this->flatten($rb);
        $catalogue = new MessageCatalogue($locale);
        $catalogue->add($messages, $domain);

        if (class_exists(DirectoryResource::class)) {
            $catalogue->addResource(new DirectoryResource($resource));
        }

        return $catalogue;
    }

    /**
     * Flattens an ResourceBundle.
     *
     * The scheme used is:
     *   key { key2 { key3 { "value" } } }
     * Becomes:
     *   'key.key2.key3' => 'value'
     *
     * This function takes an array by reference and will modify it
     *
     * @param \ResourceBundle $rb       The ResourceBundle that will be flattened
     * @param array           $messages Used internally for recursive calls
     * @param string          $path     Current path being parsed, used internally for recursive calls
     */
    protected function flatten(\ResourceBundle $rb, array &$messages = [], string $path = null): array
    {
        foreach ($rb as $key => $value) {
            $nodePath = $path ? $path.'.'.$key : $key;
            if ($value instanceof \ResourceBundle) {
                $this->flatten($value, $messages, $nodePath);
            } else {
                $messages[$nodePath] = $value;
            }
        }

        return $messages;
    }
}
