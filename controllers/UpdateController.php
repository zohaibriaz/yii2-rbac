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

class UpdateController extends DefaultController
{

    public function actionIndex() {
    	$action = \yii::$app->request->post()['action'];
    	$this->$action();
    	echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => 'Успешно!',
        ]);
    }

    protected function rename(){
    	$id = \yii::$app->request->post()['id'];
    	$type = \yii::$app->request->post()['type'];
    	$auth = \Yii::$app->authManager;
    	switch ($type) {
    		case '1':    		
    			$item = $auth->getRole($id);
    			break;
    		case '2':
    			$item = $auth->getPermission($id);
    			break;
    	}
    	$item->description = \yii::$app->request->post()['value'];
    	$auth->update($id, $item);
    }

    protected function delete(){
    	$id = \yii::$app->request->post()['id'];
    	$type = \yii::$app->request->post()['type'];
    	$auth = \Yii::$app->authManager;
    	switch ($type) {
    		case '1':    		
    			$item = $auth->getRole($id);
    			break;
    		case '2':
    			$item = $auth->getPermission($id);
    			break;
    	}
    	$item->description = \yii::$app->request->post()['value'];
    	$auth->update($id, $item);
    }

    protected function move(){
        $child = \yii::$app->request->post()['child'];
        $parent = \yii::$app->request->post()['parent'];
        $auth = \Yii::$app->authManager;
        if (is_null($item = $auth->getRole($child))) {
            $item = $auth->getPermission($child);
        }
        $newParent = $auth->getRole($parent);
        if ($parentName = (new \yii\db\Query())
            ->select('parent')
            ->from($auth->itemChildTable)
            ->where(['child' => $child])
            ->one()) {
            $currentParent = $auth->getRole($parentName);
        }
        if($currentParent) {
            $auth->removeChild($currentParent, $item);
        }
        if ($newParent) {
            $auth->addChild($newParent, $item);
        }
    }

}