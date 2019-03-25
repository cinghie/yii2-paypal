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
use yii\helpers\Json;

/**
 * Class PaypalError
 */
class PaypalError
{
	/** @var JSON */
	public $data;

	/** @var string */
	public $error;

	/**
	 * PaypalError constructor
	 *
	 * @param array | Json $data
	 */
	public function __construct($data = [])
	{
		if($data) {
			$this->data = json_decode($data);
		}

		$this->error = 'PAYPAL ';
		$this->error .= str_replace('_',' ',$this->data->name).': '.$this->data->message.' Degub ID: '.$this->data->debug_id.'<br>';

		foreach($this->data->details as $detail) {
			$this->error .= ' - '.$detail->issue.' => '.$detail->field.'<br>';
		}

		Yii::$app->session->setFlash('error', $this->error);
	}
}
