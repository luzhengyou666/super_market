<?php
/* @var $this yii\web\View */
?>
<h1>文章分类</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <th>Id</th>
        <th>名称</th>
        <th>状态</th>
        <th>排序</th>
        <th>简介</th>
        <th>帮助类别</th>
    </tr>
    <?php foreach ($articleCategorys as $articleCategory):?>
        <tr>
            <td><?=$articleCategory->id?></td>
            <td><?=$articleCategory->name?></td>
            <td><?=$articleCategory->status?></td>
            <td><?=$articleCategory->sort?></td>
            <td><?=$articleCategory->intro?></td>
            <td><?=$articleCategory->is_help?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$articleCategory->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$articleCategory->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
