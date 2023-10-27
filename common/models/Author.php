<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use yii\web\Link;
use yii\web\Linkable;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $access_token
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property int|null $bookAmount
 *
 * @property Book[] $books
 */
class Author extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    public function behaviors()
    {
        return [
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
            [['username', 'email', 'password_hash'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'book amount' => function() {
                return $this->getBooks()->count();
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password_hash' => 'Password',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function findIdentity($id)
    {
        //
    }

    public function getId()
    {
        //
    }

    public function getAuthKey()
    {
        //
    }

    public function validateAuthKey($authKey)
    {
        //
    }

    public static function findByAuthorname($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString(32);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    /**
     * Gets query for [[Books]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BooksQuery
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['author_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AuthorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AuthorQuery(get_called_class());
    }

    public function getBookAmount()
    {
        return $this->getBooks()->count();
    }
}
