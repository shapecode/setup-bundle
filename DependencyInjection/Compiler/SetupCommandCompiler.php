<?php

namespace Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SetupCommandCompiler
 * @package Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler
 * @author Nikita Loges
 */
class SetupCommandCompiler implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('shapecode_setup.command.setup')) {
            return;
        }

        $definition = $container->getDefinition('shapecode_setup.command.setup');

        foreach ($container->findTaggedServiceIds('shapecode_setup.routine') as $id => $params) {
            foreach ($params as $param) {
                $arguments = (isset($param['arguments'])) ? $param['arguments'] : null;
                $priority = (isset($param['priority'])) ? $param['priority'] : 10;
                $setup = (isset($param['setup'])) ? $param['setup'] : 'default';

                $definition->addMethodCall('addCommand', array(
                    $setup,
                    new Reference($id),
                    $arguments,
                    $priority
                ));
            }
        }
    }
}
