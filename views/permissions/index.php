<?php

$this->title = 'list of permissions';
$this->params['breadcrumbs'][] = $this->title;

use yii\grid\GridView;
use yii\data\ActiveDataProvider;

$dataProvider = new ActiveDataProvider([
    'query' => fourteenmeister\rbac\models\rbacForm::find()->where(['type' => 2]),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'description'
    ],
]);

?>