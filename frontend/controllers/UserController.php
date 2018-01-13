<?php

namespace frontend\controllers;

use frontend\components\ShopCart;
use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
public $enableCsrfValidation=false;
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

            //  var_dump($request->post());
            //添加用户
            $user=new User();

            //给user绑定场景
            $user->setScenario('reg');

            //数据绑定
            $user->load($request->post());

            //后台验证
            if ($user->validate()){
                //保存数据
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                $user->auth_key=\Yii::$app->security->generateRandomString();
                if ($user->save(false)) {
                    //返回数据
                    return Json::encode(
                        [
                            'status'=>1,
                            'msg'=>"注册成功",
                            'data'=>null
                        ]
                    );
                }
            }
            return Json::encode( [
                'status'=>0,
                'msg'=>"注册失败",
                'data'=>$user->errors
            ]);
        }
        return $this->render('regist');
    }
    public function actionSms($mobile){

        $code=rand(100000,999999);
        $config = [
            'access_key' => 'LTAISL7EYz0AMtu2',
            'access_secret' => '3aTPoSQ9kthUbcYLBRfSila1NLipVB',
            'sign_name' => '陆政佑',
        ];

//        $aliSms = new Mrgoon\AliSms\AliSms();
        $aliSms = new AliSms();
        $response = $aliSms->sendSms($mobile, 'SMS_120376203', ['code'=>$code],
            $config);
        var_dump($response);
        \Yii::$app->session->set($mobile,$code);
        return $code;
    }



 public function actionLogin($back='index/index'){

     $request=\Yii::$app->request;
     if ($request->isPost) {
         //创建对象
         $model=new User();
         $model->scenario="login";
         // 绑定数据
         $model->load($request->post());

//         var_dump($model->rememberMe);exit;

         //后台验证

         if ($model->validate()) {
             //1. 找到用户对象
             $user=User::findOne(['username'=>$model->username]);

             //2. 判断用户是否存在  // 3.判断密码是否正确
             if ($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)) {
                 //用户登录
                 \Yii::$app->user->login($user,$model->rememberMe?3600*24*7:0);


                 //登录成功之后 本地购物车数据同步到数据库
                 $shopCart = new ShopCart();
                 $shopCart->synDb();//同步到数据库
                 $shopCart->flush()->save();//清空本地购物车数据
                 return $this->redirect([$back]);
             }else{
                 //密码错误或者用户名不存在
                 echo "账号或密码错误";exit;
             }
         }
     }
     return $this->render('login');
 }



    public function actionCheck($tel){
        //验证验证码是否正确
        //1 根据手机号取对应的验证
        $code= \Yii::$app->session->get($tel);
        return $code;
    }

    public function actionLogout()
    {
        if (\Yii::$app->user->logout()) {

            return $this->redirect(['user/login']);

        }

    }














}
