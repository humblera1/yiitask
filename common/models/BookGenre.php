<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "books_genres".
 *
 * @property int $books_id
 * @property int $genres_id
 *
 * @property Books $books
 * @property Genres $genres
 */
class BookGenre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books_genres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['books_id', 'genres_id'], 'required'],
            [['books_id', 'genres_id'], 'integer'],
            [['books_id', 'genres_id'], 'unique', 'targetAttribute' => ['books_id', 'genres_id']],
            [['books_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['books_id' => 'id']],
            [['genres_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genres::class, 'targetAttribute' => ['genres_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'books_id' => 'Books ID',
            'genres_id' => 'Genres ID',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BooksQuery
     */
    public function getBooks()
    {
        return $this->hasOne(Books::class, ['id' => 'books_id']);
    }

    /**
     * Gets query for [[Genres]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\GenresQuery
     */
    public function getGenres()
    {
        return $this->hasOne(Genres::class, ['id' => 'genres_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BookGenreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookGenreQuery(get_called_class());
    }
}
