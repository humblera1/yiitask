<?php

namespace common\models;

use common\models\enums\BookTypeEnum;
use Yii;
use yii\behaviors\TimestampBehavior;
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
 * @property string $genre
 *
 * @property Authors $author
 * @property BooksGenres[] $booksGenres
 * @property Genres[] $genres
 */
class Book extends \yii\db\ActiveRecord
{

    public $genre;
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
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
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
            [
                ['type'],
                'in',
                'range' => array_keys(BookTypeEnum::TYPE_LIST),
                'message' => 'This is not a valid type (valid values are: ' . implode(', ',array_keys(BookTypeEnum::TYPE_LIST)) . ')'
            ],
            [['genre'], 'string'],
            [
                ['genre'],
                'in',
                'range' => Genre::find()->select('name')->column(),
                'message' => 'This is not a valid genre, check \'genres\' for possible values',
            ],
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

    public function fields()
    {
        return [
            'id',
            'title',
            'author' => function(){
                return $this->author->username;
            },
            'type' => function(){
                return $this->type ? BookTypeEnum::TYPE_LIST[$this->type] : null;
            },
            'genres' => function(){
                return implode(',', $this->getGenres()->select('name')->column());
            },
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

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($this->genre)) {
            $genre = Genre::findOne(['name' => $this->genre]);
            $genreId = $genre->id;

            if (!empty($bookGenre = BookGenre::findOne(['books_id' => $this->id, 'genres_id' => $genreId]))) {
                $bookGenre->delete();
            } else {

                $bookGenre = new BookGenre();
                $bookGenre->genres_id = $genreId;
                $bookGenre->books_id = $this->id;

                $bookGenre->save();
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
