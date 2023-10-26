<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m231026_075101_create_admin_entity
 */
class m231026_075101_create_admin_entity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $password = YII_ENV_DEV ? 'trial' : Yii::$app->security->generateRandomString(8);
        $passwordHash = Yii::$app->security->generatePasswordHash($password);
        $this->insert('{{%admin}}', [
            'username' => 'admin',
            'password_hash' => $passwordHash,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        echo "Пароль от администратора: $password" . PHP_EOL;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%admin}}', ['username' => 'admin']);
    }
}
