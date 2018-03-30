<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Command\AbstractClass;

class Connect extends AbstractClass
{
    protected function configure()
    {
        parent::configure();

        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:connect')

            // the short description shown while running "php bin/console list"
            ->setDescription('Start a new git repo for your project.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Start a git repo for your code...')

            ->addArgument(
                'remote',
                InputArgument::REQUIRED,
                'Provide the remote origin'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->remote = $input->getArgument('remote');

        $this->check($input, $output);

        passthru('git remote add origin ' . $this->remote);
        passthru('git push -u origin master');
    }

    protected function check(InputInterface $input, OutputInterface $output)
    {
        $result = shell_exec('git --version >/dev/null 2>&1 || { echo "false"; }');

        if (trim($result) == 'false')
        {
            throw new \RuntimeException(sprintf('Git cannot be found, install this first'));
            return;
        }

        if (!is_dir($this->target_dir))
        {
            throw new \RuntimeException(sprintf("Target site doesn't exist, first create through `box git:init`"));
            return;
        }

        if ($this->target_dir != shell_exec('pwd'))
        {
            $output->writeLn('Changing to site directory');
            chdir($this->target_dir);
        }

        //does the remote already exist, if so stop now
        $result = shell_exec('git config --list | grep "remote.origin.url" || { echo "false"; }');

        if (trim($result) != 'false')
        {
            throw new \RuntimeException(sprintf('Git repo already has a remote origin'));
            return;
        }
    }
}