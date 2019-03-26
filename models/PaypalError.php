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

namespace cinghie\paypal\models;

use Yii;
use PayPal\Exception\PayPalConnectionException;

/**
 * Class PaypalError
 */
class PaypalError
{
	/** @var string */
	public $code;

	/** @var string */
	public $debugID;

	/** @var Object */
	public $data;

	/** @var array */
	public $details;

	/** @var PayPalConnectionException */
	public $error;

	/** @var string */
	public $link;

	/** @var string */
	public $message;

	/** @var string */
	public $name;

	/**
	 * PaypalError constructor
	 *
	 * @param PayPalConnectionException $e
	 */
	public function __construct($e)
	{
		$this->error   = $e;
		$this->code    = $this->error->getCode();
		$this->data    = json_decode($this->error->getData());
		$this->debugID = $this->data->debug_id;
		$this->details = $this->data->details;
		$this->link    = str_replace('errors','error',$this->data->information_link).'-'.$this->data->name;
		$this->message = $this->data->message;
		$this->name    = $this->data->name;

		if(Yii::$app->paypal->checkIsSandbox()) {
			$this->setSandboxAlertError();
		} else {
			$this->setAlertError();
		}
	}

	/**
	 * Set Sandbox PayPalConnectionException Alert
	 *
	 * @return void
	 */
	public function setAlertError()
	{
		$alert = Yii::t('traits','Error').' '.$this->code;

		Yii::$app->session->setFlash('error', $alert);
	}

	/**
	 * Set Sandbox PayPalConnectionException Alert
	 *
	 * @return void
	 */
	public function setSandboxAlertError()
	{
		$alert = 'CODE '.$this->code.' - ';
		$alert .= 'PAYPAL '.$this->name;
		$alert .= ' (DEBUG ID: '.$this->debugID.') ';
		$alert .= $this->message.'<br>';

		foreach($this->details as $detail) {
			$alert .= ' - '.$detail->issue.' => '.$detail->field.'<br>';
		}

		$alert .= '<a href="'.$this->link.'" target="_blanck">'.$this->link.'</a><br>';

		Yii::$app->session->setFlash('error', $alert);
	}
}
