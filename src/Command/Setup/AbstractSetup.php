<?php

namespace Shapecode\Bundle\SetupBundle\Command\Setup;

use Doctrine\Common\Persistence\ManagerRegistry;
use Shapecode\Bundle\SetupBundle\Setup\ReferenceManagerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractSetup
 *
 * @package Shapecode\Bundle\SetupBundle\Command\Setup
 * @author  Nikita Loges
 */
abstract class AbstractSetup extends Command implements SetupInterface
{

    /** @var ManagerRegistry */
    protected $registry;

    /** @var ReferenceManagerInterface */
    protected $referenceManager;

    /**
     * @param ManagerRegistry $registry
     */
    public function setRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param ReferenceManagerInterface $referenceManager
     */
    public function setReferenceManager(ReferenceManagerInterface $referenceManager)
    {
        $this->referenceManager = $referenceManager;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getManager()
    {
        return $this->registry->getManager();
    }

    /**
     * @return \Shapecode\Bundle\SetupBundle\Setup\ReferenceManagerInterface
     */
    protected function getReferenceManager()
    {
        return $this->referenceManager;
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
     *
     * @return object
     */
    public function getReference($name)
    {
        return $this->getReferenceManager()->getReference($name);
    }

    /**
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasReference($name)
    {
        return $this->getReferenceManager()->hasReference($name);
    }
}