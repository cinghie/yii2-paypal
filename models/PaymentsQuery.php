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

namespace cinghie\paypal\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Payments]].
 *
 * @see Payments
 */
class PaymentsQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     *
     * @param int $limit
     * @param string $order
     * @param string $orderby
     *
     * @return PaymentsQuery
     */
    public function last($limit, $orderby = 'id', $order = 'DESC')
    {
        return $this->orderBy([$orderby => $order])->limit($limit);
    }

    /**
     * @inheritdoc
     *
     * @return Payments[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     *
     * @return Payments|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
