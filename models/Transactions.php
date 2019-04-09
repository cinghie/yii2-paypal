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
use PayPal\Api\Transaction;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%payments_paypal_transactions}}".
 *
 * @property int $id
 * @property string $transaction_id
 * @property string $payment_id
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
            [['payment_id','transaction_id'], 'string', 'max' => 55],
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
            'payment_id' => Yii::t('traits', 'Payment ID'),
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
	 * Create Transactions
	 *
	 * @param string $paymentID
	 * @param Transaction[] $transactions
	 */
	public static function createTransactions($paymentID,$transactions)
	{
		foreach ($transactions as $transaction)
		{
			$newTransactions = new self();
			$newTransactions->transaction_id = $newTransactions->getTransactionID($transaction);
			$newTransactions->payment_id = $paymentID;
			$newTransactions->currency = $transaction->getAmount()->getCurrency();
			$newTransactions->subtotal = number_format((float)$transaction->getAmount()->getDetails()->getSubtotal(),2);
			$newTransactions->tax = number_format((float)$transaction->getAmount()->getDetails()->getTax(),2);
			$newTransactions->shipping = number_format((float)$transaction->getAmount()->getDetails()->getShipping(),2);
			$newTransactions->total_paid =  number_format((float)$transaction->getAmount()->getTotal(),2);
			$newTransactions->description = $transaction->getDescription();
			$newTransactions->save();
		}
	}

	/**
	 * Get Transaction ID
	 *
	 * @param Transaction $transaction
	 *
	 * @return string
	 */
	public function getTransactionID($transaction)
    {
	    $relatedResources = $transaction->getRelatedResources();
	    $sale = $relatedResources[0]->getSale();

	    return $sale->getId();
    }

	/**
	 * Get Total Paid by Transaction[]
	 *
	 * @param Transaction[] $transactions
	 *
	 * @return float
	 */
	public static function getTransactionTotal($transactions)
	{
		$total = 0;

		if($transactions !== null)
		{
			foreach ($transactions as $transaction) {
				$total += (float)$transaction->getAmount()->getTotal();
			}
		}

		return $total;
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
