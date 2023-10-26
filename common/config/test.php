<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => \yii\web\Admin::class,
            'identityClass' => 'common\models\Admin',
        ],
    ],
];
