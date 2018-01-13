<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;
use frontend\components\ShopCart;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionLists($id)
    {

        $cate=Category::findOne($id);
        //var_dump($cate);exit();
        $cateSons=Category::find()
            ->where(['tree'=>$cate->tree])
            ->andWhere("lft>={$cate->lft}")
            ->andWhere("rgt<={$cate->rgt}")
            ->asArray()
            ->all();
//        var_dump($cateSons);exit;
        $cateIds=array_column($cateSons,'id');

        $goods=Goods::find()
            ->where(['in','category_id',$cateIds])
            ->andWhere(['status'=>1])
            ->all();

       return $this->render('lists',compact('goods'));
    }


    public function actionDetail($id)
    {


        //1.找出当前的商品
        $good = Goods::findOne($id);

        //var_dump($good->images);exit;

        //2.显示视图
        return $this->render("detail", compact('good'));

    }

    /**
     * 添加购物车
     * @param $id
     * @param $amount
     */
    public function actionAddCart($id, $amount)
    {

        //未登录 存Cookie
        if (\Yii::$app->user->isGuest) {

            //1.怎么存？  ['3'=>10,'4'=>6,'$id'=$amount]
            //2.1 取出Cookie中购物车数据
            //  $cartOld = \Yii::$app->request->cookies->getValue('cart', []);

            //$cartOld=$cartOld?$cartOld:[];
            //$cartOld=[2=>3,3=>11,4=>6]  $cartOld[3]=5+6

            //2.2 判断$cartOld里有没有当前商品Id这个key值
            // var_dump(array_key_exists($id,$cartOld));exit;

            /* if (isset($cartOld[$id])){
                 $cartOld[$id] = $cartOld[$id] + $amount;
             }else{
                 $cartOld[$id]=$amount;
             }*/
            /* if (array_key_exists($id, $cartOld)) {
                 //已经存在商品 执行修改加操作
                 $cartOld[$id] = $cartOld[$id] + $amount;

             } else {
                 //商品在购物车中不存在 执行新增操作
                 $cartOld[$id] = (int)$amount;


             }

             //  var_dump($cartOld);exit;

             //1.1 得到设置COOKie的对象
             $setCookie = \Yii::$app->response->cookies;

             //1.2 生成一个COOKie对象
             $cookie = new Cookie([
                 'name' => 'cart',
                 'value' => $cartOld,
                 'expire' => time()+3600*24*30*12,

             ]);

             //1.3 利用$setCookie添加一个Cookie对象

             $setCookie->add($cookie);*/

            //实例化购物车对象
            $shopCart = new ShopCart();
            //执行添加操作 链式调用 return $this    return this
            $shopCart->add($id, $amount)->save();
            //  (new ShopCart())->add($id,$amount)->save();

            //配置组件，调用 一般不推荐  没有提示
            //\Yii::$app->shopCart->add($id,$amount)->save();


            //$shopCart->save();





        } else {
            // 已登录 存数据库  id goods_id  amount user_id


            //判断当前商品在数据库中是否存在
            $userId=\Yii::$app->user->id;//用户Id
            //取出商品Id对应购物车数据
            $cart=Cart::findOne(['goods_id'=>$id,'user_id'=>$userId]);

            if ($cart) {
                //如果存在 修改+$amount
                $cart->amount+=$amount;
                $cart->save();

            }else{
                //新增
                $cart=new Cart();
                $cart->amount=$amount;
                $cart->goods_id=$id;
                $cart->user_id=$userId;
                $cart->save();
            }





        }
        return $this->redirect("cart-lists");

    }

    /**
     * 购物车列表页
     */
    public function actionCartLists()
    {
        //1.判断是否登录
        if (\Yii::$app->user->isGuest) {
            //未登录 COOKie
            $cart = \Yii::$app->request->cookies->getValue('cart', []);

            //1.2 取出所有商品Id，也就是Cookie购物车里的键

            $goodIds = array_keys($cart);

            //1.3 通过商品Id把所有商品取出来

            $goods = Goods::find()->where(['in', 'id', $goodIds])->asArray()->all();

            foreach ($goods as $k => $good) {
                $goods[$k]['num'] = $cart[$good['id']];
                //  $goods[1]['num']=$cart[$good['id']];
            }
            //1.4 视图


            // var_dump($goods);
            // exit;


        } else {
            //登录 数据库

            $userId=\Yii::$app->user->id;
            $cart=Cart::find()->where(['user_id'=>$userId])->asArray()->all();

//1.2 取出所有商品Id，也就是Cookie购物车里的键

            $cartGoods=ArrayHelper::map($cart,'goods_id','amount');
            //  var_dump($cartGoods);exit;
            //提取所有商品Id
            $goodIds = array_column($cart,'goods_id');

            // var_dump($goodIds);exit;
            //1.3 通过商品Id把所有商品取出来

            $goods = Goods::find()->where(['in', 'id', $goodIds])->asArray()->all();

            foreach ($goods as $k => $good) {
                //追加购物车每个商品数量
                $goods[$k]['num'] = $cartGoods[$good['id']];
                //  $goods[1]['num']=$cart[$good['id']];
            }
        }


        return $this->render('cart-lists', compact('goods'));

    }

    /**
     * 更新购物车数据
     * @param $id
     * @param $amount
     */

    public function actionUpdateCart($id, $amount)
    {
        if (\Yii::$app->user->isGuest) {

            $shopCart = new ShopCart();
            $shopCart->update($id, $amount);
            $shopCart->save();

            //1. 取出购物车数据库
            /* $cart=\Yii::$app->request->cookies->getValue('cart',[]);

             $cart[$id]=$amount;


             //1.1 得到设置COOKie的对象
             $setCookie = \Yii::$app->response->cookies;

             //1.2 生成一个COOKie对象
             $cookie = new Cookie([
                 'name' => 'cart',
                 'value' => $cart

             ]);

             //1.3 利用$setCookie添加一个Cookie对象

             $setCookie->add($cookie);

             return 1;*/

        } else {
            //数据库

        }


    }

    public function actionDelCart($id)
    {

        if (\Yii::$app->user->isGuest) {
            //1. 取出购物车数据库
            $cart = \Yii::$app->request->cookies->getValue('cart', []);

            unset($cart[$id]);


            //1.1 得到设置COOKie的对象
            $setCookie = \Yii::$app->response->cookies;

            //1.2 生成一个COOKie对象
            $cookie = new Cookie([
                'name' => 'cart',
                'value' => $cart

            ]);

            //1.3 利用$setCookie添加一个Cookie对象

            $setCookie->add($cookie);

            return 1;

        } else {
            //数据库

        }


    }

    public function actionTest()
    {


        var_dump(\Yii::$app->request->cookies->getValue("cart"));

    }
}
