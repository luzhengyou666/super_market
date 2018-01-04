<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m171229_055124_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment("名称"),
            'sn'=>$this->string(20)->notNull()->comment("货号"),
            'logo'=>$this->string()->notNull()->comment("商品logo"),
            'category_id'=>$this->integer()->unsigned()->notNull()->comment("商品分类id"),
            'brand_id'=>$this->integer()->unsigned()->notNull()->comment("品牌分类ID"),
            'market_price'=>$this->decimal(10,0)->notNull()->comment("市场价格"),
            'shop_price'=>$this->decimal(10,0)->notNull()->comment("本店价格"),
            'stock'=>$this->integer()->unsigned()->notNull()->comment("库存"),
            'status'=>$this->smallInteger()->notNull()->defaultValue(1)->comment("1=正常 0=回收站"),
            'sort'=>$this->smallInteger()->notNull()->defaultValue(100)->comment("排序"),
            'create_at'=>$this->integer()->unsigned()
        ]);
        $this->createTable('goods_intro',[
            'goods_id'=>$this->primaryKey(),
            'content'=>$this->text()->comment('商品详情')
        ]);
        $this->createTable('goods_gallery',[
            'id'=>$this->primaryKey(),
            'goods_id'=>$this->integer()->unsigned()->comment('商品id'),
            'path'=>$this->string()->notNull()->comment('图片地址')



        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
        $this->dropTable('goods_intro');
        $this->dropTable('goods_gallery');
    }
}
