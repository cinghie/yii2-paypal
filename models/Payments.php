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
 */
class Payments extends ActiveRecord
{
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
        return [
            [['order_id', 'user_id', 'payment_id', 'state'], 'required'],
            [['order_id', 'user_id'], 'integer'],
            [['created'], 'safe'],
            [['payment_id'], 'string', 'max' => 64],
            [['state'], 'string', 'max' => 24],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('traits', 'ID'),
            'order_id' => Yii::t('traits', 'Order ID'),
            'user_id' => Yii::t('traits', 'User Id'),
            'payment_id' => Yii::t('traits', 'Payment ID'),
            'state' => Yii::t('traits', 'State'),
            'description' => Yii::t('traits', 'Description'),
            'created' => Yii::t('traits', 'Created'),
        ];
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
