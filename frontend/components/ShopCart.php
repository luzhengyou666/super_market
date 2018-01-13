<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/12 0012
 * Time: 下午 3:24
 */

namespace frontend\components;


use frontend\models\Cart;
use yii\base\Component;
use yii\web\Cookie;

class ShopCart extends Component
{
    //定义$_cart用来存储COOKie中购物车数据
    private $_cart = [];

    public function __construct(array $config = [])
    {
//从COOKie中取出购物车数据，如果没有则赋值为[]
        $this->_cart = \Yii::$app->request->cookies->getValue('cart', []);
        parent::__construct($config);
    }

    public function init()
    {

        parent::init();
    }

    //增
    public function add($id, $amount)
    {

        if (array_key_exists($id, $this->_cart)) {
            //已经存在商品 执行修改加操作
            $this->_cart[$id] = $this->_cart[$id] + $amount;

        } else {
            //商品在购物车中不存在 执行新增操作
            $this->_cart[$id] = (int)$amount;
        }

        //返回当前对象
        return $this;

    }

    //删
    public function del($id)
    {
        unset($this->_cart[$id]);

        return $this;
    }

    //改
    public function update($id, $amount)
    {

        if (isset($this->_cart[$id])) {
            $this->_cart[$id] = $amount;
        }

        return $this;

    }

    //查

    public function get()
    {
        return $this->_cart;
    }

    //清空购物车
    public function flush(){

        $this->_cart=[];
        return $this;

    }

    //保存
    public function save()
    {

        //1.1 得到设置COOKie的对象
        $setCookie = \Yii::$app->response->cookies;

        //1.2 生成一个COOKie对象
        $cookie = new Cookie([
            'name' => 'cart',
            'value' => $this->_cart,
            'expire' => time() + 3600 * 24 * 30 * 12

        ]);
        //1.3 利用$setCookie添加一个Cookie对象
        $setCookie->add($cookie);
    }

    //本地数据同步到数据库
    public function synDb(){

        foreach ($this->_cart as $goodId=>$amount){

            //判断当前商品在数据库中是否存在
            $userId=\Yii::$app->user->id;//用户Id
            //取出商品Id对应购物车数据

            $cart=Cart::findOne(['goods_id'=>$goodId,'user_id'=>$userId]);

            if ($cart) {
                //如果存在 修改+$amount
                $cart->amount+=$amount;
                $cart->save();

            }else{
                //新增
                $cart=new Cart();
                $cart->amount=$amount;
                $cart->goods_id=$goodId;
                $cart->user_id=$userId;
                $cart->save();
            }



        }


    }
}