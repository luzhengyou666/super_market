<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/2 0002
 * Time: 下午 3:31
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;


    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['rememberMe'],'safe']

        ];
    }
    public function attributes()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记录信息'

        ];
    }
}