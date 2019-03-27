<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cinghie\paypal\models\Payments */

$this->title = Yii::t('paypal', 'Create Payments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('paypal', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
