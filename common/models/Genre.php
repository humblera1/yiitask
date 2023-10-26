<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "genres".
 *
 * @property int $id
 * @property string $name
 *
 * @property Books[] $books
 * @property BooksGenres[] $booksGenres
 */
class Genre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BookQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'books_id'])->viaTable('books_genres', ['genres_id' => 'id']);
    }

    /**
     * Gets query for [[BooksGenres]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BooksGenresQuery
     */
    public function getBooksGenres()
    {
        return $this->hasMany(BooksGenres::class, ['genres_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\GenreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\GenreQuery(get_called_class());
    }
}
