<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Connect extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:connect')

            // the short description shown while running "php bin/console list"
            ->setDescription('Connect local repos to their remote counterparts.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('After a new repo has been created push the contents to newly created git hosting...')
        ;
    }
}