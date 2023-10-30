<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "books_genres".
 *
 * @property int $books_id
 * @property int $genres_id
 *
 * @property Book $books
 * @property Genre $genres
 */
class BookGenre extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%books_genres}}';
    }

    public function rules(): array
    {
        return [
            [['books_id', 'genres_id'], 'required'],
            [['books_id', 'genres_id'], 'integer'],
            [['books_id', 'genres_id'], 'unique', 'targetAttribute' => ['books_id', 'genres_id']],
            [['books_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['books_id' => 'id']],
            [['genres_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genre::class, 'targetAttribute' => ['genres_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'books_id' => 'Books ID',
            'genres_id' => 'Genres ID',
        ];
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'books_id']);
    }

    public function getGenres(): ActiveQuery
    {
        return $this->hasOne(Genre::class, ['id' => 'genres_id']);
    }
}
