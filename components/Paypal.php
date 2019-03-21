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
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class Paypal
 *
 * @package cinghie\paypal\components
 */
class Paypal extends Component
{
	/**
	 * PayPal Mode
	 */
	const MODE_LIVE = 'live';
	const MODE_SANDBOX = 'sandbox';

	/**
     * PayPal Logging levels
     */
	const LOG_LEVEL_FINE  = 'FINE';
	const LOG_LEVEL_INFO  = 'INFO';
	const LOG_LEVEL_WARN  = 'WARN';
	const LOG_LEVEL_ERROR = 'ERROR';

	/**
	 * Class Property
	 */
	public $clientId = '';
	public $clientSecret = '';
	public $isProduction = false;
	public $currency = 'USD';
	public $config = [];
	private $_apiContext;

	/**
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

		parent::__construct($config);
	}

	/**
	 * @see
	 */
	public function init()
	{
		$this->_apiContext = new ApiContext(
			new OAuthTokenCredential(
				$this->clientId,
				$this->clientSecret
			)
		);

		var_dump($this->_apiContext); exit();
	}
}
