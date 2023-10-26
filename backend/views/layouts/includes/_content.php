<?php

use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Inflector;

/* @var $content string */
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <?= Breadcrumbs::widget(
            [
                'links' => $this->params['breadcrumbs'] ?? [],
                'homeLink' => false
            ]
        ) ?>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <?= Alert::widget([
            'options' => [
                'class' => 'mt-1',
            ],
        ]) ?>
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>