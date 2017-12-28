<?php
/* @var $this yii\web\View */
?>
<h1>商品分类列表</h1>

<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($cates as $cate):?>
        <tr class="cate_tr" data-tree="<?=$cate->tree?>" data-lft="<?=$cate->lft?>"
            data-rgt="<?=$cate->rgt?>">
            <td><?=$cate->id?></td>
            <td><span class="glyphicon glyphicon-plus"></span><?=$cate->nameText?></td>
            <td><?=$cate->intro?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$cate->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$cate->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
/* @var $this yii\web\View */
//定义JS
$js = <<<JS
$(".cate_tr").click(function(){

var tr=$(this);
//隐藏图标
tr.find("span").toggleClass("glyphicon glyphicon-plus");
tr.find("span").toggleClass("glyphicon glyphicon-minus");

var lft_parent=tr.attr('data-lft');//选中的lft
var rgt_parent=tr.attr('data-rgt');//选中的右值
var tree_parent=tr.attr('data-tree');//选中的右值

console.log(lft_parent,rgt_parent,tree_parent);
// 当前类的左值 右值 树
$(".cate_tr").each(function(k,v){

var lft=$(v).attr('data-lft');//当前的lft
var rgt=$(v).attr('data-rgt');//当前的右值
var tree=$(v).attr('data-tree');//当前的右值
console.log($(v).attr('data-lft'));
//循环判断 当前tr的左值大于选中的那个左值  右值小于选中的那个右值 树等于选中那个树  +（有字符串出现往字符串，其它统率往数子转） 其它统统往数子转  lft rgt 都是字符串   2 < 15
if(tree==tree_parent && lft-lft_parent>0 && rgt-rgt_parent<0){

//判断父类是不是展开状态
 if (tr.find('span').hasClass('glyphicon glyphicon-minus')){
      $(v).find('span').removeClass('glyphicon glyphicon-plus');
    $(v).find('span').addClass('glyphicon glyphicon-minus');
     $(v).hide();
     
 }else {
     //是闭合状态
      $(v).find('span').removeClass('glyphicon glyphicon-minus');
    $(v).find('span').addClass('glyphicon glyphicon-plus');
$(v).show();
 }
}
});
console.dir(this);
});
JS;
//注意JS
$this->registerJs($js);

?>
