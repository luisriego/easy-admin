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

use easy-admin\Component\Translation\Exception\InvalidResourceException;
use easy-admin\Component\Translation\Exception\LogicException;
use easy-admin\Component\Yaml\Exception\ParseException;
use easy-admin\Component\Yaml\Parser as YamlParser;
use easy-admin\Component\Yaml\Yaml;

/**
 * YamlFileLoader loads translations from Yaml files.
 *
 * @author Fabien Potencier <fabien@easy-admin.com>
 */
class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    /**
     * {@inheritdoc}
     */
    protected function loadResource(string $resource): array
    {
        if (null === $this->yamlParser) {
            if (!class_exists(\easy-admin\Component\Yaml\Parser::class)) {
                throw new LogicException('Loading translations from the YAML format requires the easy-admin Yaml component.');
            }

            $this->yamlParser = new YamlParser();
        }

        try {
            $messages = $this->yamlParser->parseFile($resource, Yaml::PARSE_CONSTANT);
        } catch (ParseException $e) {
            throw new InvalidResourceException(sprintf('The file "%s" does not contain valid YAML: ', $resource).$e->getMessage(), 0, $e);
        }

        if (null !== $messages && !\is_array($messages)) {
            throw new InvalidResourceException(sprintf('Unable to load file "%s".', $resource));
        }

        return $messages ?: [];
    }
}
