<?php

namespace common\models;

use common\models\enums\BookTypeEnum;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
 * @property Author $author
 * @property BookGenre[] $bookGenre
 * @property Genre[] $genres
 */
class Book extends ActiveRecord
{

    public $genre;

    public static function tableName(): string
    {
        return '{{%books}}';
    }

    public function behaviors(): array
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

    public function rules(): array
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

    public function attributeLabels(): array
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

    public function fields(): array
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

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getBookGenre(): ActiveQuery
    {
        return $this->hasMany(BookGenre::class, ['books_id' => 'id']);
    }

    public function getGenres(): ActiveQuery
    {
        return $this->hasMany(Genre::class, ['id' => 'genres_id'])->via('bookGenre');
    }

    public function getGenresLinks(): string
    {
        $links = [];

        foreach ($this->genres as $genre) {
            $links[] = Html::a($genre->name, ['/genre/view', 'id' => $genre->id]);
        }

        return ($links) ? implode('; ', $links) : '';

    }

    public function beforeSave($insert): bool
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
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes): void
    {
        $author = Yii::$app->user->identity->id;

        if ($insert) {
            $message = "Автором с ID {$author} создана книга '{$this->title}'";
        } else {
            $book = $changedAttributes['title'] ?? $this->title;
            $message = "Автором с ID {$author} oбновлена книга '{$book}'";
        }

        Yii::info($message, 'book');

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete(): void
    {
        $author = Yii::$app->user->identity->id;

        Yii::info("Автором с ID {$author} удалена книга '{$this->title}'", 'book');

        parent::afterDelete();
    }
}
