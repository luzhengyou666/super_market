<?php

namespace backend\controllers;

use backend\models\AuthItem;

class PermissionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        //1.获取所有权限
        $permissions=$auth->getPermissions();
        //var_dump($permissions);exit;
        return $this->render('index',compact('permissions'));
    }
    public function actionAdd()
    {
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        $model = new AuthItem();
        //判断是不是POST提交
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //1. 创建权限
            $permission = $auth->createPermission($model->name);
            //2.设置权限
            $permission->description = $model->description;
            //3.添加入库
            if ($auth->add($permission)) {
                \Yii::$app->session->setFlash("success", '添加权限' . $model->name . "成功");
                //4.刷新
                return $this->redirect(['index']);
            }
        }
        return $this->render('add', compact('model'));
    }

    public function actionEdit($name)
    {
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        //找到需要修改的权限
        $model = AuthItem::findOne($name);
        //判断是不是POST提交
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //1. 找到对应权限
            $permission = $auth->getPermission($model->name);
            // var_dump($permission);exit;
            if ($permission){
                //2.设置权限
                $permission->description = $model->description;
                //3.添加入库
                if ($auth->update($name,$permission)) {
                    \Yii::$app->session->setFlash("success", '修改权限' . $model->name . "成功");
                    //4.跳列表页
                    return $this->redirect(['index']);
                }
            }else{
                \Yii::$app->session->setFlash("danger", '不能修改名称' . $model->name );
                //刷新
                return $this->refresh();
            }


        }
        return $this->render('edit', compact('model'));


    }

    public function actionDel($name){
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        //1. 找到对象
        $permission=$auth->getPermission($name);
        //2. 删除对象
        if ($auth->remove($permission)) {
            return $this->redirect(['index']);
        }


    }
}
