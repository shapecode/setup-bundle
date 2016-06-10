<?php

namespace Shapecode\Bundle\SetupBundle\Setup;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

/**
 * Class ReferenceManager
 * @package Shapecode\Bundle\SetupBundle\Setup
 * @author Nikita Loges
 */
class ReferenceManager implements ReferenceManagerInterface
{

    /** @var ArrayCollection */
    protected $references;

    /** @var ArrayCollection */
    protected $identities;

    /** @var  EntityManagerInterface */
    protected $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->references = new ArrayCollection();
        $this->identities = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    protected function getReferences()
    {
        return $this->references;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getManager()
    {
        return $this->manager;
    }

    /**
     * @inheritdoc
     */
    public function addReference($name, $object)
    {
        if ($this->hasReference($name)) {
            throw new \BadMethodCallException("Reference to: (" . $name . ") already exists, use method setReference in order to override it");
        }
        $this->setReference($name, $object);
    }

    /**
     * @inheritdoc
     */
    public function setReference($name, $reference)
    {
        $this->getReferences()->set($name, $reference);

        $uow = $this->getManager()->getUnitOfWork();
        if ($uow->isInIdentityMap($reference)) {
            $this->getIdentities()->set($name, $this->getIdentifier($reference, $uow));
        }
    }

    /**
     * @inheritdoc
     */
    public function getReference($name)
    {
        if (!$this->hasReference($name)) {
            throw new \OutOfBoundsException('Reference to: (' . $name . ') does not exist');
        }

        $reference = $this->getReferences()->get($name);

        $meta = $this->getManager()->getClassMetadata(get_class($reference));
        $uow = $this->getManager()->getUnitOfWork();

        if (!$uow->isInIdentityMap($reference) && $this->hasIdentity($name)) {
            $reference = $this->getManager()->getReference(
                $meta->name,
                $this->getIdentity($name)
            );
            $this->setReference($name, $reference);
        }

        return $reference;
    }

    /**
     * @inheritdoc
     */
    public function hasReference($name)
    {
        return $this->getReferences()->containsKey($name);
    }

    /**
     * @return ArrayCollection
     */
    protected function getIdentities()
    {
        return $this->identities;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    protected function getIdentity($name)
    {
        return $this->getIdentities()->get($name);
    }

    /**
     * @inheritdoc
     */
    public function setIdentity($name, $identity)
    {
        $this->getIdentities()->set($name, $identity);
    }

    /**
     * @inheritdoc
     */
    public function hasIdentity($name)
    {
        return $this->getIdentities()->containsKey($name);
    }

    /**
     * @param object $reference
     * @param UnitOfWork $uow
     * @return array
     */
    protected function getIdentifier($reference, UnitOfWork $uow)
    {
        // In case Reference is not yet managed in UnitOfWork
        if (!$uow->isInIdentityMap($reference)) {
            $class = $this->manager->getClassMetadata(get_class($reference));

            return $class->getIdentifierValues($reference);
        }

        // Dealing with ORM UnitOfWork
        if (method_exists($uow, 'getEntityIdentifier')) {
            return $uow->getEntityIdentifier($reference);
        }

        /** @todo handle this case */
    }
}