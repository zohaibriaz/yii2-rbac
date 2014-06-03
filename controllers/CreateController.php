<?php

namespace fourteenmeister\rbac\controllers;

use Yii;
use yii\filters\AccessControl;
use fourteenmeister\rbac\controllers\DefaultController;
use yii\web\NotFoundHttpException;
use fourteenmeister\rbac\models\rbacForm;
use yii\helpers\Html;
use yii\bootstrap\Alert;

class CreateController extends DefaultController
{

    public function actionIndex() {
    	$auth = \Yii::$app->authManager;
    	$model = new rbacForm();        
    	if ($model->load(\Yii::$app->getRequest()->post()) && $model->validate()) {
            $parent = $auth->getRole($model->parent);
            if ($model->type == 1) {
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                $auth->add($role);
                $auth->addChild($parent, $role);
            }
            else {
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;
                $auth->add($permission);
                $auth->addChild($parent, $permission);
            }
            $success = $this->renderPartial('success', [
                'model' => $model,
                'auth' => $auth
            ], false);
            echo Html::script("$('#response').html('{$success}'); alertClose(); $('.modal').modal('hide');");
            return $this->renderAjax('form', [
                'model' => $model,
                'auth' => $auth
            ]);
        }
        if (\Yii::$app->getRequest()->isPost && \Yii::$app->getRequest()->post()['action']) {
            $model->parent = \Yii::$app->getRequest()->post()['id'];
            echo Html::script("$(document).unbind('submit');");
            return $this->renderAjax('index', [
                'model' => $model,
                'auth' => $auth
            ]);
        }
        return $this->render('index', [
        	'model' => $model,
        	'auth' => $auth
        ]);
    }

}