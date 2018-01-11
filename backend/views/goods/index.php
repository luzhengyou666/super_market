<?php
/* @var $this yii\web\View*/
 /* @var $model backend\models\Goods*/
?>
    <h1>商品列表</h1>

    <div class="row">
        <div class="pull-left">
            <a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
        </div>
        <div class="pull-right">
            <form class="form-inline">
                <div class="form-group">
                    <input type="text" size="3" class="form-control" name='minPrice' placeholder="最低价" value="<?=Yii::$app->request->get('minPrice')?>">
                </div>
                -
                <div class="form-group">
                    <input type="text" size="3" class="form-control" name="maxPrice" placeholder="最高价" value="<?=Yii::$app->request->get('maxPrice')?>">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="keyword" placeholder="请输入名称或货号" value="<?=Yii::$app->request->get('keyword')?>">
                </div>
                <button type="submit" class="btn btn-default">搜索</button>
            </form>
        </div>
    </div>


    <table class="table">

        <tr>
            <th>Id</th>
            <th>name</th>
            <th>sn</th>
            <th>logo</th>
            <th>分类</th>
            <th>品牌</th>
            <th>价格</th>
            <th>库存</th>
            <th>状态</th>
            <th>排序</th>
            <th>时间</th>
            <th>操作</th>
        </tr>

        <?php foreach ($models as $model):?>
            <tr>
                <td><?=$model->id?></td>
                <td><?=$model->name?></td>
                <td><?=$model->sn?></td>
                <td><?=\yii\bootstrap\Html::img($model->logo,['height'=>50])?></td>
                <td><?=$model->category_id?></td>
                <td><?=$model->brand_id?></td>
                <td><?=$model->shop_price?></td>
                <td><?=$model->stock?></td>
                <td><?=$model->status?></td>
                <td><?=$model->sort?></td>
                <td><?=$model->create_at?></td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                    <a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
                </td>
            </tr>
        <?php endforeach;?>


    </table>
<?=\yii\widgets\LinkPager::widget(
    ['pagination' => $pages]
)?>