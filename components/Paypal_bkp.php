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
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\CreditCard;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\Currency;
use PayPal\Api\Payment;
use PayPal\Api\PayoutItem;
use PayPal\Api\RedirectUrls;
use Braintree;
use PayPal\Api\Payout;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;
use yii\base\Component;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use yii\base\InvalidConfigException;
/**
 * Class Paypal
 */
class Paypal extends Component
{
    /** @var string $clientId */
    public $clientId = 'AfgrUXTSAHVQEiZxjDE2kk-SL_3OyHKAEwUqgVPUzXOCSZ240FU9myNQDLGSG9NeLHDmOLrVGNizMTAi';
    /** @var string $clientSecret */
    public $clientSecret = 'ECJhV_P-d1Ak4nAqxnZIX4d6jBCaIGW9xZ5NCDPAirzwOd2hQtYHvj2wkGR1nxtjiq-sdOKYP0OwK9nT';
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
        if (!$config['clientId']) {
            throw new InvalidConfigException(Yii::t('paypal', 'PayPal clientId missing!'));
        }
        if (!$config['clientSecret']) {
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
        if (!isset($this->config['mode'])) {
            $this->config['mode'] = 'sandbox';
        }
        if (!isset($this->config['http.ConnectionTimeOut'])) {
            $this->config['http.ConnectionTimeOut'] = 30;
        }
        if (!isset($this->config['http.Retry'])) {
            $this->config['http.Retry'] = 1;
        }
        if (!isset($this->config['log.LogEnabled'])) {
            $this->config['log.LogEnabled'] = YII_DEBUG ? 1 : 0;
        }
        if (!isset($this->config['log.FileName'])) {
            $this->config['log.FileName'] = Yii::getAlias('@runtime/logs/paypal.log');
        }
        if (!isset($this->config['log.LogLevel'])) {
            $this->config['log.LogLevel'] = 'ERROR';
        }
        $this->_apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );
        $this->_apiContext->setConfig($this->config);
        if ($this->config['log.LogEnabled'] && isset($this->config['log.FileName'], $this->config['log.LogEnabled'])) {
            $logFileName = $this->config['log.FileName'];
            if (!file_exists($logFileName) && !touch($logFileName)) {
                throw new ErrorException('Can\'t create paypal.log file at: ' . $logFileName);
            }
        }
        return $this->_apiContext;
    }

    /**
     * Payment Demo
     *
     * @return Payment
     */
    public function payDemo()
    {
        $addr = new Address();
        $addr->setLine1('52 N Main ST');
        $addr->setCity('Johnstown');
        $addr->setCountryCode('US');
        $addr->setPostalCode('43210');
        $addr->setState('OH');
        /*$card = new CreditCard();
        $card->setNumber('4417119669820331');
        $card->setType('visa');
        $card->setExpireMonth('11');
        $card->setExpireYear('2030');
        $card->setCvv2('874');
        $card->setFirstName('Joe');
        $card->setLastName('Shopper');
        $card->setBillingAddress($addr);
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);*/

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        //$payer->setFundingInstruments(array($fi));

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl('https://wisho.gogodigital.it/process.php')
            ->setCancelUrl('https://wisho.gogodigital.it/cancel.php');

        $amountDetails = new Details();
        $amountDetails->setSubtotal('1.00');
        $amountDetails->setTax('0.22');
        $amountDetails->setShipping('0.10');
        $amount = new Amount();
        $amount->setCurrency('EUR');
        $amount->setTotal('1.32');
        $amount->setDetails($amountDetails);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('This is the payment transaction description.');

        $payment = new Payment();
        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));
        //$request = clone $payment;
        try {
            $paymentDemo = $payment->create($this->_apiContext);
            $approvalUrl = $paymentDemo->getApprovalLink();
            echo '<a href="' . $approvalUrl . '">Paga con PayPal</a>';
            //echo "<h1>ciao</h1>";
            //	$approvalUrl = $payment->getApprovalLink();
            //ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

        } catch (PayPalConnectionException $e) {

            //echo var_dump($e->getCode());
            //echo var_dump($e->getData());
            //die(var_dump($e));
        } catch (Exception $ex) {

            //die(var_dump($ex));
        }
        return $paymentDemo;
    }

    public function Payout($email,$amount,$item)
    {
         $payouts = new Payout();
		 $senderBatchHeader = new PayoutSenderBatchHeader();
           $senderBatchHeader->setSenderBatchId(uniqid())
               ->setEmailSubject("hai ricevuto una offerta per ".$item);
           $senderItem = new PayoutItem();
           $senderItem->setRecipientType('Email')
               ->setNote('grazie per '.$item)
               ->setReceiver($email)
               ->setSenderItemId(uniqid())
               ->setAmount(new Currency('{
                           "value":"'.$amount.'",
                           "currency":"EUR"
                       }'));
           $payouts->setSenderBatchHeader($senderBatchHeader)
               ->addItem($senderItem);
           $request = clone $payouts;
           try {
               $output = $payouts->create(null,$this->_apiContext);
               // $output = $payouts->createSynchronous($this->_apiContext);
           } catch (Exception $ex) {
			 //  print_r($ex);
               //ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
               exit(1);
           }
		 /*  echo '<pre>';
		   print_r($output);
		    echo '</pre>';*/
           //ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
           return $output;
		   
		   /*
        $data2 =    ' {
            "sender_batch_header":{
            "sender_batch_id":"2014021110112112801",
            "email_subject":"YouhaveaPayout!",
            "recipient_type":"EMAIL"
            },
            "items": [
                {
                "recipient_type":"EMAIL",
                "amount": {
                "value":"1",
                "currency":"EUR"
                },
                "note":"Thanksforyourpatronage!",
                "sender_item_id":"201403140001",
                "receiver":"acquirente@gogodigital.it"
                }
            ]
          }';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payouts/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data2,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer A21AAEXVvXMuatCp1FX7T_6Qky2Z6CfLDbhBFoCuFX1Rqpy3KzX900SLPNYkbBjUR7RUpOCYMrIWMNMvvpIKgFyBS1xloYUFQ",
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            var_dump($response);
        } 

*/
        // $payouts = new Payout();


        /*
         * {"sender_batch_header":
         * {"sender_batch_id":"2014021110112112801",
         *  "email_subject":"YouhaveaPayout!",
         *  "recipient_type":"EMAIL"},
         * "items":[{"recipient_type":"EMAIL",
         *  "amount":{"value":"1.0","currency":"USD"},
         *      "note":"Thanksforyourpatronage!",
         *  "sender_item_id":"201403140001",
         *  "receiver":"shirt-supplier-one@mail.com"}
         * ]
         * }
         *
         *
         *
         *
         <?php

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.sandbox.paypal.com/v1/payments/payouts",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer A21AAEPOs-Uq4WqYX8YQP_TZNi3BZUPvaUeIm4vRSx8grQJE3Mhla78x8zY5uYoX_LKLi_08SnDc1I1pVwTJK8XbMAfZOggWw",
            "Content-Type: application/x-www-form-urlencoded",
            "Postman-Token: 82203088-ff16-4447-aafb-8875b8f3116f",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }


        // codice con httprequest

        $request = new HttpRequest();
        $request->setUrl('https://api.sandbox.paypal.com/v1/payments/payouts');
        $request->setMethod(HTTP_METH_POST);

        $request->setHeaders(array(
          'Postman-Token' => 'b3922c60-d145-45c5-9357-25f5450cff0b',
          'cache-control' => 'no-cache',
          'Authorization' => 'Bearer A21AAEPOs-Uq4WqYX8YQP_TZNi3BZUPvaUeIm4vRSx8grQJE3Mhla78x8zY5uYoX_LKLi_08SnDc1I1pVwTJK8XbMAfZOggWw',
          'Content-Type' => 'application/x-www-form-urlencoded'
        ));

        $request->setContentType('application/x-www-form-urlencoded');
        $request->setPostFields(null);

        try {
          $response = $request->send();

          echo $response->getBody();
        } catch (HttpException $ex) {
          echo $ex;
        }

         */
		   
		   
       }
    public static function environment()
    {
        $clientId     = 'AfgrUXTSAHVQEiZxjDE2kk-SL_3OyHKAEwUqgVPUzXOCSZ240FU9myNQDLGSG9NeLHDmOLrVGNizMTAi';
        $clientSecret = 'ECJhV_P-d1Ak4nAqxnZIX4d6jBCaIGW9xZ5NCDPAirzwOd2hQtYHvj2wkGR1nxtjiq-sdOKYP0OwK9nT';
        return new SandboxEnvironment($clientId, $clientSecret);
    }

    public function ClienToken()
    {
       /* $gateway = new Braintree_Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'rf6zrcrjz24wnbzp',
            'publicKey' => '5nwr3tcp89j4q8dk',
            'privateKey' => '4a19bf637334b053548a4a7e365fcd1e'
        ]);
        */
        $config = new Braintree\Braintree_Configuration([
            'environment' => 'sandbox',
            'merchantId' => 'rf6zrcrjz24wnbzp',
            'publicKey' => '5nwr3tcp89j4q8dk',
            'privateKey' => '4a19bf637334b053548a4a7e365fcd1e'
        ]);
        $gateway = new Braintree\Gateway($config);
        echo($clientToken = $gateway->clientToken()->generate());
    }


	  public static function authorizeOrder($orderId, $debug=false)
	  {
		$request = new OrdersAuthorizeRequest($orderId);
		$request->body = Paypal::buildRequestBody();
		// 3. Call PayPal to authorize an order
		$client = new PayPalHttpClient(Paypal::environment());
		$response = $client->execute($request);
		// 4. Save the authorization ID to your database. Implement logic to save authorization to your database for future reference.
		if ($debug)
		{
		  print "Status Code: {$response->statusCode}\n";
		  print "Status: {$response->result->status}\n";
		  print "Order ID: {$response->result->id}\n";
		  print "Authorization ID: {$response->result->purchase_units[0]->payments->authorizations[0]->id}\n";
		  print "Links:\n";
		  foreach($response->result->links as $link)
		  {
			print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
		  }
		  print "Authorization Links:\n";
		  foreach($response->result->purchase_units[0]->payments->authorizations[0]->links as $link)
		  {
			print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
		  }
		  // To toggle printing the whole response body comment/uncomment the following line
		  echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
		}
		return $response;
	  }
	
    public static function getOrder($orderId)
    {
        // 3. Call PayPal to get the transaction details
        $client = new PayPalHttpClient(Paypal::environment());
        $response = $client->execute(new OrdersGetRequest($orderId));

        /**
         *Enable the following line to print complete response as JSON.
         */
        //print json_encode($response->result);
       /* print "Status Code: {$response->statusCode}\n";
        print "Status: {$response->result->status}\n";
        print "Order ID: {$response->result->id}\n";
        print "Intent: {$response->result->intent}\n";
        print "Links:\n";*/
        foreach($response->result->links as $link)
        {
            //print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
        }
        // 4. Save the transaction in your database. Implement logic to save transaction to your database for future reference.
       // print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

        // To print the whole response body, uncomment the following line
        // return json_encode($response->result, JSON_PRETTY_PRINT);
		return json_encode($response->result, JSON_PRETTY_PRINT);
    }
	
	public static function captureAuth($authorizationId, $debug=false)
  {
    $request = new AuthorizationsCaptureRequest($authorizationId);
    $request->body = Paypal::buildRequestBody();
    // 3. Call PayPal to capture an authorization.
    $client = new PayPalHttpClient(Paypal::environment());
    $response = $client->execute($request);
    // 4. Save the capture ID to your database for future reference.
    if ($debug)
    {
      print "Status Code: {$response->statusCode}\n";
      print "Status: {$response->result->status}\n";
      print "Capture ID: {$response->result->id}\n";
      print "Links:\n";
      foreach($response->result->links as $link)
      {
        print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
      }
      // To toggle printing the whole response body comment/uncomment
      // the follwowing line
      echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
    }
    return $response;
  }
  
  public static function buildRequestBody()
  {
    return "{}";
  }


}
