<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180110_151516_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment("用户ID"),
            'name'=>$this->string(20)->comment("姓名"),
            'province'=>$this->string(20)->comment("省份"),
            'city'=>$this->string(20)->comment("市"),
            'county'=>$this->string(20)->comment("区县"),
            'address'=>$this->string()->comment("地址"),
            'mobile'=>$this->string()->comment("手机号"),
            'status'=>$this->smallInteger()->defaultValue(0)->comment("状态：1默认 0非默认")

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
