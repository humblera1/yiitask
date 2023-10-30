<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "genres".
 *
 * @property int $id
 * @property string $name
 *
 * @property Book[] $books
 * @property BookGenre[] $booksGenres
 */
class Genre extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%genres}}';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'books',
        ];
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'books_id'])->viaTable('books_genres', ['genres_id' => 'id']);
    }

    public function getBookGenre(): ActiveQuery
    {
        return $this->hasMany(BookGenre::class, ['genres_id' => 'id']);
    }
}
