<?php

namespace frontend\models;

use common\models\Author;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    protected $_token;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\Author', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Author', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string'],
        ];
    }

    public function signup()
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

    public function createAccessToken()
    {

    }

    public function getAccessToken()
    {
        return $this->_token;
    }
}
