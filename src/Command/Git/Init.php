<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Command\AbstractClass;

class Init extends AbstractClass
{
    protected function configure()
    {
        parent::configure();

        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:init')

            // the short description shown while running "php bin/console list"
            ->setDescription('Start a new git repo for your project.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Start a git repo for your code...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->check($input, $output);

        passthru('git init');

        `touch .gitignore README.md`;
        `echo ".git-ftp*" > .gitignore`;
        `git add -A`;
        `git commit -a -m "Initial commit"`;
    }

    protected function check(InputInterface $input, OutputInterface $output)
    {
        $result = shell_exec('git --version >/dev/null 2>&1 || { echo "false"; }');

        if (trim($result) == 'false')
        {
            throw new \RuntimeException(sprintf('Git cannot be found, install this first'));
            return;
        }

        if (file_exists($this->target_dir . '/.git'))
        {
            throw new \RuntimeException(sprintf('Git already initialized'));
            return;
        }

        if (!is_dir($this->target_dir))
        {
            $output->writeln("Target directory doesn't exist creating it");

            `mkdir -p $this->target_dir`;
        }

        parent::check($input, $output);
    }
}