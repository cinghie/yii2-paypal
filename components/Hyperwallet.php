<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-paypal
 * @license BSD-3-Clause
 * @package yii2-paypal
 * @version 0.2.4
 */

namespace cinghie\paypal\components;

use Yii;
use Hyperwallet\Hyperwallet as baseHyperwallet;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Hyperwallet
 *
 * @see https://github.com/hyperwallet/php-sdk
 */
class Hyperwallet extends Component
{
	/** @var string $server */
	public $server = 'https://sandbox.hyperwallet.com';

	/** @var string $username */
	public $username;

	/** @var string $password */
	public $password;

	/** @var string $token */
	public $token;

	/** @var baseHyperwallet $_hyperwallet */
	private $_hyperwallet;

	/**
	 * Hyperwallet constructor
	 *
	 * @param array $config
	 *
	 * @throws InvalidConfigException
	 */
	public function __construct(array $config = [])
	{
		if(!$config['username']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Hyperwallet username missing!'));
		}

		if(!$config['password']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Hyperwallet password missing!'));
		}

		if(!$config['token']) {
			throw new InvalidConfigException(Yii::t('paypal', 'Hyperwallet token missing!'));
		}

		$this->server = $config['server'] ?: 'https://sandbox.hyperwallet.com';
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->token = $config['token'] ?: null;

		parent::__construct($config);
	}

	/**
	 * Hyperwallet Init
	 *
	 * @see https://github.com/hyperwallet/php-sdk/blob/master/examples/three-step-tutorial.php
	 */
	public function init()
	{
		$this->_hyperwallet = new baseHyperwallet($this->username,$this->password,$this->token,$this->server);
	}
}