<?php

namespace Shapecode\Bundle\SetupBundle\DependencyInjection\Compiler;

use Shapecode\Bundle\SetupBundle\Command\SetupCommand;
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
        $definition = $container->getDefinition(SetupCommand::class);
        $tags = $container->findTaggedServiceIds('shapecode_setup.routine');

        foreach ($tags as $id => $params) {
            foreach ($params as $param) {
                $arguments = $param['arguments'] ?? null;
                $priority = $param['priority'] ?? 0;
                $setup = $param['setup'] ?? 'default';

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
