<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-paypal
 * @license BSD-3-Clause
 * @package yii2-paypal
 * @version 0.2.3
 */

namespace cinghie\paypal\models;

use Yii;
use cinghie\traits\FakerTraits;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use yii\base\ErrorException;

/**
 * Class Demo
 */
class Demo
{
	use FakerTraits;

	/** @var ApiContext $_apiContext */
	private $_apiContext;

	/**
	 * Demo constructor.
	 *
	 * @throws ErrorException
	 */
	public function __construct()
	{
		$this->_apiContext = Yii::$app->paypal->getApiContext();

		if(!Yii::$app->paypal->checkIsSandbox()) {
			throw new ErrorException(Yii::t('paypal','Can\'t use Demo if Paypal is not in Sandbox mode'));
		}
	}

	/**
	 * Credit Card Payment Demo
	 *
	 * @return Payment
	 */
	public function payByCreditCardDemo()
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
		$card->setExpireYear('2020');
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
		$amountDetails->setShipping('0.15');

		$amount = new Amount();
		$amount->setCurrency('USD');
		$amount->setTotal('1.37');
		$amount->setDetails($amountDetails);

		$transaction = new Transaction();
		$transaction->setAmount($amount);
		$transaction->setDescription('This is the payment transaction description.');

		$payment = new Payment();
		$payment->setIntent('sale');
		$payment->setPayer($payer);
		$payment->setTransactions(array($transaction));

		$paymentDemo = new Payment();

		try {
			$paymentDemo = $payment->create($this->_apiContext);
		} catch (\PayPal\Exception\PayPalConnectionException $e) {
			new Error($e);
		} catch (Exception $ex) {
			echo 'Ciao';
			die(var_dump($ex));
		}

		Payments::createPayments($paymentDemo);

		return $paymentDemo;
	}

	/**
	 * Paypal Payment Demo
	 *
	 * @return Payment
	 */
	public function payByPaypalDemo()
	{
		$addr = new Address();
		$addr->setLine1('52 N Main ST');
		$addr->setCity('Johnstown');
		$addr->setCountryCode('US');
		$addr->setPostalCode('43210');
		$addr->setState('OH');

		$payer = new Payer();
		$payer->setPaymentMethod('paypal');

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl('https://www,google.com');
		$redirectUrls->setCancelUrl('https://www,google.com');

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
		$payment->setRedirectUrls($redirectUrls);
		$payment->setTransactions(array($transaction));

		$paymentDemo = new Payment();

		try {
			$paymentDemo = $payment->create($this->_apiContext);
		} catch (\PayPal\Exception\PayPalConnectionException $e) {
			new Error($e);
		} catch (Exception $ex) {
			die(var_dump($ex));
		}

		$approvalUrl = $paymentDemo->getApprovalLink();
		echo '<a href="'.$approvalUrl.'">Paga con PayPal</a>';

		return $paymentDemo;
	}
}
