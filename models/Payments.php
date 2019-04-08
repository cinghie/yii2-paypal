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

namespace cinghie\paypal\models;

use Yii;
use cinghie\traits\CreatedTrait;
use cinghie\traits\UserHelpersTrait;
use cinghie\traits\UserTrait;
use cinghie\traits\ViewsHelpersTrait;
use PayPal\Api\Payment;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments_paypal}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $payment_id
 * @property string $client_token
 * @property string $payment_method
 * @property string $currency
 * @property double $total_paid
 * @property string $payment_state
 * @property int $created_by
 * @property string $created
 *
 * @property User $createdBy
 * @property User $user
 */
class Payments extends ActiveRecord
{
	use CreatedTrait, UserHelpersTrait, UserTrait, ViewsHelpersTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments_paypal}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
	    return array_merge(CreatedTrait::rules(), UserTrait::rules(), [
		    [['payment_id'], 'required'],
		    [['order_id'], 'integer'],
		    [['total_paid'], 'number'],
		    [['payment_id'], 'string', 'max' => 55],
		    [['client_token', 'payment_method'], 'string', 'max' => 255],
		    [['payment_state'], 'string', 'max' => 24],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(CreatedTrait::attributeLabels(), UserTrait::attributeLabels(), [
	        'id' => Yii::t('traits', 'ID'),
	        'order_id' => Yii::t('traits', 'Order ID'),
	        'payment_id' => Yii::t('traits', 'Payment ID'),
	        'client_token' => Yii::t('traits', 'Client Token'),
	        'payment_method' => Yii::t('traits', 'Payment Method'),
	        'total_paid' => Yii::t('traits', 'Total Paid'),
	        'payment_state' => Yii::t('traits', 'Payment State'),
	        'created_by' => Yii::t('traits', 'Created By'),
	        'created' => Yii::t('traits', 'Created'),
        ]);
    }

	/**
	 * Create Payments DB from Paypal Paymen
	 *
	 * @param Payment $payment
	 */
	public static function createPayments($payment)
    {
    	$payments = new self();

    	$currentUser    = $payments->getCurrentUser()->id;
    	$orderID        = 1;
    	$paymentAmount  = Transactions::getTransactionTotal($payment->getTransactions());
    	$paymentCreated = $payments->convertDateToDateTime($payment->getCreateTime());
    	$paymentID      = $payment->getId();
    	$paymentMethod  = $payment->getPayer()->getPaymentMethod();
    	$paymentState   = $payment->getState();

    	// Create Payments
	    $payments->created = $paymentCreated;
	    $payments->created_by = $currentUser;
    	$payments->order_id = $orderID;
	    $payments->payment_id = $paymentID;
	    $payments->payment_method = $paymentMethod;
    	$payments->payment_state = $paymentState;
	    $payments->total_paid = $paymentAmount;
	    $payments->user_id = $currentUser;
    	$payments->save();

	    // Create Transactions
	    Transactions::createTransactions($paymentID,$payment->getTransactions());
    }

	/**
	 * Convert Paypal Date in DateTime format
	 *
	 * @param $date
	 *
	 * @return false|string
	 */
	public function convertDateToDateTime($date)
    {
    	return date('Y-m-d H:m:s', strtotime($date));
    }

    /**
     * @inheritdoc
     *
     * @return PaymentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PaymentsQuery(static::class);
    }
}
