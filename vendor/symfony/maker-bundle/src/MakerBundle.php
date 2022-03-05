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

use easy-admin\Bundle\MakerBundle\DependencyInjection\CompilerPass\DoctrineAttributesCheckPass;
use easy-admin\Bundle\MakerBundle\DependencyInjection\CompilerPass\MakeCommandRegistrationPass;
use easy-admin\Bundle\MakerBundle\DependencyInjection\CompilerPass\RemoveMissingParametersPass;
use easy-admin\Bundle\MakerBundle\DependencyInjection\CompilerPass\SetDoctrineAnnotatedPrefixesPass;
use easy-admin\Bundle\MakerBundle\DependencyInjection\CompilerPass\SetDoctrineManagerRegistryClassPass;
use easy-admin\Component\DependencyInjection\Compiler\PassConfig;
use easy-admin\Component\DependencyInjection\ContainerBuilder;
use easy-admin\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Ryan Weaver <weaverryan@gmail.com>
 */
class MakerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        // add a priority so we run before the core command pass
        $container->addCompilerPass(new DoctrineAttributesCheckPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 11);
        $container->addCompilerPass(new MakeCommandRegistrationPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
        $container->addCompilerPass(new RemoveMissingParametersPass());
        $container->addCompilerPass(new SetDoctrineManagerRegistryClassPass());
        $container->addCompilerPass(new SetDoctrineAnnotatedPrefixesPass());
    }
}
