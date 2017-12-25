<?php
/* @var $this yii\web\View */
?>
<h1>文章列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <th>Id</th>
        <th>标题</th>
        <th>简介</th>
        <th>状态</th>
        <th>排序</th>
        <th>分类</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->title?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->status?></td>
            <td><?=$article->sort?></td>
            <td><?=$article->cate_id?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$article->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$article->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
