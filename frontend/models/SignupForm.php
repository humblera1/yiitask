<?php

namespace frontend\models;

use common\models\Author;
use yii\base\Model;

class SignupForm extends Model
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password = null;

    protected ?string $_token = null;

    public function rules(): array
    {
        return [
            [['username'], 'trim'],
            [['username'], 'required'],
            [['username'], 'unique', 'targetClass' => '\common\models\Author', 'message' => 'This username has already been taken.'],
            [['username'], 'string', 'min' => 2, 'max' => 255],

            [['email'], 'trim'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique', 'targetClass' => '\common\models\Author', 'message' => 'This email address has already been taken.'],

            [['password'], 'required'],
            [['password'], 'string'],
        ];
    }

    public function signup(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }
        
        $author = new Author();
        $author->username = $this->username;
        $author->email = $this->email;
        $author->setPassword($this->password);

        $author->generateAccessToken();

        $this->_token = $author->access_token;

        return $author->save();
    }

    public function getAccessToken(): string
    {
        return $this->_token;
    }
}
