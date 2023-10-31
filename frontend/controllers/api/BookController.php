<?php

namespace frontend\controllers\api;

use common\models\Book;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class BookController extends ActiveController
{
    public $modelClass = 'common\models\Book';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions(): array
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['update']);

        return $actions;
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'update' || $action === 'delete') {
            if ($model->author_id !== \Yii::$app->user->identity->id)
                throw new ForbiddenHttpException('You can\'t delete or update books owned by another author');
        }
    }

    public function actionCreate(): array
    {
        $book = new Book();

        $params = $this->request->post();

        $book->attributes = $params;
        $book->author_id = Yii::$app->user->identity->id;

        if ($book->save()) {
            Yii::$app->response->statusCode = 201;
            return [
                'isSuccess' => true,
                'message' => 'You have been successfully created a new book',
                'book' => $book,
            ];
        }

        return [
            'hasErrors' => $book->hasErrors(),
            'errors' => $book->getErrors(),
        ];
    }

    public function actionUpdate(int $id): array
    {
        $book = Book::findOne($id);

        $book->attributes = $this->request->post();
        $book->author_id = Yii::$app->user->identity->id;

        if ($book->save()) {
            return [
                'isSuccess' => true,
                'message' => 'You have been successfully updated the book',
                'book' => $book,
            ];
        }

        return [
            'hasErrors' => $book->hasErrors(),
            'errors' => $book->getErrors(),
        ];
    }

}