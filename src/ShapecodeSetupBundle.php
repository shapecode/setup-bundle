<?php

namespace Shapecode\Bundle\SetupBundle;

use Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler\SetupCommandCompiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ShapecodeSetupBundle
 *
 * @package Shapecode\Bundle\InstallationBundle
 * @author  Nikita Loges
 */
class ShapecodeSetupBundle extends Bundle
{

    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SetupCommandCompiler());
    }
}
