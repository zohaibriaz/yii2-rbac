<?php

namespace fourteenmeister\rbac\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use fourteenmeister\rbac\controllers\DefaultController;
use yii\web\NotFoundHttpException;
use fourteenmeister\rbac\models\rbacForm;
use yii\bootstrap\Alert;
use yii\helpers\Json;
use fourteenmeister\tree\Tree;

class DataController extends DefaultController
{

    public function actionIndex() {        
        $model = new rbacForm();
        $tree = new Tree([
            'model' => $model,
            'key' => 'name',
            'parent_key' => 'parent',
            'title_key' => 'description'
        ]);
    	echo Json::encode($tree->nodes);
    }

}