<?php

$this->title = 'create roles';
$this->params['breadcrumbs'][] = $this->title;

\yii\widgets\Pjax::begin([
	'id' => "rbacPjax",
	'enablePushState' => false
]);
echo $this->render('form', [
	'model' => $model,
	'auth' => $auth
]);

\yii\widgets\Pjax::end();

?>