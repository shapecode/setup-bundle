<?php

namespace Shapecode\Bundle\SetupBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Shapecode\Bundle\SetupBundle\Model\CommandMeta;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class SetupCommand
 *
 * @package Shapecode\Bundle\SetupBundle\Command
 * @author  Nikita Loges
 */
class SetupCommand extends Command
{

    /** @var ArrayCollection|Command[] */
    protected $commands;

    /**
     *
     */
    public function __construct()
    {
        $this->commands = new ArrayCollection();

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('shapecode:setup');

        $this->addArgument('setup', InputArgument::OPTIONAL, 'Which Setup should executed?', 'default');
        $this->addOption('force', null, InputOption::VALUE_NONE, 'Which Setup should executed?');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('setup');
        $force = $input->getOption('force');

        if (!$this->hasCommandSet($name) || !$this->getCommandSet($name)->count()) {
            $output->writeln('there is no setup script.');

            return;
        }

        $commandSet = $this->getCommandSet($name);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelperSet()->get('question');

        if (!$force) {
            $question = new ConfirmationQuestion('Do you want to fire up setup?', false);

            if (!$helper->ask($input, $output, $question)) {
                $output->writeln('exiting ...');
                exit;
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

            $output->writeln('');
            $output->writeln('execute command "' . $command->getName() . '":');

            $commandI = new StringInput($meta->getFullCommand());
            $this->getApplication()->doRun($commandI, $output);
        }
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
     *
     * @return bool
     */
    public function hasCommandSet($name)
    {
        return $this->getCommands()->containsKey($name);
    }

    /**
     * @param         $name
     * @param Command $command
     * @param         $arguments
     * @param         $priority
     */
    public function addCommand($name, Command $command, $arguments, $priority)
    {
        if (!$this->commands->containsKey($name)) {
            $this->commands->set($name, new ArrayCollection());
        }

        $meta = new CommandMeta($priority, $command, $arguments);

        $this->commands->get($name)->add($meta);
    }

}