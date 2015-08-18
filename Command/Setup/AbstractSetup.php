<?php

namespace Shapecode\Bundle\SetupBundle\Command\Setup;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractSetup
 * @package Shapecode\Bundle\SetupBundle\Command\Setup
 * @author Nikita Loges
 * @date 20.07.2015
 */
class AbstractSetup extends Command implements SetupInterface
{

    /** @var ContainerInterface */
    protected $container;

    /**
     * @param null|string $name
     * @param ContainerInterface $container
     */
    public function __construct($name, ContainerInterface $container)
    {
        parent::__construct($name);

        $this->container = $container;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }
}