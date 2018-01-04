<?php

namespace backend\controllers;

use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        //1.获取所有权限
        $roles=$auth->getRoles();
        //var_dump($roles);exit;
        return $this->render('index',compact('roles'));
    }
    public function actionAdd()
    {
        //实例化authManager组件

        $auth = \Yii::$app->authManager;

        $model = new AuthItem();

        //找出所有权限
        $pers=$auth->getPermissions();
//        var_dump($pers);exit;
        $persArr=ArrayHelper::map($pers,'name','description');

        // var_dump($persArr);exit;

        //判断是不是POST提交
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //1. 创建角色
            $role = $auth->createRole($model->name);
            //2.设置描述
            $role->description = $model->description;

            //  var_dump($model->permissions);exit;
            //3.添加入库
            if ($auth->add($role)) {

                if ($model->permissions) {
                    //4.给角色添加权限
                    foreach ($model->permissions as $perName){

                        //4.1通过权限名称找到对应的权限对象
                        $permission=$auth->getPermission($perName);
                        //4.2把权限加入到角色中
                        $auth->addChild($role,$permission);

                    }
                }
                \Yii::$app->session->setFlash("success", '添加角色' . $model->name . "成功");
                //4.刷新
                return $this->redirect(['index']);
            }

        }
        return $this->render('add', compact('model','persArr'));
    }

    public function actionEdit($name)
    {
        //实例化authManager组件
        $auth = \Yii::$app->authManager;
        //找到当前角色
        $model = AuthItem::findOne($name);
        //找出所有权限
        $pers=$auth->getPermissions();
        $persArr=ArrayHelper::map($pers,'name','description');
        // var_dump($persArr);exit;
        //判断是不是POST提交
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //1. 找到角色
            $role = $auth->getRole($name);
            //2.设置描述
            $role->description = $model->description;
            //3.添加入库
            if ($auth->update($name,$role)) {
                //删除当角角所对应的权限  删除角色对应的所有权限
                $auth->removeChildren($role);
                if ($model->permissions) {
                    //4.给角色添加权限
                    foreach ($model->permissions as $perName){
                        //4.1通过权限名称找到对应的权限对象
                        $permission=$auth->getPermission($perName);
                        //4.2把权限加入到角色中
                        $auth->addChild($role,$permission);
                    }
                }

                \Yii::$app->session->setFlash("success", '添加角色' . $model->name . "成功");
                //4.刷新
                return $this->redirect(['index']);
            }

        }

        //当前角色所对应的权限 通过角色找权限
        $roles=$auth->getPermissionsByRole($name);
        //取出所有权限的key值
        $model->permissions=array_keys($roles);
        //$model->description="111";
        return $this->render('add', compact('model','persArr'));
    }

    public function actionDel($name){

        //实例化authManager组件
        $auth = \Yii::$app->authManager;

        //1.找到要删除的角色
        $role=$auth->getRole($name);
        //2.删除角色所对应的所有权限
        $auth->removeChildren($role);
        //3.删除角色
        if ($auth->remove($role)) {
            return $this->redirect(['index']);
        }
    }
}
