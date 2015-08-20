<?php

namespace Shapecode\Bundle\SetupBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Shapecode\Bundle\SetupBundle\Model\CommandMeta;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

        $this->configureOptions();
    }

    /**
     *
     */
    protected function configureOptions()
    {
        $this->addOption('setup', null, InputOption::VALUE_REQUIRED, 'Which Setup should executed?', 'default');
        $this->addOption('force', null, InputOption::VALUE_NONE, 'Which Setup should executed?');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('setup');
        $force = $input->getOption('force');

        if (!$this->hasCommandSet($name) || !$this->getCommandSet($name)->count()) {
            $output->writeln('there is no setup script.');

            return;
        }

        $commandSet = $this->getCommandSet($name);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        if (!$force) {
            $question = new Question('Do you want to fire up setup? (y/n)');
            $yesno = $helper->ask($input, $output, $question);

            if ($yesno != 'y') {
                $output->writeln('exiting ...');
            }
        }

        $iterator = $commandSet->getIterator();
        $iterator->uasort(function (CommandMeta $a, CommandMeta $b) {
            return ($a->getPriority() < $b->getPriority()) ? -1 : 1;
        });
        $commandSet = new ArrayCollection(iterator_to_array($iterator));

        /** @var CommandMeta $meta */
        foreach ($commandSet as $meta) {
            $command = $meta->getCommand();

            $inputArray = array_replace_recursive($meta->getArguments()->toArray(), array(
                'command' => $command->getName()
            ));
            $commandI = new ArrayInput($inputArray);
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
     * @return ArrayCollection|ArrayCollection[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @return ArrayCollection|Command[]
     */
    public function getCommandSet($name)
    {
        return $this->getCommands()->get($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasCommandSet($name)
    {
        return $this->getCommands()->containsKey($name);
    }

    /**
     * @param $name
     * @param Command $command
     * @param $arguments
     * @param $priority
     */
    public function addCommand($name, Command $command, $arguments, $priority)
    {
        if (!$this->commands->containsKey($name)) {
            $this->commands->set($name, new ArrayCollection());
        }

        $meta = new CommandMeta($command, $arguments, $priority);

        $this->commands->get($name)->add($meta);
    }

}