<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use yii2tech\ar\linkmany\LinkManyBehavior;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property string|null $type
 * @property int|null $author_id
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Authors $author
 * @property BooksGenres[] $booksGenres
 * @property Genres[] $genres
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    public function behaviors()
    {
        return [
            'categoriesBehavior' => [
                'class' => LinkManyBehavior::class,
                'relation' => 'genres',
                'relationReferenceAttribute' => 'genreList',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id'], 'required'],
            [['author_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'type'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => ['author_id' => 'id']],
            [['genreList'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'type' => 'Type',
            'author_id' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AuthorsQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[BooksGenres]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BooksGenresQuery
     */
    public function getBookGenre()
    {
        return $this->hasMany(BookGenre::class, ['books_id' => 'id']);
    }

    /**
     * Gets query for [[Genres]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\GenresQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::class, ['id' => 'genres_id'])->via('bookGenre');
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BookQuery(get_called_class());
    }

    public function getGenresLinks()
    {
        $links = [];

        foreach ($this->genres as $genre) {
            $links[] = Html::a($genre->name, ['/genre/view', 'id' => $genre->id]);
        }

        return ($links) ? implode('; ', $links) : '';

    }
}
