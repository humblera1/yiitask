<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%author}}`
 */
class m231026_084511_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->string(255),
            'author_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            '{{%idx-books-author_id}}',
            '{{%books}}',
            'author_id'
        );

        // add foreign key for table `{{%author}}`
        $this->addForeignKey(
            '{{%fk-books-author_id}}',
            '{{%books}}',
            'author_id',
            '{{%authors}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%author}}`
        $this->dropForeignKey(
            '{{%fk-books-author_id}}',
            '{{%books}}'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            '{{%idx-books-author_id}}',
            '{{%books}}'
        );

        $this->dropTable('{{%books}}');
    }
}
