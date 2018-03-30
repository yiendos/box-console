<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Config extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:config')

            // the short description shown while running "php bin/console list"
            ->setDescription('Manage git configuration for a local repo or globally.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Quickly change repo settings...')
        ;
    }
}