<?php

/**
* @copyright Copyright &copy; Gogodigital Srls
* @company Gogodigital Srls - Wide ICT Solutions 
* @website http://www.gogodigital.it
* @github https://github.com/cinghie/yii2-paypal
* @license GNU GENERAL PUBLIC LICENSE VERSION 3
* @package yii2-paypal
* @version 0.2.1
*/

namespace cinghie\paypal\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

use cinghie\paypal\components\Helper;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal extends Component
{
	
	// API SETTINGS
	public $clientId;
    public $clientSecret;
    public $currency = 'USD';
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
            $logFileName = Yii::getAlias($this->config['log.FileName']);
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

    public function getDemoPaymentUsingPayPal($baseUrl)
    {
        $payer        = Helper::setPaypalPayer("paypal");
        $item1        = Helper::setPaypalItem("Ground Coffee 40 oz","USD",1,"123123",7.5);
        $item2        = Helper::setPaypalItem("Granola bars","USD",5,"321321",2);
        $itemList     = Helper::setPaypalItemList(array($item1,$item2));
        $details      = Helper::setPaypalDetails(1.2,1.3,17.5);
        $amount       = Helper::setPaypalAmount("USD",20.0,$details);
        $transaction  = Helper::setPaypalTransaction($amount,"Payment description",uniqid(),$itemList);
        $redirectUrls = Helper::setPaypalRedirectUrls($baseUrl."ExecutePayment.php?success=true",$baseUrl."ExecutePayment.php?success=false");
        $payment      = Helper::setPaypalPayment("sale",$payer,$redirectUrls,$transaction);

        return $payment->create($this->_apiContext);

    }

    public function getLink(array $links, $type) {
        foreach($links as $link) {
            if($link->getRel() == $type) {
                return $link->getHref();
            }
        }
        return "";
    }

}