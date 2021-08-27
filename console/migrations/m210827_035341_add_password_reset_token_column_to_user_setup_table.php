<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_setup}}`.
 */
class m210827_035341_add_password_reset_token_column_to_user_setup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Yii::$app->params['DBCompanyName'].'User Setup ', 'password_reset_token', $this->string(256));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_setup}}', 'password_reset_token');
    }
}
