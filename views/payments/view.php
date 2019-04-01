<?php

use kartik\detail\DetailView;
use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model cinghie\paypal\models\Payments */

$this->title = $model->payment_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('traits', 'PayPal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('traits', 'Transaction').' '.$this->title;

?>

<div class="payments-view">

	<?php if(Yii::$app->getModule('paypal')->showTitles): ?>
        <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
	<?php endif ?>

    <div class="row">

        <div class="col-md-6">

        </div>

        <div class="col-md-6">

		    <?= $model->getExitButton() ?>

        </div>

    </div>

    <div class="separator"></div>

    <div class="row">

        <div class="col-md-12">

		    <?= DetailView::widget([
			    'model' => $model,
			    'condensed' => true,
			    'enableEditMode' => false,
			    'hover' => true,
			    'mode' => DetailView::MODE_VIEW,
			    'panel' => [
				    'heading' => '<h3 class="panel-title"><i class="fa fa-paypal"></i> '.Yii::t('traits', 'Payments').'</h3>',
				    'type' => DetailView::TYPE_INFO,
			    ],
			    'attributes' => [
				    [
					    'columns' => [
						    [
							    'attribute' => 'payment_id',
							    'valueColOptions' => ['style'=>'width:30%']
						    ],
						    [
							    'attribute' => 'id',
						    ]
					    ]
				    ],
				    [
					    'columns' => [
						    [
							    'attribute' => 'payment_method',
							    'valueColOptions' => ['style'=>'width:30%']
						    ],
						    [
							    'attribute' => 'order_id',
						    ]
					    ]
				    ],
				    [
					    'columns' => [
						    [
							    'attribute' => 'total_paid',
							    'valueColOptions' => ['style'=>'width:30%']
						    ],
						    [
							    'attribute' => 'created_by',
							    'format' => 'raw',
							    'valueColOptions' => ['style'=>'width:30%'],
							    'value' => $model->created_by ? Html::a($model->createdBy->username,urldecode(Url::toRoute(['/user/admin/update', 'id' => $model->createdBy]))) : Yii::t('traits', 'Nobody'),
						    ],
					    ]
				    ],
				    [
					    'columns' => [
						    [
							    'attribute' => 'payment_state',
							    'valueColOptions' => ['style'=>'width:30%']
						    ],
						    [
							    'attribute' => 'created',
						    ],
					    ]
				    ],
			    ]
		    ]) ?>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

	        <?= GridView::widget([
		        'dataProvider' => $dataProvider,
		        'pjaxSettings'=>[
			        'neverTimeout'=>true,
		        ],
		        'columns' => [
		            [
				        'attribute' => 'transaction_id',
				        'hAlign' => 'center'
                    ],
			        [
				        'attribute' => 'subtotal',
				        'hAlign' => 'center'
			        ],
			        [
				        'attribute' => 'tax',
				        'hAlign' => 'center'
			        ],
			        [
				        'attribute' => 'shipping',
				        'hAlign' => 'center'
			        ],
			        [
				        'attribute' => 'total_paid',
				        'hAlign' => 'center'
			        ],
			        [
				        'attribute' => 'currency',
				        'hAlign' => 'center'
			        ],
                    [
				        'attribute' => 'id',
				        'width' => '6%',
				        'hAlign' => 'center'
                    ]
                ],
		        'responsive' => true,
		        'hover' => true,
		        'panel' => [
			        'heading'    => '<h3 class="panel-title"><i class="fa fa-th-list"></i> '.Yii::t('newsletters', 'Subscribers').'</h3>',
			        'type'       => 'success',
		        ],
	        ]) ?>

        </div>

    </div>

</div>
