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

namespace cinghie\paypal;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\i18n\PhpMessageSource;

class Paypal extends Module
{
	// Admin Rules
	public $paypalRoles = ['admin'];

	// Show Titles in the views
	public $showTitles = false;

	/**
	 * @inheritdoc
	 *
	 * @throws InvalidParamException
	 */
	public function init()
	{
		parent::init();
		$this->registerTranslations();
	}

	/**
	 * Translating module message
	 */
	public function registerTranslations()
	{
		if (!isset(Yii::$app->i18n->translations['paypal*'])) {
			Yii::$app->i18n->translations['paypal*'] = [
				'class' => PhpMessageSource::class,
				'basePath' => __DIR__ . '/messages',
			];
		}
	}
}
