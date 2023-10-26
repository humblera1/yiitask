<?php

use common\models\Author;
use common\models\Book;
use common\models\enums\BookTypeEnum;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'type',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'type',
                    'data' => BookTypeEnum::TYPE_LIST,
                    'options' => [
                        'class' => 'form-group',
                        'placeholder' => 'Choose type...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'value' => function(Book $book) {
                    return BookTypeEnum::TYPE_LIST[$book->type];
                },
            ],
            [
                'attribute' => 'author_id',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'author_id',
                    'data' => ArrayHelper::map(Author::find()->all(), 'id', 'username'),
                    'options' => [
                        'class' => 'form-group',
                        'placeholder' => 'Choose author...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'value' => function(Book $book) {
                    return $book->author->username;
                },
            ],
            [
                'class' => ActionColumn::class,
            ],
        ],
    ]); ?>


</div>
