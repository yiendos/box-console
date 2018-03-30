<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractClass extends Command
{
    protected $site;

    protected $target_dir;

    protected $www;

    protected function configure()
    {
        $this
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->site         = $input->getArgument('site');
        $this->www          = $input->getOption('www');
        $this->target_dir   = $this->www . '/' . $this->site;
    }

    protected function check(InputInterface $input, OutputInterface $output)
    {
        if ($this->target_dir != shell_exec('pwd'))
        {
            $output->writeLn('Changing to site directory');
            chdir($this->target_dir);
        }
    }
}