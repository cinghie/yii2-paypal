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

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class Helper
{

   /*
    * Set Item
    *
    * @property string quantity
    * @property string name
    * @property string description
    * @property string price
    * @property string tax
    * @property string currency
    * @property string sku
    * @property string url
    * @property string category
    * @property \PayPal\Api\Measurement weight
    * @property \PayPal\Api\Measurement length
    * @property \PayPal\Api\Measurement height
    * @property \PayPal\Api\Measurement width
    * @property \PayPal\Api\NameValuePair[] supplementary_data
    * @property \PayPal\Api\NameValuePair[] postback_data
    *
    */

    public function setPaypalItem($name,$currency,$quantity,$sku,$price)
    {
        $price = number_format($price,2);

        $item = new Item();
        $item->setQuantity($quantity);
        $item->setName($name);
        $item->setPrice($price);
        $item->setCurrency($currency);
        $item->setSku($sku);

        return $item;
    }

    /*
     * Set ItemList
     *
     * @property \PayPal\Api\Item[] items
     * @property \PayPal\Api\ShippingAddress shipping_address
     * @property string shipping_method
     *
     */

    public function setPaypalItemList($array)
    {
        $itemList = new ItemList();
        $itemList->setItems($array);

        return $itemList;
    }

    /*
     * Set Address Item
     *
     * @property string line1
     * @property string line2
     * @property string city
     * @property string country_code
     * @property string postal_code
     * @property string state
     * @property string normalization_status
     * @property string status
     *
     */

    public function setPaypalAddress($line1,$line2,$city,$countryCode,$postalCode,$state)
    {
        $address = new Address();
        $address->setLine1($line1);
        $address->setLine2($line2);
        $address->setCity($city);
        $address->setCountryCode($countryCode);
        $address->setPostalCode($postalCode);
        $address->setState($state);

        return $address;
    }

    /*
     * Set RedirectUrls
     *
     * @property string return_url
     * @property string cancel_url
     *
     */

    public function setPaypalRedirectUrls($returnUrl,$cancelUrl)
    {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl);
        $redirectUrls->setCancelUrl($cancelUrl);

        return $redirectUrls;
    }

    /*
     * Set Payer
     *
     * @property string payment_method
     * @property string status
     * @property string account_type
     * @property string account_age
     * @property \PayPal\Api\FundingInstrument[] funding_instruments
     * @property string funding_option_id
     * @property \PayPal\Api\FundingOption funding_option
     * @property \PayPal\Api\FundingOption related_funding_option
     * @property \PayPal\Api\PayerInfo payer_info
     *
     */

    public function setPaypalPayer($payment_method)
    {
        $payer = new Payer();
        $payer->setPaymentMethod($payment_method);

        return $payer;
    }

    /*
     * Set Details
     *
     * @property string subtotal
     * @property string shipping
     * @property string tax
     * @property string handling_fee
     * @property string shipping_discount
     * @property string insurance
     * @property string gift_wrap
     * @property string fee
     *
     */
    public function setPaypalDetails($shipping,$tax,$subtotal)
    {
        $subtotal = number_format($subtotal,2);
        $shipping = number_format($shipping,2);
        $tax      = number_format($tax,2);

        $details  = new Details();
        $details->setSubtotal($subtotal);
        $details->setShipping($shipping);
        $details->setTax($tax);

        return $details;
    }

    /*
     * Set Amount
     *
     * @property string currency
     * @property string total
     * @property \PayPal\Api\Details details
     *
     */

    public function setPaypalAmount($currency,$total,$details)
    {
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal(number_format($total,2));

        if($details) {
            $amount->setDetails($details);
        }

        return $amount;
    }

    // Set Card
    public function setPaypalCard($number,$type,$expireMonth,$expireYear,$cvv2,$firstname,$lastname,$addr)
    {
        $card = new CreditCard();
        $card->setNumber($number);
        $card->setType($type);
        $card->setExpireMonth($expireMonth);
        $card->setExpireYear($expireYear);
        $card->setCvv2($cvv2);
        $card->setFirstName($firstname);
        $card->setLastName($lastname);
        $card->setBillingAddress($addr);

        return $card;
    }

    /*
     * Set Transaction
     *
     * @property \PayPal\Api\Amount amount
     * @property \PayPal\Api\Payee payee
     * @property string description
     * @property string note_to_payee
     * @property string custom
     * @property string invoice_number
     * @property string soft_descriptor
     * @property \PayPal\Api\PaymentOptions payment_options
     * @property \PayPal\Api\ItemList item_list
     * @property string notify_url
     * @property string order_url
     *
     */

    public function setPaypalTransaction($amount,$descr,$invoiceNumber,$itemList)
    {
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($descr);
        $transaction->setInvoiceNumber($invoiceNumber);
        $transaction->setItemList($itemList);

        return $transaction;
    }

    /*
     * Set Payment
     *
     * @property string id
     * @property string create_time
     * @property string update_time
     * @property string intent
     * @property \PayPal\Api\Payer payer
     * @property \PayPal\Api\Payee payee
     * @property string cart
     * @property \PayPal\Api\Transaction[] transactions
     * @property \PayPal\Api\Error[] failed_transactions
     * @property \PayPal\Api\PaymentInstruction payment_instruction
     * @property string state
     * @property \PayPal\Api\RedirectUrls redirect_urls
     * @property string experience_profile_id
     *
     */

    public function setPaypalPayment($intent,$payer,$redirectUrls,$transaction)
    {
        $payment = new Payment();
        $payment->setIntent($intent);
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrls);
        $payment->setTransactions(array($transaction));

        return $payment;
    }

}