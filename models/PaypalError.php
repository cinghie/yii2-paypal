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
use yii\base\Object;

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
		$this->link    = $this->data->information_link;
		$this->message = $this->data->message;
		$this->name    = $this->data->name;

		$this->setAlertError();
	}

	/**
	 * Set PayPalConnectionException Alert
	 *
	 * @return void
	 */
	public function setAlertError()
	{
		$alert = 'CODE '.$this->code.' - ';
		$alert .= 'PAYPAL ';
		$alert .= str_replace('_',' ',$this->name);
		$alert .= ' (DEBUG ID: '.$this->debugID.') ';
		$alert .= $this->message.'<br>';

		foreach($this->details as $detail) {
			$alert .= ' - '.$detail->issue.' => '.$detail->field.'<br>';
		}

		Yii::$app->session->setFlash('error', $alert);
	}
}
