<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cinghie\paypal\models\Payments */

$this->title = Yii::t('paypal', 'Update Payments: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('paypal', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('paypal', 'Update');
?>
<div class="payments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
