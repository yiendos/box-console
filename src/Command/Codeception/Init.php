<?php

namespace App\Command\Codeception;

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
            ->setName('codecept:init')

            // the short description shown while running "php bin/console list"
            ->setDescription('Start a new codeception project for your site .')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Start a new codeception project for your site...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->check($input, $output);

        passthru('codecept init acceptance');
    }

    protected function check(InputInterface $input, OutputInterface $output)
    {
        parent::check($input, $output);

        $result = shell_exec('codecept --version >/dev/null 2>&1 || { echo "false"; }');

        if (trim($result) == 'false')
        {
            throw new \RuntimeException(sprintf('Codeception needs to be installed globally'));
            return;
        }

        if (file_exists($this->target_dir . '/codeception.yml'))
        {
            throw new \RuntimeException('A codeception project already exisits');
            return;
        }


        /**
         * we don't actually need chromedriver or selenium for vagrant box testing
         * @todo this should be added as a todo when starting codeception
         */
        /*if (!file_exists('/home/vagrant/chromedriver'))
        {
            $output->writeLn('<info>Currently downloading and installing chromedriver</info>');

            `curl -s -L https://chromedriver.storage.googleapis.com/2.37/chromedriver_linux64.zip -o /tmp/chromedriver.zip`;
            
            `unzip /tmp/chromedriver.zip -d /home/vagrant/`;
            
            `chmod +x /home/vagrant/chromedriver`;
            
            passthru(`/home/vagrant/chromedriver --verbose --log-path=/tmp/chromedriver.log --url-base=/wd/hub &`);
        }

        $result = shell_exec('java --version >/dev/null 2>&1 || { echo "false"; }');

        if (trim($result) == 'false')
        {
            throw new \RuntimeException(sprintf('Java needs to be installed globally'));
            return;
        }

        if (!file_exists('/home/vagrant/selenium.jar'))
        {
            $output->writeLn('<info>Currently downloading and installing selenium server</info>');

            `curl -s -L https://goo.gl/UzaKCo -o /home/vagrant/selenium.jar`;

            passthru(`java -jar /home/vagrant/selenium.jar`);
        }*/
    }
}