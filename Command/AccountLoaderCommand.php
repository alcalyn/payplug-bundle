<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;

class AccountLoaderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payplug:account:update')
            ->setDescription('Update your Payplug account parameters and set them into your parameters.yml')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Load test environment')
            ->addOption('no-prod', null, InputOption::VALUE_NONE, 'Do not load prod environment again')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        
        $output->writeLn('');
        
        $mail = $dialog->ask(
            $output,
            'Payplug account mail: '
        );
        
        $pass = $dialog->askHiddenResponse(
            $output,
            'Payplug account pass: '
        );

        try {
            $output->writeLn('');
            $payplugAccountLoader = $this->getContainer()->get('payplug.account_loader');
            
            if (!$input->getOption('no-prod')) {
                $output->write('Load account parameters... ');
                $payplugAccountLoader->loadPayplugParameters($mail, $pass);
                $output->writeLn('     [OK]');
            }
            
            if ($input->getOption('test') || $input->getOption('no-prod')) {
                $output->write('Load TEST account parameters... ');
                $payplugAccountLoader->loadPayplugParameters($mail, $pass, true);
                $output->writeLn('[OK]');
            }
            
            $output->writeLn('');
            $output->writeLn('[OK] Parameters successfully loaded.');
        } catch (PayplugException $e) {
            $output->writeLn('[FAIL] '.$e->getMessage());
        }
    }
}
