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

    protected static $files;

    protected function configure()
    {
        if (empty(self::$files)) {
            self::$files = $this->getTemplatePath();
        }

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
        $current_dir = shell_exec('pwd');

        //@todo this doesn't actually work because it returns home/vagrant/Projects
        if ($this->target_dir != $current_dir)
        {
            $output->writeLn('Changing to site directory');
            chdir($this->target_dir);
        }
    }

    //@todo come back to this to avoid hardcoded paths
    public static function getTemplatePath()
    {
        $root = dirname(dirname(dirname(dirname(__DIR__))));

        return $root . DIRECTORY_SEPARATOR . "Projects" . DIRECTORY_SEPARATOR . 'box-console' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . '.files';
    }
}