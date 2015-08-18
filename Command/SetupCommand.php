<?php

namespace Shapecode\Bundle\SetupBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Shapecode\Bundle\SetupBundle\Command\Setup\SetupInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SetupCommand
 * @package Shapecode\Bundle\SetupBundle\Command
 * @author Nikita Loges
 * @date 20.07.2015
 */
class SetupCommand extends Command
{
    /** @var ContainerInterface */
    protected $container;

    /** @var ArrayCollection|Command[] */
    protected $commands;

    /**
     * @param null|string $name
     * @param ContainerInterface $container
     */
    public function __construct($name, ContainerInterface $container)
    {
        parent::__construct($name);

        $this->container = $container;
        $this->commands = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setDescription('Greet someone');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->getCommands()->count()) {
            $output->writeln('Keine Installations-Routinen vorhanden.');
            return;
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        $question = new Question('Do you want to fire up setup? (y/n)');
        $yesno = $helper->ask($input, $output, $question);

        if ($yesno != 'y') {
            $output->writeln('exiting ...');
        }

        foreach ($this->getCommands() as $command) {

            $commandI = new ArrayInput(array());
            $command->run($commandI, $output);
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return ArrayCollection|Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param Command $command
     */
    public function addCommand(Command $command)
    {
//        if (!($command instanceof SetupInterface)) {
//            throw new \RuntimeException('command must implement SetupInterface');
//        }

        $this->commands->add($command);
    }

}