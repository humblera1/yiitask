<?php

use common\models\Admin;
use hail812\adminlte\widgets\Menu;
use yii\web\View;

/**
 * @var View $this
 */

/** @var Admin $admin */
$admin = Yii::$app->user->identity;
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <span class="brand-text font-weight-light"><?= Yii::$app->id ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?= Menu::widget([
                'items' => [
                    [
                        'label' => 'Авторы',
                        'icon' => 'users',
                        'url' => ['/author']
                    ],
                    [
                        'label' => 'Книги',
                        'icon' => 'book',
                        'url' => ['/book']
                    ],
                    [
                        'label' => 'Жанры',
                        'icon' => 'tags',
                        'url' => ['/genre']
                    ],
                ]
            ]); ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>