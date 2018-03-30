<?php

namespace App\Command\Git;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use App\Command\AbstractClass;

class Config extends AbstractClass
{
    protected function configure()
    {
        parent::configure();

        $this
            // the name of the command (the part after "bin/console")
            ->setName('git:config')

            // the short description shown while running "php bin/console list"
            ->setDescription('Manage git configuration for a local repo or globally.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Quickly change repo settings...')

            ->addOption(
                'config',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Pass an array of config items (key, value)'
            )

            ->addOption(
                'list',
                null,
                InputOption::VALUE_REQUIRED,
                'Show current git config details',
                false

            )
            ->addOption(
                'global',
                null,
                InputOption::VALUE_REQUIRED,
                'do you wish to update global details',
                false
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->config     = $input->getOption('config');
        $this->list    = $input->getOption('list');
        $this->global     = $input->getOption('global');

        $this->check($input, $output);

        $global = '';

        if ($this->global) {
            $global = '--global';
        }

        if ($this->list)
        {
            passthru("git config $global --list");
            return;
        }


        foreach ($this->config as $config)
        {
            list($key, $value) = explode("=", $config);

            `git config $global $key "$value"`;

            $result = shell_exec('git config ' . $global . "$key");

            if (trim($result) == $value){
                $output->writeLn('<info>Git config: ' . $key . ' has successfully been updated: ' . $value . '</info>');
            }else{
                $output->writeLn('<error>Git config: ' . $key . ' has not been updated</error>');
            }
        }
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
    }
}