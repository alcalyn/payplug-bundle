<?php

namespace Alcalyn\PayplugBundle\Command;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Alcalyn\PayplugBundle\Model\Payment;
use Alcalyn\PayplugBundle\Model\IPN;
use Alcalyn\PayplugBundle\Event\PayplugIPNEvent;
use Alcalyn\PayplugBundle\Event\PayplugMalformedIPNEvent;
use Alcalyn\PayplugBundle\Services\PayplugIPNService;

class SimulateIPNCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payplug:simulate:ipn')
            ->setDescription('Simulate an IPN to test your callback')
            ->addOption('test', 't', InputOption::VALUE_NONE, 'Simulate a sandbox IPN')
            ->addOption('malformed', null, InputOption::VALUE_NONE, 'Simulate a malformed IPN')
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'Amount of the payment in cents', 1600)
            ->addOption('order', null, InputOption::VALUE_REQUIRED, 'Order number', 0)
            ->addOption('customer', null, InputOption::VALUE_REQUIRED, 'Customer number', 0)
            ->addOption(
                'state',
                null,
                InputOption::VALUE_REQUIRED,
                'The new state of the transaction: "'.IPN::PAYMENT_PAID.'" or "'.IPN::PAYMENT_REFUNDED.'"',
                IPN::PAYMENT_PAID
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sandboxMode = $input->getOption('test');
        $malformed = $input->getOption('malformed');
        $title = 'Dispatch '.($malformed ? 'malformed ' : '').'IPN event'.($sandboxMode ? ' (sandbox mode)' : '').'...';
        
        $this->writeTitle($output, $title);
        
        if (!$malformed) {
            $data = $this->getIPNData($input);
            $ipnRequest = $this->getIPNService()->createIPNFromData($data);
            $event = new PayplugIPNEvent($ipnRequest);
            $this->getEventDispatcher()->dispatch(PayplugIPNEvent::PAYPLUG_IPN, $event);
        } else {
            $ipnRequest = $this->createMalformedIPNRequest($input);
            $event = new PayplugMalformedIPNEvent($ipnRequest);
            $this->getEventDispatcher()->dispatch(PayplugMalformedIPNEvent::PAYPLUG_IPN_MALFORMED, $event);
        }
        
        $output->writeLn(PHP_EOL.'[OK] Done.');
    }
    
    /**
     * Display text with blank line above and below
     * 
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $title
     */
    private function writeTitle(OutputInterface $output, $title)
    {
        $output->writeLn('');
        $output->writeLn($title);
        $output->writeLn('');
    }
    
    /**
     * @return EventDispatcher
     */
    private function getEventDispatcher()
    {
        return $this->getContainer()->get('event_dispatcher');
    }
    
    /**
     * @return PayplugIPNService
     */
    private function getIPNService()
    {
        return $this->getContainer()->get('payplug.ipn');
    }
    
    /**
     * @param InputInterface $input
     * 
     * @return array
     */
    private function getIPNData(InputInterface $input)
    {
        return array(
            'id_transaction' => '0',
            'first_name' => 'Tyler',
            'last_name' => 'Durden',
            'email' => 'tyler.durden@hollywood.com',
            'state' => $input->getOption('state'),
            'amount' => $input->getOption('amount'),
            'origin' => 'Alcalyn Payplug bundle, IPN simulation (payplug:simulate:ipn)',
            'customer' => $input->getOption('customer'),
            'custom_data' => null,
            'custom_datas' => null,
            'status' => 0,
            'order' => $input->getOption('order'),
            'is_test' => $input->getOption('test'),
        );
    }
    
    /**
     * @param InputInterface $input
     * 
     * @return Request
     */
    private function createMalformedIPNRequest(InputInterface $input)
    {
        $sandboxMode = $input->getOption('test');
        
        $callbackUrl = $this->getContainer()->get('router')->generate('payplug_ipn');
        $content = json_encode($this->getIPNData($input));
        $payplugSignature = base64_encode('Malformed signature');
        
        $request = Request::create($callbackUrl, 'POST', array(), array(), array(), array(), $content);
        $request->headers->set('payplug-signature', $payplugSignature);
        
        return $request;
    }
}
