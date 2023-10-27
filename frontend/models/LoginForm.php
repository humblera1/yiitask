<?php

namespace frontend\models;

use common\models\Author;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_author;
    private $_token;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $author = $this->getAuthor();
            if (!$author || !$author->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $author = $this->getAuthor();
            $author->generateAccessToken();

            $this->_token = $author->access_token;

            return $author->save();
        }
        
        return false;
    }

    public function getAccessToken()
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
