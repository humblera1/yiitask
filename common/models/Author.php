<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
    public static function tableName(): string
    {
        return '{{%authors}}';
    }

    public function behaviors(): array
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'password_hash'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'password_hash'], 'string', 'max' => 255],
        ];
    }

    public function fields(): array
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

    public function attributeLabels(): array
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

    public static function findByAuthorname(string $authorname): static
    {
        return static::findOne(['username' => $authorname]);
    }

    public static function findIdentityByAccessToken(mixed $token, mixed $type = null): static
    {
        return static::findOne(['access_token' => $token]);
    }

    public function generateAccessToken(): void
    {
        $this->access_token = Yii::$app->security->generateRandomString(32);
    }

    public function setPassword($password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['author_id' => 'id']);
    }

    public function getBookAmount(): ?int
    {
        return $this->getBooks()->count();
    }

    public function afterSave($insert, $changedAttributes): void
    {
        if ($insert) {
            $message = "Новый автор зарегистрировался в системе!";
        } else {
            $message = "Автор с ID {$this->id} обновил учётную запись!";
        }

        Yii::info($message, 'author');

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete(): void
    {
        Yii::info("Автор с ID $this->id удалил учётную запись", 'author');

        parent::afterDelete();
    }
}
