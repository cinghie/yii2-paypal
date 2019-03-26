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
 * @property string $payment_id
 * @property string $state
 * @property string $description
 * @property string $created
 * @property int $created_by
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
            [['order_id', 'payment_id', 'state'], 'required'],
            [['order_id'], 'integer'],
            [['payment_id'], 'string', 'max' => 64],
            [['state'], 'string', 'max' => 24],
            [['description'], 'string', 'max' => 255],
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
            'state' => Yii::t('traits', 'State'),
            'description' => Yii::t('traits', 'Description'),
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
    	$payments->userd_id = $payments->getCurrentUser();
	    $payments->payment_id = $payment->getId();
    	$payments->state = $payment->getState();
    	$payments->created = $payment->getCreateTime();
    	$payments->created_by = $payments->getCurrentUser();
    	$payments->save();

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
