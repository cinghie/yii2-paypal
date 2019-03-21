# Yii2 PayPal
Yii2 PayPal Extension to manage PayPal Payments

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require cinghie/yii2-paypal "@dev"
```

or add this line to the require section of your `composer.json` file.

```
"cinghie/yii2-paypal": "@dev"
```

## PayPal

### Get credentials

1. Log into Dashboard and type your PayPal business account email and password.

2. In the REST API apps section, click Create App. The purpose of this app is to generate your credentials.

3. Type a name for your app and click Create App. The page shows your sandbox app information, which includes your credentials.  
Note: To show your live app information, toggle to Live.

4. Copy and save the client ID and secret for your sandbox app.

5. Review your app details and save your app.

### Create sandbox accounts

1. Log into Dashboard and type your PayPal business account email and password.  
Note: If you do not have a business account, click Sign Up.

2. Under Sandbox, click Accounts and click Create Account.

3. To create the buyer account, select the personal account type.  
Type these required and any optional fields and click Create Account:  
  
   - Email Address: A fake or valid email address.  
   If you use a valid address, you receive email notifications when you run test transactions    
   - Password: An easy-to-remember password, such as 12345678  
   - PayPal Balance: A high amount, such as 5000  

4. To create the merchant account, select the business account type, type account information, and click Create Account  

### Documentation

API: https://developer.paypal.com/docs/api/overview  
Documentation: https://developer.paypal.com/docs  
Support: https://developer.paypal.com/support/  

## Configuration

Add in your configuration file, in component section:

```
'paypal'=> [
    'class'        => 'cinghie\paypal\components\PayPal',
    'clientId'     => 'YOUR_CLIENT_ID',
    'clientSecret' => 'YOUR_CLIENT_SECRET',
    'isProduction' => false,
    'config'       => [
    	'mode' => 'sandbox', // development (sandbox) or production (live) mode
    ]
],
```

<ul>
  <li>clientid => your PayPal clientId</li>
  <li>clientSecret => your PayPal clientSecret</li>
  <li>isProduction => set yes if your site is on Production Mode, false otherwise</li>
  <li>mode => set 'sandbox' if your site is on Development Mode, or 'live' on Production Mode</li>
</ul>

You can set advanced settings in config array:

```
     'config'       => [
         'http.ConnectionTimeOut' => 30,
         'http.Retry' => 1,
         'mode' => 'sandbox', // development (sandbox) or production (live) mode
         'log.LogEnabled' => YII_DEBUG ? 1 : 0,
         'log.FileName' => Yii::getAlias('@runtime/logs/paypal.log'),
         'log.LogLevel' => 'ERROR',
    ],
```
