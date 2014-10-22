<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;
use Alcalyn\PayplugBundle\Services\PayplugAccountLoader;

class AccountLoaderCommand extends Command
{
    /**
     * @var PayplugAccountLoader
     */
    private $payplugAccountLoader;
    
    /**
     * @param PayplugAccountLoader $payplugAccountLoader
     */
    public function __construct(PayplugAccountLoader $payplugAccountLoader)
    {
        parent::__construct();
        
        $this->payplugAccountLoader = $payplugAccountLoader;
    }
    
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
            
            if (!$input->getOption('no-prod')) {
                $output->write('Load account parameters... ');
                $this->payplugAccountLoader->loadPayplugParameters($mail, $pass);
                $output->writeLn('     [OK]');
            }
            
            if ($input->getOption('test') || $input->getOption('no-prod')) {
                $output->write('Load TEST account parameters... ');
                $this->payplugAccountLoader->loadPayplugParameters($mail, $pass, true);
                $output->writeLn('[OK]');
            }
            
            $output->writeLn('');
            $output->writeLn('[OK] Parameters successfully loaded.');
        } catch (PayplugException $e) {
            $output->writeLn('[FAIL] '.$e->getMessage());
        }
    }
}
