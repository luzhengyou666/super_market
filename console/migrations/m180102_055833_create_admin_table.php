<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180102_055833_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('令牌'),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment("0=禁用 1=激活"),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
            'login_at'=>$this->integer()->notNull()->comment('登陆时间'),
            'login_ip'=>$this->integer()->notNull()->comment('登陆ip')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
