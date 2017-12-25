<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Brand */
/* @var $form ActiveForm */
?>
<div class="article-add">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'status')->radioList(['0'=>'隐藏','1'=>'显示'])?>
    <?= $form->field($model, 'sort')->textInput(['value'=>100]) ?>
    <?= $form->field($model, 'intro')->textarea() ?>
    <?= $form->field($model, 'is_help')?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- article-add -->