<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
//    显示视图
    public function actionIndex()
    {
        $articleCategorys=ArticleCategory::find()->all();
        return $this->render('index',compact('articleCategorys'));

    }
    //添加
    public function actionAdd()
    {
        //生成模型对象
        $model = new ArticleCategory();
        $request = \Yii::$app->request;
        if ($request->isPost) {
//            绑定数据
            $model->load($request->post());
//            验证
            if($model->validate()){
//                保存数据
                $model->save();
                //                    提示
                \Yii::$app->session->setFlash("success", "添加成功");
//                    跳转
                return $this->redirect(["index"]);
            }

        }
        //显示视图
        return $this->render('add', ['model' => $model]);
    }

    //编辑
    public function actionEdit($id)
    {
        //生成模型对象
        $model = ArticleCategory::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
//            绑定数据
            $model->load($request->post());
//            验证
            if($model->validate()){
//                保存数据
                $model->save();
                //                    提示
                \Yii::$app->session->setFlash("success", "编辑成功");
//                    跳转
                return $this->redirect(["index"]);
            }

        }
        //显示视图
        return $this->render('add', ['model' => $model]);
    }
//    删除
    public function actionDel($id)
    {
        $model=ArticleCategory::findOne($id);
        if($model->delete()){
            \Yii::$app->session->setFlash("success", "删除成功");
            return $this->redirect(['index']);
        }

    }
}
