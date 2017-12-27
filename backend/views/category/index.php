<?php
/* @var $this yii\web\View */
?>
<h1>category/index</h1>

<a href="<?=\yii\helpers\Url::to(['add'])?> " class="btn btn-info glyphicon glyphicon-plus"></a>
<table class="table">
    <tr>
        <td>展示|隐藏</td>
        <td>id</td>
        <td>名称</td>
        <td>左值</td>
        <td>右值</td>
        <td>parent_id</td>
        <td>深度</td>
        <td>树</td>
        <td>简介</td>
        <td>操作</td>

    </tr>
    <?php foreach ($categorys as $category):?>
        <tr lft="<?=$category->lft?>" rgt="<?=$category->rgt?>" tree="<?=$category->tree?>">
            <td><span class="glyphicon glyphicon-plus cate"></span></td>
            <td><?=$category['id']?></td>
            <td><?=str_repeat("&nbsp;",$category->depth*5).$category['name']?></td>
            <td><?=$category['lft']?></td>
            <td><?=$category['rgt']?></td>
            <td><?=$category['parent_id']?></td>
            <td><?=$category['depth']?></td>
            <td><?=$category['tree']?></td>
            <td><?=$category['intro']?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$category->id])?>" class="btn btn-info glyphicon glyphicon-edit"></a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$category->id])?>" class="btn btn-danger glyphicon glyphicon-trash"></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
/* @var $this yii\web\View */
$js=<<<EOF
    $(".cate").click(function(){
        //换图片
       $(this).toggleClass("glyphicon-minus-sign");
       $(this).toggleClass("glyphicon-plus-sign");
        //找到对应的tr
        var tr=$(this).parent().parent();
        var lft=tr.attr('lft');
        var rgt=tr.attr('rgt');
        var tree=tr.attr('tree');
        //console.dir(lft);
        //得到所有的tr
        var trs=$("tr");
        //根据左值右值来判定 左边好判断（递增） 右边（反之） 还要满足一颗树
        $.each(trs,function(k,v){
            var lftPer=$(v).attr('lft');
            var rgtPer=$(v).attr('rgt');
            var treePer=$(v).attr('tree');            
         if(lftPer - lft>0 && rgtPer - rgt<0 && tree==treePer){ 
            $(v).toggle();
          } 
            
        })
        
    });

EOF;
$this->registerJs($js);
?>
