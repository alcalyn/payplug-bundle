<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;

class AccountLoaderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payplug:account:update')
            ->setDescription('Update your Payplug account parameters and set them in your parameters.yml')
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
        
        $payplugAccountLoader = $this->getContainer()->get('payplug.account_loader');

        try {
            $output->writeLn('');
            $output->writeLn('Requesting Payplug...');
            $output->writeLn('');
            
            $payplugAccountLoader->loadPayplugParameters($mail, $pass);
            
            $output->writeLn('Parameters successfully loaded.');
        } catch (PayplugException $e) {
            $output->writeLn($e->getMessage());
        }
    }
}
