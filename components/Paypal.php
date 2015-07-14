<?php

/**
* @copyright Copyright &copy; Gogodigital Srls
* @company Gogodigital Srls - Wide ICT Solutions 
* @website http://www.gogodigital.it
* @github https://github.com/cinghie/yii2-paypal
* @license GNU GENERAL PUBLIC LICENSE VERSION 3
* @package yii2-paypal
* @version 0.1.1
*/

namespace cinghie\paypal\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal extends Component
{
	
	// API SETTINGS
	public $clientId;
    public $clientSecret;
    public $currency = 'EUR';
    public $config = [];
	
	// API CONTEXT
    private $_apiContext = null;
	
	// API Context
    // Use an ApiContext object to authenticate API calls. 
	// The clientId and clientSecret for the OAuthTokenCredential class 
    // can be retrieved from developer.paypal.com
	function init() 
	{ 
		// Set _apiContext
		$this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );
		
		// Config _apiContext
		$this->_apiContext->setConfig(
			ArrayHelper::merge([
                'mode'                   => 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'http.Retry'             => 1,
                'log.LogEnabled'         => YII_DEBUG ? 1 : 0,
                'log.FileName'           => Yii::getAlias('@runtime/logs/paypal.log'),
                'log.LogLevel'           => 'ERROR',
                'validation.level'       => 'log',
                'cache.enabled'          => 'true'
            ],$this->config)
		);
		
		// Write Log
		if ( isset($this->config['log.FileName']) && isset($this->config['log.LogEnabled']) && ((bool)$this->config['log.LogEnabled'] == true) ) 
		{
            $logFileName = \Yii::getAlias($this->config['log.FileName']);
            if ($logFileName) 
			{
                if (!file_exists($logFileName)) 
				{
                    if (!touch($logFileName)) {
                        throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
                    }
                }
            }
            $this->config['log.FileName'] = $logFileName;
        }
		
		return $this->_apiContext;
	}
	
	// Test a Paypal Demo
	public function getDemo()
    {
 		$addr       = $this->setAddress('52 N Main ST','Johnstown','US','43210','OH');
		$amount     = $this->setAmount('USD','0.99');
		$card       = $this->setCard('4417119669820331','visa','11','2018','874','Giando','Yii2 Shopper',$addr); 
        
		$fi = new FundingInstrument();
        $fi->setCreditCard($card);
        
		$payer = new Payer();
        $payer->setPaymentMethod('credit_card');
        $payer->setFundingInstruments(array($fi));
        
		$transaction = $this->setTransaction($amount,'This is the payment transaction description.');
		$payment     = $this->setPayment('sale',$payer,$transaction);
        
		return $payment->create($this->_apiContext);
    }
	
	// Set Address Item
	public function setAddress($line1,$city,$countryCode,$postalCode,$state)
	{
		$address = new Address();
        $address->setLine1($line1);
        $address->setCity($city);
        $address->setCountryCode($countryCode);
        $address->setPostalCode($postalCode);
        $address->setState($state);
		
		return $address;
	}
	
	// Set Amount
	public function setAmount($currency,$total)
	{
		$amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);
		
		return $amount;
	}
	
	// Set Card
	public function setCard($number,$type,$expireMonth,$expireYear,$cvv2,$firstname,$lastname,$addr)
	{
		$card = new CreditCard();
        $card->setNumber($number);
        $card->setType($type);
        $card->setExpireMonth($expireMonth);
        $card->setExpireYear($expireYear);
        $card->setCvv2($cvv2);
        $card->setFirstName($firstname);
        $card->setLastName($lastname);
        $card->setBillingAddress($addr);
		
		return $card;
	}
	
	// Set Transaction
	public function setTransaction($amount,$descr)
	{
		$transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($descr);
		
		return $transaction;
	}
	
	// Set Payment
	public function setPayment($intent,$payer,$transaction)
	{
		$payment = new Payment();
        $payment->setIntent($intent);
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));
		
		return $payment;
	}
}