<?php

namespace frontend\controllers\api;

use common\models\Author;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class AuthorController extends ActiveController
{
    public $modelClass = 'common\models\Author';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['update', 'delete', 'logout'];
        $behaviors['authenticator']['authMethods'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'update' || $action === 'delete') {
            if ($model->id !== \Yii::$app->user->id)
                throw new ForbiddenHttpException('You can\'t delete or update another author information');
        }
    }

    public function actionCreate()
    {
        $model = new SignupForm();

        $model->attributes = $this->request->post();

        if ($model->signup()) {
            return [
                'isSuccess' => 201,
                'message' => 'You have been successfully registered',
                'author' => $model,
                'your token' => $model->getAccessToken()
            ];
        };

        return [
            'hasErrors' => $model->hasErrors(),
            'errors' => $model->getErrors(),
        ];
    }


    public function actionLogout()
    {
        return Yii::$app->user->identity;
    }
}