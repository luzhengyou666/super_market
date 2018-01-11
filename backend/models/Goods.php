<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property string $category_id
 * @property string $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property string $stock
 * @property integer $status
 * @property integer $sort
 * @property string $create_at
 */
class Goods extends \yii\db\ActiveRecord
{
    public $imgFiles;//显示多图
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'logo', 'category_id', 'brand_id', 'market_price', 'shop_price', 'stock','status','sort'], 'required'],
            [['market_price', 'shop_price'], 'number'],
            [['sn','imgFiles'],'safe']


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'sn' => '货号',
            'logo' => '商品logo',
            'category_id' => '商品分类id',
            'brand_id' => '品牌分类ID',
            'market_price' => '市场价格',
            'shop_price' => '本店价格',
            'stock' => '库存',
            'status' => '1=正常 0=回收站',
            'sort' => '排序',
            'create_at' => 'Create At',
        ];
    }

    public function getIntro(){
    return $this->hasOne(GoodsIntro::className(),['goods_id'=>'id']);


    }

    public function getImages(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);

    }
}
