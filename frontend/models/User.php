<?php

namespace frontend\models;

use function Sodium\compare;
use Yii;
use yii\web\IdentityInterface;

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
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $rePassword;
    public $captcha;
    public $checkCode;
    public $rememberMe;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }
    public function scenarios()
    {
        $parents= parent::scenarios();
        //定义一个login场景
        $parents['login']=['username','password','rememberMe'];
        $parents['reg']=['username','password','rePassword','captcha','checkCode','mobile'];
        return $parents;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','rePassword', 'mobile'], 'required'],
            ['rePassword','compare','compareAttribute' => 'password'],
            [['mobile'],'match','pattern' => '/0?(13|14|15|17|18|19)[0-9]{9}/','message' => '手机号不正确'],
            [['captcha'],'validateCaptcha'],//自定义
            [['username'], 'unique','on' => 'reg'],
            ['checkCode','captcha','captchaAction' =>'/user/captcha' ],
            [['rememberMe'],'safe','on' => 'login']
        ];
    }
    public function validateCaptcha($attribute, $params)
    {
        //1. 根据手机号得到对应的Session值
        $code= Yii::$app->session->get('tel_'.$this->mobile);

        //2. 根据当前验证码是否和session值一琶

        if ($code!=$this->captcha){

            //3.添加错误提示
           $this->addError($attribute,"验证码错误");
        }

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

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
       return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
       return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key===$authKey;
    }
}
