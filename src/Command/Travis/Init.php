<?php

namespace App\Command\Travis;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
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
            ->setName('travis:init')

            // the short description shown while running "php bin/console list"
            ->setDescription('Start a new Travis suite for your site.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Start a new Travis suite for your site..')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->check($input, $output);

        $path = self::$files;
        $tmp  = '/tmp/vhost.tmp';
        $travis_config = $path . "/travis/.travis.yml";
        $travis_before = $path . "/travis/before_install.sh";
        $travis_scripts = $this->target_dir . "/scripts/travis/";

        `cp $travis_config $this->target_dir`;

        mkdir($travis_scripts);

        `cp $travis_before $travis_scripts`;

    }

    protected function check(InputInterface $input, OutputInterface $output)
    {
        if (file_exists($this->target_dir . DIRECTORY_SEPARATOR . ".travis.yml"))
        {
            throw new \RuntimeException('Travis is already installed');
            return;
        }
    }
}