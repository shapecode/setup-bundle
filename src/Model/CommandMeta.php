<?php

namespace Shapecode\Bundle\SetupBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Command\Command;

/**
 * Class CommandMeta
 *
 * @package Shapecode\Bundle\SetupBundle\Model
 * @author  Nikita Loges
 */
class CommandMeta
{

    /** @var integer */
    protected $priority = 0;

    /** @var string */
    protected $command;

    /** @var ArrayCollection */
    protected $arguments;

    /**
     * @param         $priority
     * @param Command $command
     * @param array   $arguments
     */
    public function __construct($priority, Command $command, $arguments)
    {
        $this->command = $command;
        $this->priority = $priority;
        $this->arguments = $arguments;
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
     * @return string
     */
    public function getFullCommand()
    {
        $command = $this->getCommand()->getName();

        if (!empty($this->getArguments())) {
            $command .= ' ' . $this->getArguments();
        }

        return $command;
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
     * @return string
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param string $arguments
     */
    protected function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }
}