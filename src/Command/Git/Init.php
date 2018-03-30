<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:init')

            // the short description shown while running "php bin/console list"
            ->setDescription('Start a new git repo for your project.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Start a git repo for your code...')

            ->addArgument(
                'site',
                InputArgument::REQUIRED,
                'Alphanumeric site name. Also used in the site URL with .test domain'
            )

            ->addOption(
                'www',
                null,
                InputOption::VALUE_REQUIRED,
                "Web server root",
                '/var/www'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->site       = $input->getArgument('site');
        $this->www        = $input->getOption('www');
        $this->target_dir = $this->www . '/' . $this->site;

        $this->check($input, $output);

        if (!is_dir($this->target_dir))
        {
            $output->writeln("Target directory doesn't exist creating it");

            `mkdir -p $this->target_dir`;
        }

        if ($this->target_dir != shell_exec('pwd'))
        {
            $output->writeLn('Changing to site directory');
            chdir($this->target_dir);
        }

        passthru('git init');

        `touch .gitignore`;
        `echo ".git-ftp*" > .gitignore`;
        `git add README.md`;
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
    }
}