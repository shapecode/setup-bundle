<?php

namespace Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SetupCommandCompiler
 *
 * @package Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler
 * @author  Nikita Loges
 */
class SetupCommandCompiler implements CompilerPassInterface
{

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('shapecode_setup.command.setup');
        $tags = $container->findTaggedServiceIds('shapecode_setup.routine');

        foreach ($tags as $id => $params) {
            foreach ($params as $param) {
                $arguments = (isset($param['arguments'])) ? $param['arguments'] : null;
                $priority = (isset($param['priority'])) ? $param['priority'] : 0;
                $setup = (isset($param['setup'])) ? $param['setup'] : 'default';

                $definition->addMethodCall('addCommand', [
                    $setup,
                    new Reference($id),
                    $arguments,
                    $priority
                ]);
            }
        }
    }
}
