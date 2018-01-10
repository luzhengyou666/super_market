<?php

namespace frontend\models;

use function Sodium\compare;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $mobile
 * @property integer $login_ip
 */
class User extends \yii\db\ActiveRecord
{
    public $password;
    public $rePassword;
    public $captcha;
    public $checkCode;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','rePassword', 'mobile'], 'required'],
            ['rePassword','compare','compareAttribute' => 'password'],
            [['username'], 'unique'],
            ['checkCode','captcha','captchaAction' => '/user/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'mobile' => 'Mobile',
            'login_ip' => 'Login Ip',
        ];
    }
}
