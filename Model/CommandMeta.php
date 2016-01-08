<?php

namespace Shapecode\Bundle\SetupBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandMeta
 * @package Shapecode\Bundle\SetupBundle\Model
 * @author Nikita Loges
 */
class CommandMeta
{

    /** @var integer */
    protected $priority = 0;

    /** @var string */
    protected $id;

    /** @var ArrayCollection */
    protected $arguments;

    /**
     * @param Command $command
     * @param string $arguments
     * @param integer $priority
     */
    public function __construct(Command $command, $arguments, $priority)
    {
        $this->command = $command;
        $this->arguments = new ArrayCollection();
        $this->priority = $priority;

        $arguments = trim($arguments);
        if (!empty($arguments)) {
            $arguments = array_filter(array_map('trim', explode(' ', $arguments)), 'strlen');
            $this->arguments = new ArrayCollection($arguments);
        }
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param Command $command
     */
    protected function setCommand(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @return ArrayCollection
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param ArrayCollection $arguments
     */
    protected function setArguments(ArrayCollection $arguments)
    {
        $this->arguments = $arguments;
    }
}