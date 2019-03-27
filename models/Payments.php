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

namespace cinghie\paypal\models;

use Yii;
use cinghie\traits\CreatedTrait;
use cinghie\traits\UserHelpersTrait;
use cinghie\traits\UserTrait;
use PayPal\Api\Payment;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments_paypal}}".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property string $transaction_id
 * @property string $payment_id
 * @property string $client_token
 * @property string $payment_method
 * @property string $currency
 * @property double $total_paid
 * @property string $payment_state
 * @property string $method
 * @property string $description
 * @property int $created_by
 * @property string $created
 *
 * @property User $createdBy
 * @property User $user
 */
class Payments extends ActiveRecord
{
	use CreatedTrait, UserHelpersTrait, UserTrait;

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
		    [['order_id'], 'integer'],
		    [['total_paid'], 'number'],
		    [['transaction_id', 'payment_id'], 'string', 'max' => 55],
		    [['client_token', 'payment_method', 'method', 'description'], 'string', 'max' => 255],
		    [['currency'], 'string', 'max' => 21],
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
	        'user_id' => Yii::t('traits', 'User Id'),
	        'transaction_id' => Yii::t('traits', 'Transaction ID'),
	        'payment_id' => Yii::t('traits', 'Payment ID'),
	        'client_token' => Yii::t('traits', 'Client Token'),
	        'payment_method' => Yii::t('traits', 'Payment Method'),
	        'currency' => Yii::t('traits', 'Currency'),
	        'total_paid' => Yii::t('traits', 'Total Paid'),
	        'payment_state' => Yii::t('traits', 'Payment State'),
	        'method' => Yii::t('traits', 'Method'),
	        'description' => Yii::t('traits', 'Description'),
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
    	$payments->order_id = 1;
    	$payments->user_id = $payments->getCurrentUser()->id;
	    $payments->payment_id = $payment->getId();
    	$payments->payment_state = $payment->getState();
    	$payments->created = date('Y-m-d H:m:s', strtotime($payment->getCreateTime()));
    	$payments->created_by = $payments->getCurrentUser()->id;
    	$payments->save();

	    echo '<pre>'; var_dump($payments->errors); echo '</pre>';

    	echo '<pre>'; var_dump($payment); echo '</pre>';
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
