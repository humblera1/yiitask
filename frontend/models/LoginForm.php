<?php

namespace frontend\models;

use common\models\Author;
use yii\base\Model;

class LoginForm extends Model
{
    public ?string $username = null;
    public ?string $password = null;

    private ?Author $_author = null;
    private ?string $_token = null;


    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $author = $this->getAuthor();
            if (!$author || !$author->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            $author = $this->getAuthor();
            $author->generateAccessToken();

            $this->_token = $author->access_token;

            return $author->save();
        }
        
        return false;
    }

    public function getAccessToken(): string
    {
        return $this->_token;
    }

    protected function getAuthor(): ?Author
    {
        if ($this->_author === null) {
            $this->_author = Author::findByAuthorname($this->username);
        }

        return $this->_author;
    }
}
