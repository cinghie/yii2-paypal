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

namespace cinghie\paypal\controllers;

use RuntimeException;
use Throwable;
use Yii;
use cinghie\paypal\models\Demo;
use cinghie\paypal\models\Payments;
use cinghie\paypal\models\PaymentsSearch;
use cinghie\paypal\models\TransactionsSearch;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * PaymentsController implements the CRUD actions for Payments model.
 */
class PaymentsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
	        'access' => [
		        'class' => AccessControl::class,
		        'rules' => [
			        [
				        'actions' => ['index','view','delete','demo'],
				        'allow' => true,
				        'roles' => $this->module->paypalRoles
			        ],
			        [
				        'actions' => ['cancel','return'],
				        'allow' => true,
				        'roles' => ['@']
			        ]
		        ],
		        'denyCallback' => static function () {
			        throw new RuntimeException(Yii::t('traits','You are not allowed to access this page'));
		        }
	        ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'demo' => ['POST'],
                ],
            ],
        ];
    }

	/**
	 * @param string $type
	 *
	 * @return mixed
	 * @throws ErrorException
	 */
	public function actionDemo($type)
    {
    	$demo = new Demo();

    	switch ($type)
	    {
		    case 'credit_card':
		    	// Add Credit Card Demo Content
			    $demo->payByCreditCardDemo();
			    // Set Success Message
			    Yii::$app->session->setFlash('success', Yii::t('paypal', 'Credit Card Demo Payment added!'));
			    break;
		    case 'paypal':
			    // Add Paypal Demo Content
			    $demo->payByPaypalDemo();
			    // Set Success Message
			    Yii::$app->session->setFlash('success', Yii::t('paypal', 'Paypal Demo Payment added!'));
			    break;
	    }

	    return $this->redirect(['index']);
    }

    /**
     * Lists all Payments models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payments model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
    	$model = $this->findModel($id);
	    $searchModel = new TransactionsSearch();
	    $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->payment_id);

	    return $this->render('view', [
		    'model' => $model,
		    'dataProvider' => $dataProvider
	    ]);
    }

	/**
	 * Unsucces URL for Paypal Cancel URL
	 *
	 * @return mixed
	 */
	public function actionCancel()
	{
		return $this->render('cancel');
	}

	/**
	 * Success URL for Paypal Return URL
	 *
	 * @return mixed
	 */
	public function actionReturn()
    {
	    return $this->render('return');
    }

	/**
	 * Deletes an existing Payments model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @throws StaleObjectException
	 * @throws Throwable
	 */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Payments
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Payments::findOne($id)) !== null) {
            return $model;
        }

	    throw new NotFoundHttpException(Yii::t('traits','The requested page does not exist.'));
    }
}
