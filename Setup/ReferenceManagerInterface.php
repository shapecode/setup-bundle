<?php

namespace Shapecode\Bundle\SetupBundle\Setup;

/**
 * Interface ReferenceManagerInterface
 * @package Shapecode\Bundle\SetupBundle\Setup
 * @author Nikita Loges
 */
interface ReferenceManagerInterface
{


    /**
     * @param $name
     * @param $object
     */
    public function addReference($name, $object);

    /**
     * @param $name
     * @param $reference
     */
    public function setReference($name, $reference);

    /**
     * @param $name
     * @return mixed|null|object
     */
    public function getReference($name);

    /**
     * @param $name
     * @return bool
     */
    public function hasReference($name);

    /**
     * @param $name
     * @param $identity
     */
    public function setIdentity($name, $identity);

    /**
     * @param $name
     * @return bool
     */
    public function hasIdentity($name);
}