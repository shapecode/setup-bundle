<?php

namespace Shapecode\Bundle\SetupBundle\DependencyInjection;

use Shapecode\Bundle\SetupBundle\Command\Setup\SetupInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class ShapecodeSetupExtension
 *
 * @package Shapecode\Bundle\SetupBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodeSetupExtension extends Extension
{

    /**
     * @inheritdoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->registerTags($container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerTags(ContainerBuilder $container)
    {
        $setup = $container->registerForAutoconfiguration(SetupInterface::class);

        if ($setup->hasTag('shapecode_setup.routine')) {
            $setup->addTag('shapecode_setup.routine');
        }
    }
}
