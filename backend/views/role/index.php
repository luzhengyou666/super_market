<?php
/* @var $this yii\web\View */
?>
<h1>角色列表</h1>
<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role): ?>
        <tr>
            <td>
             <?=$role->name ?>
            </td>

            <td><?= $role->description?></td>
            <td>
                <?php
                $auth = \Yii::$app->authManager;
                //  var_dump( $auth->getPermissionsByRole($role->name));
                foreach ($auth->getPermissionsByRole($role->name) as $permission){
                    echo $permission->description."||";
                }
                ?>
            </td>

            <td><a href="<?= \yii\helpers\Url::to(['edit', 'name' => $role->name]) ?>" class="btn btn-success">编辑</a>
                <?= \yii\bootstrap\Html::a("删除", ['del', 'name' => $role->name], ["class" => "btn btn-danger"]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


