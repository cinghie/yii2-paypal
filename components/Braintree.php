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

namespace cinghie\paypal\components;

use Yii;
use Braintree_Configuration;
use Braintree_Gateway;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Braintree
 *
 * @see https://developers.braintreepayments.com/start/hello-server/php
 */
class Braintree extends Component
{
	/** @var string $environment */
	public $environment = 'sandbox';

	/** @var string $merchantId */
	public $merchantId = '';

	/** @var string $privateKey */
	public $privateKey = '';

	/** @var string $publicKey */
	public $publicKey = '';

	/** @var Braintree_Gateway $gateway */
	public $gateway;

	/**
	 * Braintree constructor
	 *
	 * @param array $config
	 *
	 * @throws InvalidConfigException
	 */
	public function __construct(array $config = [])
	{
		if(!$config['merchantId']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Braintree merchantId missing!'));
		}

		if(!$config['privateKey']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Braintree privateKey missing!'));
		}

		if(!$config['publicKey']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Braintree publicKey missing!'));
		}


		$this->environment = $config['environment'] ?: 'sandbox';
		$this->merchantId = $config['merchantId'];
		$this->privateKey = $config['privateKey'];
		$this->publicKey = $config['publicKey'];

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$config = new Braintree_Configuration([
			'environment' => $this->environment,
			'merchantId' => $this->merchantId ,
			'publicKey' => $this->publicKey,
			'privateKey' => $this->privateKey
		]);

		$this->gateway = new Braintree_Gateway($config);
	}

	/**
	 * Get Client Token
	 *
	 * @param string $customerId
	 *
	 * @return string
	 */
	public function getClientToken($customerId = '')
	{
		if($customerId) {
			return $this->gateway->clientToken()->generate([
				'customerId' => $customerId
			]);
		}

		return $this->gateway->clientToken()->generate();
 	}
}
