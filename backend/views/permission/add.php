<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/3 0003
 * Time: 下午 2:58
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();