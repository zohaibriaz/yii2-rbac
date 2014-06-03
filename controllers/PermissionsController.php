<?php

namespace fourteenmeister\rbac\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PermissionsController extends Controller
{

    public function actionIndex() {
    	$auth = \Yii::$app->authManager;
        return $this->render('index', [
        	'auth' => $auth
        ]);
    }

}