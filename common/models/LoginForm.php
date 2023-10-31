<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public ?string $username = null;
    public ?string $password = null;
    public ?bool $rememberMe = true;

    private ?Admin $_admin = null;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            [['rememberMe'], 'boolean'],
            [['password'], 'validatePassword'],
        ];
    }

    public function validatePassword(string $attribute, array $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getAdmin();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getAdmin(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    protected function getAdmin(): ?Admin
    {
        if ($this->_admin === null) {
            $this->_admin = Admin::findByAdminname($this->username);
        }

        return $this->_admin;
    }
}
