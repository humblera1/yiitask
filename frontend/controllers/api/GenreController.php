<?php

namespace frontend\controllers\api;

use yii\rest\ActiveController;

class GenreController extends ActiveController
{
    public $modelClass = 'common\models\Genre';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

}