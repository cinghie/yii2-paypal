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

use cinghie\traits\migrations\Migration;

class m151021_201536_create_paypal_transactions_table extends Migration
{
	/**
	 * @inheritdoc
	 */
    public function up()
    {
        $this->createTable('{{%payments_paypal_transactions}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->string(55)->defaultValue(null),
            'payment_id' => $this->string(55)->defaultValue(null),
            'currency' => $this->string(21)->defaultValue(null),
            'subtotal' => $this->float()->defaultValue(null),
            'tax' => $this->float()->defaultValue(null),
            'shipping' => $this->float()->defaultValue(null),
            'total_paid' => $this->float()->defaultValue(null),
            'description' => $this->string(255)->defaultValue(''),
        ], $this->tableOptions);

	    // Add Index and Foreign Key payment_id
	    $this->createIndex(
		    'index_payments_paypal_transactions_payment_id',
		    '{{%payments_paypal_transactions}}',
		    'payment_id'
	    );
	    $this->addForeignKey(
		    'fk_payments_paypal_transactions_payment_id',
		    '{{%payments_paypal_transactions}}', 'payment_id',
		    '{{%payments_paypal}}', 'payment_id',
		    'SET NULL', 'CASCADE'
	    );
    }

	/**
	 * @inheritdoc
	 */
    public function down()
    {
	    $this->dropForeignKey('fk_payments_paypal_transactions_payment_id', '{{%payments_paypal_transactions}}');
	    $this->dropIndex('index_payments_paypal_transactions_payment_id', '{{%payments_paypal_transactions}}');
        $this->dropTable('{{%payments_paypal_transactions}}');
    }
}
