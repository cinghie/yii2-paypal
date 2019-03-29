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
 * This is the model class for table "{{%payments_paypal_transactions}}".
 *
 * @property int $id
 * @property string $transaction_id
 * @property string $currency
 * @property double $subtotal
 * @property double $tax
 * @property double $shipping
 * @property double $total_paid
 * @property string $description
 */
class Transactions extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments_paypal_transactions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subtotal', 'tax', 'shipping', 'total_paid'], 'number'],
            [['transaction_id'], 'string', 'max' => 55],
            [['currency'], 'string', 'max' => 21],
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
            'transaction_id' => Yii::t('traits', 'Transaction ID'),
            'currency' => Yii::t('traits', 'Currency'),
            'subtotal' => Yii::t('traits', 'Subtotal'),
            'tax' => Yii::t('traits', 'Tax'),
            'shipping' => Yii::t('traits', 'Shipping'),
            'total_paid' => Yii::t('traits', 'Total Paid'),
            'description' => Yii::t('traits', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     *
     * @return TransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionsQuery( static::class );
    }
}
