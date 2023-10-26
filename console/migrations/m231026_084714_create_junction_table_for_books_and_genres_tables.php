<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books_genres}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%books}}`
 * - `{{%genres}}`
 */
class m231026_084714_create_junction_table_for_books_and_genres_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_genres}}', [
            'books_id' => $this->integer(),
            'genres_id' => $this->integer(),
            'PRIMARY KEY(books_id, genres_id)',
        ]);

        // creates index for column `books_id`
        $this->createIndex(
            '{{%idx-books_genres-books_id}}',
            '{{%books_genres}}',
            'books_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-books_genres-books_id}}',
            '{{%books_genres}}',
            'books_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        // creates index for column `genres_id`
        $this->createIndex(
            '{{%idx-books_genres-genres_id}}',
            '{{%books_genres}}',
            'genres_id'
        );

        // add foreign key for table `{{%genres}}`
        $this->addForeignKey(
            '{{%fk-books_genres-genres_id}}',
            '{{%books_genres}}',
            'genres_id',
            '{{%genres}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-books_genres-books_id}}',
            '{{%books_genres}}'
        );

        // drops index for column `books_id`
        $this->dropIndex(
            '{{%idx-books_genres-books_id}}',
            '{{%books_genres}}'
        );

        // drops foreign key for table `{{%genres}}`
        $this->dropForeignKey(
            '{{%fk-books_genres-genres_id}}',
            '{{%books_genres}}'
        );

        // drops index for column `genres_id`
        $this->dropIndex(
            '{{%idx-books_genres-genres_id}}',
            '{{%books_genres}}'
        );

        $this->dropTable('{{%books_genres}}');
    }
}
