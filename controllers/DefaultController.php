<?php

namespace fourteenmeister\rbac\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            if (yii::$app->id === 'app-frontend')
                                throw new \yii\web\HttpException(404, Yii::t('yii','Page not found.'));
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex() {
    	$auth = \Yii::$app->authManager;
        return $this->render('index', [
        	'model' => $model
        ]);
    }

}