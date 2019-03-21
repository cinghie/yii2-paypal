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
		if(!isset($this->config['mode'])) {
			$this->config['mode'] = 'sandbox';
		}

		if(!isset($this->config['http.ConnectionTimeOut'])) {
			$this->config['http.ConnectionTimeOut'] = 30;
		}

		if(!isset($this->config['http.Retry'])) {
			$this->config['http.Retry'] = 1;
		}

		if(!isset($this->config['log.LogEnabled'])) {
			$this->config['log.LogEnabled'] = YII_DEBUG ? 1 : 0;
		}

		if(!isset($this->config['log.FileName'])) {
			$this->config['log.FileName'] = '@runtime/logs/paypal.log';
		}

		if(!isset($this->config['log.LogLevel'])) {
			$this->config['log.LogLevel'] = 'ERROR';
		}

		$this->_apiContext = new ApiContext(
			new OAuthTokenCredential(
				$this->clientId,
				$this->clientSecret
			)
		);

		if($this->config['log.LogEnabled'])
		{
			$logFileName = \Yii::getAlias($this->config['log.FileName']);

			if (!file_exists($logFileName) && !touch($logFileName))
			{
				throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
			}
		}

		return $this->_apiContext;
	}
}
