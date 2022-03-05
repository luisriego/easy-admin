<?php

/*
 * This file is part of the easy-admin MakerBundle package.
 *
 * (c) Fabien Potencier <fabien@easy-admin.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace easy-admin\Bundle\MakerBundle;

use easy-admin\Component\Console\Input\InputInterface;
use easy-admin\Component\Console\Output\OutputInterface;
use easy-admin\Component\Console\Style\easy-adminStyle;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
final class ConsoleStyle extends easy-adminStyle
{
    private $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        parent::__construct($input, $output);
    }

    public function success($message): void
    {
        $this->writeln('<fg=green;options=bold,underscore>OK</> '.$message);
    }

    public function comment($message): void
    {
        $this->text($message);
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
