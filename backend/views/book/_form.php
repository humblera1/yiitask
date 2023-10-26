<?php

use common\models\Author;
use common\models\enums\BookTypeEnum;
use common\models\Genre;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(
            BookTypeEnum::TYPE_LIST,
            [
                'prompt' => 'Choose edition...'
            ]
        ); ?>

        <?= $form->field($model, 'genreList')->widget(Select2::class, [
            'data' => ArrayHelper::map(Genre::find()->all(), 'id', 'name'),
            'options' => ['prompt' => 'Choose genre...', 'multiple' => true],
            'pluginOptions' => ['allowClear' => true]
        ]) ?>

        <?= $form->field($model, 'author_id')->dropDownList(
            ArrayHelper::map(Author::find()->all(), 'id', 'username'),
            [
                'prompt' => 'Choose author...'
            ]
        ); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mx-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
