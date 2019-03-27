<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model cinghie\paypal\models\Payments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('paypal', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payments-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('paypal', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('paypal', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('paypal', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'order_id',
            'user_id',
            'transaction_id',
            'payment_id',
            'client_token',
            'payment_method',
            'currency',
            'total_paid',
            'payment_state',
            'method',
            'description',
            'created_by',
            'created',
        ],
    ]) ?>

</div>
