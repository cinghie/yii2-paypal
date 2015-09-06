# Yii2 Paypal
Yii2 Paypal Extension to manage Paypal Payments

Installation
-----------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist cinghie/yii2-paypal "*"
```

or add this line to the require section of your `composer.json` file.

```
"cinghie/yii2-paypal": "*"
```

Configuration
-----------------

Add in your configuration file, in component section:

```
'paypal'=> [
    'class'        => 'cinghie\paypal\components\Paypal',
    'clientId'     => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'isProduction' => false,
     // This is config file for the PayPal system
     'config'       => [
         'http.ConnectionTimeOut' => 30,
         'http.Retry' => 1,
         'mode' => 'sandbox', // development (sandbox) or production (live) mode
         'log.LogEnabled' => YII_DEBUG ? 1 : 0,
         'log.FileName' => 'F:/xampp/htdocs/yii2/runtime/logs/paypal.log', // '@runtime/logs/paypal.log'
         'log.LogLevel' => 'ERROR',
    ]
],
```

Changelog
-----------------

<ul>
  <li>Version 0.1.1 - Editing Code from https://github.com/marciocamello/yii2-paypal</li>
  <li>Version 0.1.0 - Adding Code from https://github.com/marciocamello/yii2-paypal</li>
  <li>Version 0.0.1 - Initial Release</li>
</ul>