<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-paypal
 * @license BSD-3-Clause
 * @package yii2-paypal
 * @version 0.2.2
 */

namespace cinghie\paypal\components;

use Yii;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Paypal
 */
class Paypal extends Component
{
	/** @var string $clientId  */
	public $clientId = '';

	/** @var string $clientSecret  */
	public $clientSecret = '';

	/** @var array $config */
	public $config = [];

	/** @var boolean $isProduction */
	public $isProduction;

	/** @var ApiContext $_apiContext */
	private $_apiContext;

	/**
	 * @inheritdoc
	 *
	 * @param array $config
	 *
	 * @throws InvalidConfigException
	 */
	public function __construct(array $config = [])
	{
		if(!$config['clientId']) {
			throw new InvalidConfigException(Yii::t('paypal', 'PayPal clientId missing!'));
		}

		if(!$config['clientSecret']) {
			throw new InvalidConfigException(Yii::t('paypal', 'PayPal clientSecret missing!'));
		}

		$this->clientId = $config['clientId'];
		$this->clientSecret = $config['clientSecret'];
		$this->config = isset($config['config']) ? $config['config'] : [];
		$this->isProduction = isset($config['isProduction']) ? $config['isProduction'] : $config['isProduction'] = false;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if( !isset($this->config['mode']) ) {
			$this->config['mode'] = 'sandbox';
		}

		if( !isset($this->config['http.ConnectionTimeOut']) ) {
			$this->config['http.ConnectionTimeOut'] = 30;
		}

		if( !isset($this->config['http.Retry']) ) {
			$this->config['http.Retry'] = 1;
		}

		if( !isset($this->config['log.LogEnabled']) ) {
			$this->config['log.LogEnabled'] = YII_DEBUG ? 1 : 0;
		}

		if( !isset($this->config['log.FileName']) ) {
			$this->config['log.FileName'] = Yii::getAlias('@runtime/logs/paypal.log');
		}

		if( !isset($this->config['log.LogLevel']) ) {
			$this->config['log.LogLevel'] = 'ERROR';
		}

		$this->_apiContext = new ApiContext(
			new OAuthTokenCredential(
				$this->clientId,
				$this->clientSecret
			)
		);

		$this->_apiContext->setConfig($this->config);

		if( $this->config['log.LogEnabled'] && isset($this->config['log.FileName'], $this->config['log.LogEnabled']) )
		{
			$logFileName = $this->config['log.FileName'];

			if ( !file_exists($logFileName) && !touch($logFileName) )
			{
				throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
			}
		}

		return $this->_apiContext;
	}

	/**
	 * Payment Demo
	 *
	 * @return Payment
	 */
	public function payDemo()
	{
		$addr = new Address();
		$addr->setLine1('52 N Main ST');
		$addr->setCity('Johnstown');
		$addr->setCountryCode('US');
		$addr->setPostalCode('43210');
		$addr->setState('OH');

		$card = new CreditCard();
		$card->setNumber('4417119669820331');
		$card->setType('visa');
		$card->setExpireMonth('11');
		$card->setExpireYear('2030');
		$card->setCvv2('874');
		$card->setFirstName('Joe');
		$card->setLastName('Shopper');
		$card->setBillingAddress($addr);

		$fi = new FundingInstrument();
		$fi->setCreditCard($card);

		$payer = new Payer();
		$payer->setPaymentMethod('credit_card');
		$payer->setFundingInstruments(array($fi));

		$amountDetails = new Details();
		$amountDetails->setSubtotal('1.00');
		$amountDetails->setTax('0.22');
		$amountDetails->setShipping('0.10');

		$amount = new Amount();
		$amount->setCurrency('EUR');
		$amount->setTotal('1.32');
		$amount->setDetails($amountDetails);

		$transaction = new Transaction();
		$transaction->setAmount($amount);
		$transaction->setDescription('This is the payment transaction description.');

		$payment = new Payment();
		$payment->setIntent('sale');
		$payment->setPayer($payer);
		$payment->setTransactions(array($transaction));

		try {
			$paymentDemo = $payment->create($this->_apiContext);
		} catch (PayPalConnectionException $e) {
			echo var_dump($e->getCode());
			echo var_dump($e->getData());
			die(var_dump($e));
		} catch (Exception $ex) {
			die(var_dump($ex));
		}

		return $paymentDemo;
	}
}
