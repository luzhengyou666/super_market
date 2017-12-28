<?php

namespace backend\controllers;

use backend\models\Category;
use function Sodium\compare;
use yii\db\Exception;
use yii\helpers\Json;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //得到所有数据
        $cates=Category::find()->orderBy('tree,lft')->all();

        return $this->render('index',compact('cates'));
    }
    //增加
    public function actionAdd()
    {
        //new 模型对象
        $model=new Category();
//        找到所有分类
        $cates=Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级目录','parent_id'=>0];
        $cates=Json::encode($cates);
        //判断
        $request=\Yii::$app->request;
        //如果是post提交接受参数
        if ($request->isPost) {
//            绑定数据
            $model->load($request->post());
//           后端验证
            if ($model->validate()) {

                if($model->parent_id==0){
                    //1.parent_id=0创建一级分类
                $model->makeRoot();

                    //提示
                    \Yii::$app->session->setFlash("success","添加一级分类".$model->name."成功");
                    return $this->redirect(['index']);
                }else{
//            2.否则追加对应的父类
                    //1找到父节点
                $cateParent=Category::findOne($model->parent_id);
                //2创建一个新节点
//                $cate=new Category();
//                $cate->name='dianq';
//                $cate->parent_id=$cateParent->id;
//        3把新节点加入到父节点
                $model->prependTo($cateParent);
                    \Yii::$app->session->setFlash("success","把".$model->name."添加到".$cateParent->name."成功");
                    return $this->redirect(['index']);
                }
//                刷新
                return $this->refresh();
            }

        }
        return $this->render('add', ['model' => $model,'cates'=>$cates]);
    }
//    增加完毕
    //修改
    public function actionEdit($id)
    {
        //new 模型对象
        $model=Category::findOne($id);
//        找到所有分类
        $cates=Category::find()->asArray()->all();
        $cates[]=['id'=>0,'name'=>'一级目录','parent_id'=>0];
        $cates=Json::encode($cates);
        //判断
        $request=\Yii::$app->request;
        //如果是post提交接受参数
        if ($request->isPost) {
//            绑定数据
            $model->load($request->post());
//           后端验证
            if ($model->validate()) {
                //捕获异常
                try{
//                执行这里面的所有代码，一旦发生错误，跳到catch执行
                    if($model->parent_id==0){
                        //1.parent_id=0创建一级分类
//                    $model->makeRoot();
                        $model->save();

                        //提示
                        \Yii::$app->session->setFlash("success","修改一级分类".$model->name."成功");
                        return $this->redirect(['index']);
                    }else{
//            2.否则追加对应的父类
                        //1找到父节点
                        $cateParent=Category::findOne($model->parent_id);
                        //2创建一个新节点
//                $cate=new Category();
//                $cate->name='dianq';
//                $cate->parent_id=$cateParent->id;
//        3把新节点加入到父节点
                        $model->prependTo($cateParent);
                        \Yii::$app->session->setFlash("success","把".$model->name."添加到".$cateParent->name."成功");
                        return $this->redirect(['index']);
                    }

                }catch (Exception $exception){
//                    var_dump($exception->getMessage());exit;
                    \Yii::$app->session->setFlash("danger",$exception->getMessage());
                    return $this->refresh();

                }


//                刷新
                return $this->refresh();
            }

        }
        return $this->render('add', ['model' => $model,'cates'=>$cates]);
    }
//    修改完毕
//删除
    public function actionDel($id)
    {
        if (Category::findOne($id)->deleteWithChildren()) {
            \Yii::$app->session->setFlash('danger','删除分类成功');
            return $this->redirect(['index']);
        }
    }
}
