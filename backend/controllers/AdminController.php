<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AuthItem;
use backend\models\LoginForm;
use yii\helpers\ArrayHelper;


class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $admins=Admin::find()->all();
        return $this->render('index',['admins'=>$admins]);
    }
//    登陆
    public function actionLogin()
    {
        if(!\Yii::$app->user->isGuest){
            return $this->redirect(['index']);

        }
        $model=new LoginForm();
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if($model->validate()){
            $admin=Admin::findOne(['username'=>$model->username]);
            if ($admin){
                if (\Yii::$app->security->validatePassword($model->password,$admin->password_hash)) {
                    \Yii::$app->user->login($admin,$model->rememberMe?3600*24*7:0);
                    $admin->login_at=time();
                    $admin->login_ip=ip2long(\Yii::$app->request->userIP);
                    $admin->save();
                    \Yii::$app->session->setFlash('success','登陆成功');
                   return $this->redirect(['index']);
                }else{
                    $model->addError('password','密码不正确');
                }

            }
            }else{
                $model->addError('username','用户名不存在');
            }
        }
        return $this->render('login',compact('model'));
    }


    //增加

    /**
     * @return string|\yii\web\Response
     */
    public function actionAdd()
    {
        $model=new Admin();
        $auth=\Yii::$app->authManager;
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->auth_key=\Yii::$app->security->generateRandomString();
                $model->password_hash=\Yii::$app->security->generatePasswordHash("$model->password_hash");
                $model->login_ip=ip2long(\Yii::$app->request->userIP);
                $model->save();

                $role=$auth->getRole($model->permArr);
//               var_dump($role);exit;
                $auth->assign($role, $model->id);

                //$auth->assign($permArr,$model->id);

                \Yii::$app->session->setFlash("success", "创建成功");
                return $this->redirect(['index']);
            }
        }

        $perm=$auth->getRoles();
//        var_dump($perm);exit;
        $permArr=ArrayHelper::map($perm,'name','description');
        //给用户分组
        //$auth->assign($permArr,$model->id);

//                $role=$auth->getRole();
//                $auth->assign($role,$admin->id);
        //添加权限完毕
        return $this->render('add',compact('model','permArr'));

    }
//    修改

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $model=Admin::findOne($id);
        $auth=\Yii::$app->authManager;
        $request=\Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                $model->auth_key=\Yii::$app->security->generateRandomString();
                $model->password_hash=\Yii::$app->security->generatePasswordHash("$model->password_hash");
                $model->login_ip=ip2long(\Yii::$app->request->userIP);
                $model->save();

                $role=$auth->getRole($model->permArr);
//               var_dump($role);exit;

                $auth->revokeAll($model->id);
                $auth->assign($role, $model->id);


                //$auth->assign($permArr,$model->id);

                \Yii::$app->session->setFlash("success", "修改成功");
                return $this->redirect(['index']);
            }
        }

        $perm=$auth->getRoles();
//        var_dump($perm);exit;
        $permArr=ArrayHelper::map($perm,'name','description');
        //给用户分组
        //$auth->assign($permArr,$model->id);

//                $role=$auth->getRole();
//                $auth->assign($role,$admin->id);
        //添加权限完毕
        return $this->render('add',compact('model','permArr'));


    }

//删除
    public function actionDel($id)
    {
        $model=Admin::findOne($id);
        $auth=\Yii::$app->authManager;
        $role=$auth->getRole($model->permArr);
//               var_dump($role);exit;
        $auth->revokeAll($model->id);
        if($model->delete()){
            \Yii::$app->session->setFlash("success", "删除成功");
            return $this->redirect(['index']);
        }
    }
        public function actionLogout()
        {
           if(\Yii::$app->user->logout()){
            return $this->redirect(['login']);
        }
        }

}
