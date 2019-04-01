<?php

/* @var $this yii\web\View */
/* @var $searchModel cinghie\paypal\models\PaymentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('traits', 'PayPal');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="payments-index">

	<?php if(Yii::$app->getModule('paypal')->showTitles): ?>
        <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
	<?php endif ?>

    <?php if(Yii::$app->paypal->checkIsSandbox()): ?>

        <div class="row">

            <div class="col-md-6">



            </div>

            <div class="col-md-6">

                <?= $searchModel->getStandardButton(
                        'fa fa-credit-card',
                        Yii::t('paypal','Credit Card Demo Payment'),
                        ['demo','type' => 'credit_card'],
                        ['data' => ['method' => 'post']],
                        'pull-right text-center',
                        'margin-right: 25px; max-width: 125px;'
                ) ?>

                <?= $searchModel->getStandardButton(
                        'fa fa-paypal',
                        Yii::t('paypal','Paypal Demo Payment'),
                        ['demo','type' => 'paypal'],
                        ['data' => ['method' => 'post'],'style' => 'max-width: 125px;'],
                        'pull-right text-center',
                        'margin-right: 25px; max-width: 125px;'
                ) ?>

            </div>

        </div>

        <div class="separator"></div>

    <?php endif ?>

	<?= GridView::widget([
		'dataProvider'=> $dataProvider,
		'filterModel' => $searchModel,
		'containerOptions' => [
			'class' => 'paypal-payments-pjax-container'
		],
		'pjax' => true,
		'pjaxSettings'=>[
			'neverTimeout' => true,
		],
        'columns' => [
	        [
		        'class' => CheckboxColumn::class
	        ],
	        [
		        'attribute' => 'payment_id',
		        'format' => 'html',
		        'hAlign' => 'center',
		        'value' => function ($model) {
			        $url = urldecode(Url::toRoute(['/paypal/payments/view','id' => $model->id]));
			        return Html::a($model->payment_id,$url);
		        }
	        ],
	        [
		        'attribute' => 'payment_method',
		        'hAlign' => 'center',
		        'width' => '12%',
            ],
	        [
		        'attribute' => 'payment_state',
		        'hAlign' => 'center',
		        'width' => '8%',
	        ],
	        [
		        'attribute' => 'total_paid',
		        'hAlign' => 'center',
		        'width' => '8%',
	        ],
	        [
		        'attribute' => 'created_by',
		        'filterType' => GridView::FILTER_SELECT2,
		        'filter' => $searchModel->getUsersSelect2(),
		        'filterWidgetOptions' => [
			        'pluginOptions' => ['allowClear' => true],
		        ],
		        'filterInputOptions' => ['placeholder' => ''],
		        'format' => 'raw',
		        'hAlign' => 'center',
		        'width' => '8%',
		        'value' => function ($model) {
			        /** @var $model cinghie\paypal\models\Payments */
			        return $model->getCreatedByGridView();
		        }
	        ],
	        [
		        'attribute' => 'created',
		        'hAlign' => 'center',
		        'width' => '12%',
	        ],
	        [
		        'attribute' => 'id',
		        'width' => '5%',
		        'hAlign' => 'center',
	        ]
        ],
		'responsive' => true,
		'responsiveWrap' => true,
		'hover' => true,
		'panel' => [
			'heading' => '<h3 class="panel-title"><i class="fa fa-paypal"></i></h3>',
			'type' => 'success',
		],
    ]) ?>

</div>
