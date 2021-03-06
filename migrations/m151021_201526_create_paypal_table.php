<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-paypal
 * @license BSD-3-Clause
 * @package yii2-paypal
 * @version 0.2.4
 */

use cinghie\traits\migrations\Migration;

class m151021_201526_create_paypal_table extends Migration
{
	/**
	 * @inheritdoc
	 */
    public function up()
    {
        $this->createTable('{{%payments_paypal}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->defaultValue(null),
            'user_id' => $this->integer(11)->defaultValue(null),
            'payment_id' => $this->string(55)->defaultValue(null),
            'client_token' => $this->string(255)->defaultValue(null),
            'payment_method' => $this->string(255)->defaultValue(null),
            'total_paid' => $this->float()->defaultValue(null),
            'payment_state' => $this->string(255)->defaultValue(null),
            'created_by' => $this->integer(11)->defaultValue(null),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $this->tableOptions);

	    // Add Index and Foreign Key payment_id
	    $this->createIndex(
		    'index_payments_paypal_payment_id',
		    '{{%payments_paypal}}',
		    'payment_id'
	    );

	    // Add Index and Foreign Key user_id
	    $this->createIndex(
		    'index_payments_paypal_user_id',
		    '{{%payments_paypal}}',
		    'user_id'
	    );
	    $this->addForeignKey(
		    'fk_payments_paypal_user_id',
		    '{{%payments_paypal}}', 'user_id',
		    '{{%user}}', 'id',
		    'SET NULL', 'CASCADE'
	    );

	    // Add Index and Foreign Key created_by
	    $this->createIndex(
		    'index_payments_paypal_created_by',
		    '{{%payments_paypal}}',
		    'created_by'
	    );
	    $this->addForeignKey('fk_payments_paypal_created_by',
		    '{{%payments_paypal}}', 'created_by',
		    '{{%user}}', 'id',
		    'SET NULL', 'CASCADE'
	    );
    }

	/**
	 * @inheritdoc
	 */
    public function down()
    {
	    $this->dropForeignKey('fk_payments_paypal_user_id', '{{%payments_paypal}}');
	    $this->dropForeignKey('fk_payments_paypal_created_by', '{{%payments_paypal}}');
	    $this->dropIndex('index_payments_paypal_payment_id', '{{%payments_paypal}}');
	    $this->dropIndex('index_payments_paypal_user_id', '{{%payments_paypal}}');
	    $this->dropIndex('index_payments_paypal_created_by', '{{%payments_paypal}}');
        $this->dropTable('{{%payments_paypal}}');
    }
}
