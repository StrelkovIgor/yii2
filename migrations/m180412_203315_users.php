<?php

use yii\db\Migration;

/**
 * Class m180412_203315_users
 */
class m180412_203315_users extends Migration
{
    /**
     * {@inheritdoc}
     */
  /*  public function safeUp()
    {

    }
*/
    /**
     * {@inheritdoc}
     */
/*    public function safeDown()
    {
        echo "m180412_203315_users cannot be reverted.\n";

        return false;
    }
*/
    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
		$this->createTable('users',[
			'id'			=> $this->primaryKey(),
			'login'			=> $this->string(64)->notNull()->unique(),
			'email'			=> $this->string(64)->notNull()->unique(),
			'password'		=> $this->string(128)->notNull(),
			'accessToken'	=> $this->string(128),
			'referral_key'	=> $this->string(32),
			'referral_id'	=> $this->integer(11)->defaultValue(0)
		]);
		
		$this->createIndex(
            'index-referral_id-id',
            'users',
            'referral_id'
        );
    }

    public function down()
    {
        $this->dropTable('users');
		
    }
    
}
