<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsGallery;
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
          $good=Goods::findOne($id);
//

          return $this->render('detail',compact('good'));
        }

        public function actionAddCart($id,$amount)
        {
            //未登录 存Cookie
            if (\Yii::$app->user->isGuest) {
                //1.怎么存？  ['3'=>10,'4'=>6,'$id'=$amount]
                //2.1 取出Cookie中购物车数据
                $cartOld = \Yii::$app->request->cookies->getValue('cart', []);

                //$cartOld=$cartOld?$cartOld:[];

                //2.2 判断$cartOld里有没有当前商品Id这个key值
                // var_dump(array_key_exists($id,$cartOld));exit;
                if (array_key_exists($id, $cartOld)) {
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
                    'value' => $cartOld
                ]);
                //1.3 利用$setCookie添加一个Cookie对象
                $setCookie->add($cookie);
                return $this->redirect("cart-lists");


            } else {
                // 已登录 存数据库


            }

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


        }


        return $this->render('cart-lists',compact('goods'));

    }

    /**
     * 更新购物车数据
     * @param $id
     * @param $amount
     */

    public function actionUpdateCart($id,$amount){
        if (\Yii::$app->user->isGuest) {
            //1. 取出购物车数据库
            $cart=\Yii::$app->request->cookies->getValue('cart',[]);

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

            return 1;

        }else{
            //数据库

        }



    }

    public function actionDelCart($id){

        if (\Yii::$app->user->isGuest) {
            //1. 取出购物车数据库
            $cart=\Yii::$app->request->cookies->getValue('cart',[]);

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
        }else{
            //数据库
        }

    }

    public function actionTest()
    {


        var_dump(\Yii::$app->request->cookies->getValue("cart"));

    }

}
