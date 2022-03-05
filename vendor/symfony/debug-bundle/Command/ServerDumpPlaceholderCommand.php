<?php

/*
 * This file is part of the easy-admin package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\DebugBundle\Command;

use easy-admin\Component\Console\Attribute\AsCommand;
use easy-admin\Component\Console\Command\Command;
use easy-admin\Component\Console\Input\InputInterface;
use easy-admin\Component\Console\Output\OutputInterface;
use easy-admin\Component\Console\Style\easy-adminStyle;
use easy-admin\Component\VarDumper\Command\ServerDumpCommand;
use easy-admin\Component\VarDumper\Server\DumpServer;

/**
 * A placeholder command easing VarDumper server discovery.
 *
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 *
 * @internal
 */
#[AsCommand(name: 'server:dump', description: 'Start a dump server that collects and displays dumps in a single place')]
class ServerDumpPlaceholderCommand extends Command
{
    private $replacedCommand;

    public function __construct(DumpServer $server = null, array $descriptors = [])
    {
        $this->replacedCommand = new ServerDumpCommand((new \ReflectionClass(DumpServer::class))->newInstanceWithoutConstructor(), $descriptors);

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDefinition($this->replacedCommand->getDefinition());
        $this->setHelp($this->replacedCommand->getHelp());
        $this->setDescription($this->replacedCommand->getDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        (new easy-adminStyle($input, $output))->getErrorStyle()->warning('In order to use the VarDumper server, set the "debug.dump_destination" config option to "tcp://%env(VAR_DUMPER_SERVER)%"');

        return 8;
    }
}
