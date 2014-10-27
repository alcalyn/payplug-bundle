<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Services\PayplugPaymentService;

class GenerateUrlCommand extends Command
{
    /**
     * @var PayplugPaymentService
     */
    private $paymentService;
    
    /**
     * @param PayplugPaymentService $paymentService
     */
    public function __construct(PayplugPaymentService $paymentService)
    {
        parent::__construct();
        
        $this->paymentService = $paymentService;
    }
    
    protected function configure()
    {
        $this
            ->setName('payplug:generate:url')
            ->setDescription('Generate a payment url')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'Generate a test payment')
            ->addOption('code', null, InputOption::VALUE_NONE, 'Display code to generate this payment')
            ->addOption('interactive', 'i', InputOption::VALUE_NONE, 'Prompt every missed payment parameters')
            ->addArgument('amount', InputArgument::OPTIONAL, 'Amount of the payment in cents', 1600)
            ->addArgument('currency', InputArgument::OPTIONAL, 'Currency of the payment', Payment::EUROS)
            ->addOption('firstname', 'f', InputOption::VALUE_REQUIRED, 'First name of the customer')
            ->addOption('lastname', 'l', InputOption::VALUE_REQUIRED, 'Last name of the customer')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email of the customer')
            ->addOption('ipn-url', null, InputOption::VALUE_REQUIRED, 'Ipn Url')
            ->addOption('return-url', null, InputOption::VALUE_REQUIRED, 'Return url')
            ->addOption('cancel-url', null, InputOption::VALUE_REQUIRED, 'Cancel url')
            ->addOption('customer', null, InputOption::VALUE_REQUIRED, 'Customer number')
            ->addOption('order', null, InputOption::VALUE_REQUIRED, 'Order number')
            ->addOption('custom-data', null, InputOption::VALUE_REQUIRED, 'Custom data')
            ->addOption('origin', null, InputOption::VALUE_REQUIRED, 'Origin of the payment')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paymentService = $this->paymentService;
        $payment = $this->getPaymentFromInput($input);
        $test = $input->getOption('test');
        
        if ($input->getOption('interactive')) {
            $this->fillWithInteractiveMode($payment, $input, $output);
            $output->writeLn('');
        }
        
        if ($input->getOption('code')) {
            $output->writeLn($this->getCodeToGeneratePayment($payment, $test));
        } else {
            $output->writeLn($paymentService->generateUrl($payment, $test));
        }
    }
    
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * 
     * @return Payment
     */
    private function getPaymentFromInput(InputInterface $input)
    {
        $payment = new Payment();
        
        return $payment
            ->setAmount($input->getArgument('amount'))
            ->setCurrency($input->getArgument('currency'))
            ->setFirstName($input->getOption('firstname'))
            ->setLastName($input->getOption('lastname'))
            ->setEmail($input->getOption('email'))
            ->setIpnUrl($input->getOption('ipn-url'))
            ->setReturnUrl($input->getOption('return-url'))
            ->setCancelUrl($input->getOption('cancel-url'))
            ->setCustomer($input->getOption('customer'))
            ->setOrder($input->getOption('order'))
            ->setCustomData($input->getOption('custom-data'))
            ->setOrigin($input->getOption('origin'))
        ;
    }
    
    /**
     * @param \Alcalyn\PayplugBundle\Model\Payment $payment
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    private function fillWithInteractiveMode(Payment $payment, InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        
        $output->writeLn('');
        
        $this->promptAmount($payment, $output, $dialog);
        $this->promptCurrency($payment, $output, $dialog);
        
        foreach (array('firstname', 'lastname', 'email') as $field) {
            $this->promptField($field, $payment, $input, $output, $dialog);
        }
        
        if (!$input->getOption('ipn-url')) {
            if ($input->getOption('test')) {
                $defaultIpn = $this->testPaymentService->getIpnUrl();
            } else {
                $defaultIpn = $this->paymentService->getIpnUrl();
            }
            
            $v = $dialog->ask($output, $this->promptFormat('IPN url ['.$defaultIpn.']'), $defaultIpn);
            $payment->setIpnUrl($v);
        }
        
        foreach (array('return-url', 'cancel-url', 'customer', 'order', 'custom-data', 'origin') as $field) {
            $this->promptField($field, $payment, $input, $output, $dialog);
        }
    }
    
    /**
     * @param \Alcalyn\PayplugBundle\Model\Payment $payment
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param mixed $dialog
     */
    private function promptAmount(Payment $payment, OutputInterface $output, $dialog)
    {
        $amount = $dialog->askAndValidate(
            $output,
            $this->promptFormat('Amount in cents'),
            function ($answer) {
                if (!preg_match('/[0-9]+/', $answer)) {
                    throw new \RunTimeException('Amount must be an integer');
                }

                return $answer;
            },
            false,
            1600
        );

        $payment->setAmount($amount);
    }
    
    /**
     * @param \Alcalyn\PayplugBundle\Model\Payment $payment
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param mixed $dialog
     */
    private function promptCurrency(Payment $payment, OutputInterface $output, $dialog)
    {
        $currency = $dialog->ask($output, $this->promptFormat('Currency [EUR]'), 'EUR');
        $payment->setCurrency($currency);
    }
    
    /**
     * @param string $field example: "custom-data"
     * @param \Alcalyn\PayplugBundle\Model\Payment $payment
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param mixed $dialog
     */
    private function promptField($field, Payment $payment, InputInterface $input, OutputInterface $output, $dialog)
    {
        if (!$input->getOption($field)) {
            $v = $dialog->ask($output, $this->promptFormat(ucfirst(str_replace('-', ' ', $field))));
            $payment->{'set'.str_replace('-', '', $field)}($v);
        }
    }
    
    /**
     * @param string $s
     * @return string
     */
    private function promptFormat($s)
    {
        return str_pad($s.': ', 20, ' ', STR_PAD_LEFT);
    }
    
    /**
     * @param \Alcalyn\PayplugBundle\Model\Payment $payment
     * @param boolean $test whether it is a test payment
     * 
     * @return string
     */
    private function getCodeToGeneratePayment(Payment $payment, $test)
    {
        $code = <<<'CODE'
    $payment = new Payment();

    $payment

CODE;
        
        $code .= '        ->setAmount('.$payment->getAmount().')'.PHP_EOL;
        
        $attributes = array(
            'Currency',     'FirstName',    'LastName', 'Email',    'ReturnUrl',
            'CancelUrl',    'Customer',     'Customer', 'Order',    'CustomData',   'Origin',
        );
        
        foreach ($attributes as $attribute) {
            if (null !== $payment->{'get'.$attribute}()) {
                $code .= '        ->set'.$attribute.'(\''.$payment->{'get'.$attribute}().'\')'.PHP_EOL;
            }
        }
        
        $code .= '    ;'.PHP_EOL.PHP_EOL;
        $code .= '    // Get Payplug '.($test ? 'test ' : '').'payment service'.PHP_EOL;
        $code .= '    $payplugPayment = $this->get(\'payplug.payment'.($test ? '.test' : '').'\');'.PHP_EOL.PHP_EOL;
        $code .= '    // Generate payment url'.PHP_EOL;
        $code .= '    $paymentUrl = $payplugPayment->generateUrl($payment);'.PHP_EOL;
        
        return $code;
    }
}
