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

use cinghie\traits\migrations\Migration;

class m151021_200526_create_paypal_table extends Migration
{
	/**
	 * @inheritdoc
	 */
    public function up()
    {
        $this->createTable('{{%paypal_orders}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'payment_id' => $this->string(64)->notNull(),
            'state' => $this->string(24)->notNull(),
            'description' => $this->string(255)->defaultValue(''),
            'created' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $this->tableOptions);
    }

	/**
	 * @inheritdoc
	 */
    public function down()
    {
        $this->dropTable('{{%paypal_orders}}');
    }
}
