<?php

namespace frontend\controllers;

use backend\models\GoodsGallery;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionLists($id)
    {

       return $this->render('lists');
    }






















}
