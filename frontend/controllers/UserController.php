<?php

namespace frontend\controllers;

use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRegist()
    {

        $request=\Yii::$app->request;
        if ($request->isPost) {
//            var_dump($request->post());

            $user=new User();
            $user->load($request->post());
            if ($user->validate()) {
                $user->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
                $user->auth_key=\Yii::$app->security->generateRandomString();
                if ($user->save(false)){
                    return Json::encode(
                      [
                       'status'=>1,
                        ' msg'=>'注册成功',
                          'data'=>null
                      ]
                    );
                }
            }
            return Json::encode([
                'status'=>0,
                ' msg'=>'注册失败',
                'data'=>$user->errors
            ]);
//                var_dump($user->errors);exit;
//            $user->username=$request->post('username');
//            $user->password_hash=\Yii::$app->security->generatePasswordHash($request->post('password'));
//            $user->mobile=$request->post('tel');
//            if ($user->save()) {
//                return 1;
//            }else{
//               var_dump($user->errors);exit;
//            }
        }
        return $this->render('regist');
    }
    public function actionSms($mobile){

        $code=rand(100000,999999);
        $config = [
            'access_key' => 'your access key',
            'access_secret' => 'your access secret',
            'sign_name' => 'your sign name',
        ];

//        $aliSms = new Mrgoon\AliSms\AliSms();
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile, 'tempplate code', ['code'=>$code], $config);

        \Yii::$app->session->set($mobile,$code);


        return $code;
    }

}
