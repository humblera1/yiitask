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
            return Yii::$app->user->login($this->getAuthor());
        }
        
        return false;
    }

    protected function getAuthor(): ?Author
    {
        if ($this->_author === null) {
            $this->_author = Author::findByAuthorname($this->username);
        }

        return $this->_author;
    }
}
