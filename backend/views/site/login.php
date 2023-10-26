<?php

use common\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var LoginForm $model
 */

$this->title = 'Вход';
?>
<div class="login-box">
    <div class="login-box-body">
        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model, 'username')->textInput([
            'placeholder' => $model->getAttributeLabel('username'),
            'style' => ['border-radius' => '10px'],
        ])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => $model->getAttributeLabel('password'),
            'style' => ['border-radius' => '10px'],
        ])->label(false) ?>

<!--        <div class="row">-->
<!--            <div class="col-6">-->
<!--                --><?php //= $form->field($model, 'rememberMe')->checkbox() ?>
<!--            </div>-->
<!--        </div>-->

        <?= Html::submitButton('Войти',
            ['class' => 'btn btn-block btn-outline-success mb-2 mt-1']) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>

