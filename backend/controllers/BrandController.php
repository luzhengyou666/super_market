<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //得到所有数据
        $brands = Brand::find()->all();

        return $this->render('index', compact('brands'));
    }

    //添加
    public function actionAdd()
    {
        //生成模型对象
        $model = new Brand();
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

/*//            得到图片对象
            $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
//            后端验证
            if ($model->validate()) {
//                定义上传路径
                $path = "";
//判断是否上传图片
                if ($model->logoFile) {
//                    路径
                    $path = "images/brand/" . uniqid() . "." . $model->logoFile->extension;
//                移动图片
                    $model->logoFile->saveAs($path, false);
                }
                //给logo赋值
                $model->logo = $path;
//                保存数据
                if ($model->save()) {
//                    提示
                    \Yii::$app->session->setFlash("success", "添加成功");
//                    跳转
                    return $this->redirect(["index"]);
                }
            } else {
//                TODO
                var_dump($model->errors);
                exit;
            }*/
        }
        //显示视图
        return $this->render('add', ['model' => $model]);
    }


//    添加结束

//编辑
    public function actionEdit($id)
    {
        //生成模型对象
        $model = Brand::findOne($id);

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

            /*//            得到图片对象
                        $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
            //            后端验证
                        if ($model->validate()) {
            //                定义上传路径
                            $path = "";
            //判断是否上传图片
                            if ($model->logoFile) {
            //                    路径
                                $path = "images/brand/" . uniqid() . "." . $model->logoFile->extension;
            //                移动图片
                                $model->logoFile->saveAs($path, false);
                            }
                            //给logo赋值
                            $model->logo = $path;
            //                保存数据
                            if ($model->save()) {
            //                    提示
                                \Yii::$app->session->setFlash("success", "添加成功");
            //                    跳转
                                return $this->redirect(["index"]);
                            }
                        } else {
            //                TODO
                            var_dump($model->errors);
                            exit;
                        }*/
        }
        //显示视图
        return $this->render('add', ['model' => $model]);
      /* //生成模型对象
        $model = Brand::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
//            绑定数据
            $model->load($request->post());

//            得到图片对象
            $model->logoFile = UploadedFile::getInstance($model, 'logoFile');
//            后端验证
            if ($model->validate()) {
//                定义上传路径
                $path = $model->logo;
//判断是否上传图片
                if ($model->logoFile) {
//                    删除之前的图片
                    unlink($path);
//                    路径
                    $path = "images/brand/" . uniqid() . "." . $model->logoFile->extension;
//                移动图片
                    $model->logoFile->saveAs($path, false);
                }
                //给logo赋值
                $model->logo = $path;
//                保存数据
                if ($model->save()) {
//                    提示
                    \Yii::$app->session->setFlash("success", "修改成功");
//                    跳转
                    return $this->redirect(["index"]);
                }
            } else {
//                TODO
                var_dump($model->errors);
                exit;
            }
        }
        //显示视图
        return $this->render('add', ['model' => $model]);*/
    }
//编辑结束

//删除
    public function actionDel($id)
    {
        $model=Brand::findOne($id);
        unlink($model->logo);
        if($model->delete()){
            \Yii::$app->session->setFlash("success", "删除成功");
            return $this->redirect(['index']);
        }

    }
//删除结束
    public function actionUpload()
    {
//        得到上传文件的实例对象
        $file=UploadedFile::getInstanceByName("file");
        if($file){
            //                    路径
            $path = "images/brand/" . time() . "." . $file->extension;

//            移动图片
            if($file->saveAs($path,false)){
                $result=[
                  'code'=>0,
                  'url'=>"/".$path,
                    'attachment'=>$path

                ];
                return json_encode($result);
            }
        }

    }

}