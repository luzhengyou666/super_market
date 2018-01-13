<?php

namespace frontend\controllers;

class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            //回到登录页面

            return $this->redirect(['user/login','back'=>'order/index']);

        }


        return $this->render('index');
    }

}
