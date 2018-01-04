<?php
/* @var $this yii\web\View */
?>
<h1>注册列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <th>Id</th>
        <th>用户名</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>登陆时间</th>
        <th>登陆ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
        <tr>
            <td><?=$admin->id?></td>
            <td><?=$admin->username?></td>
            <td><?=$admin->status?></td>
            <td><?=date('Y-m-d H:i:s',$admin->created_at)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->updated_at)?></td>
            <td><?=date('Y-m-d H:i:s',$admin->login_at)?></td>
            <td><?=$admin->login_ip?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$admin->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$admin->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

