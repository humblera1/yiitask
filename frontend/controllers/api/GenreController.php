<?php

namespace frontend\controllers\api;

use yii\rest\ActiveController;

class GenreController extends ActiveController
{
    public $modelClass = 'common\models\Genre';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

}