<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Alcalyn\PayplugBundle\Exceptions\PayplugException;
use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Services\PayplugPaymentService;

class GenerateUrlCommand extends Command
{
    /**
     * @var PayplugPaymentService
     */
    private $paymentService;
    
    /**
     * @var PayplugPaymentService
     */
    private $testPaymentService;
    
    /**
     * @param PayplugAccountLoader $payplugAccountLoader
     */
    public function __construct(PayplugPaymentService $paymentService, PayplugPaymentService $testPaymentService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
        $this->testPaymentService = $testPaymentService;
    }
    
    protected function configure()
    {
        $this
            ->setName('payplug:generate:url')
            ->setDescription('Generate a payment url')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'Generate a test payment')
            ->addArgument('amount', InputArgument::OPTIONAL, 'Amount of the payment in cents', 1600)
            ->addArgument('currency', InputArgument::OPTIONAL, 'Currency of the payment', Payment::EUROS)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn('');
        
        if ($input->getOption('test')) {
            $paymentService = $this->testPaymentService;
        } else {
            $paymentService = $this->paymentService;;
        }
        
        $payment = new Payment($input->getArgument('amount'), $input->getArgument('currency'));
        
        $output->writeLn($paymentService->generateUrl($payment));
    }
}
