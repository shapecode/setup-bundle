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

    /**
     * @return \Shapecode\Bundle\SetupBundle\Setup\ReferenceManagerInterface
     */
    protected function getReferenceManager()
    {
        return $this->container->get('shapecode_setup.reference_manager');
    }

    /**
     * @param $name
     * @param $object
     */
    public function setReference($name, $object)
    {
        $this->getReferenceManager()->setReference($name, $object);
    }

    /**
     * @param string $name
     * @param object $object
     */
    public function addReference($name, $object)
    {
        $this->getReferenceManager()->addReference($name, $object);
    }

    /**
     * @param string $name
     * @return object
     */
    public function getReference($name)
    {
        return $this->getReferenceManager()->getReference($name);
    }

    /**
     *
     * @param string $name
     * @return boolean
     */
    public function hasReference($name)
    {
        return $this->getReferenceManager()->hasReference($name);
    }
}