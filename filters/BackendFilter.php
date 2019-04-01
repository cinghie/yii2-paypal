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

namespace cinghie\paypal\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\NotFoundHttpException;

/**
 * BackendFilter is used to allow access only to admin and security controller in frontend
 */
class BackendFilter extends ActionFilter
{
    /**
     * @var array
     */
    public $controllers = ['cancel','return'];

    /**
     * @param Action $action
     *
     * @return bool
     * @throws NotFoundHttpException
     */
    public function beforeAction($action)
    {
        if ( in_array( $action->controller->id, $this->controllers, true ) ) {
            throw new NotFoundHttpException(Yii::t('traits','Page not found'));
        }

        return true;
    }
}
