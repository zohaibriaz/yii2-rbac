<?php

namespace fourteenmeister\rbac\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use fourteenmeister\rbac\controllers\DefaultController;
use yii\web\NotFoundHttpException;
use fourteenmeister\rbac\models\rbacForm;
use yii\bootstrap\Alert;

class DeleteController extends DefaultController
{

    protected $auth;

    public function actionIndex() {
    	$this->auth = \Yii::$app->authManager;
        $nameItem = \Yii::$app->request->post()['id'];
        $this->auth->removeRecursive($nameItem);        
    	echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => 'Поздравляем! Роль успешно удалена!',
        ]);
    }

}