<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

$this->title = 'create roles';

?>
<div class="row">
    <div class="">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'rbac-form',
                    'layout' => 'horizontal',
                    'enableClientValidation' => true,
                    'validateOnChange' => false,
                	'options' => [
                		'data-pjax' => 1
                	]
                ]) ?>
                <?php 
            		echo $form->field($model, 'type')->widget(Select2::classname(), [
						'data' => [
							"1" => "role", 
							"2" => "permission"
						],
						'options' => [
							'placeholder' => 'Select type RBAC item',
						],
						'pluginOptions' => [
							'allowClear' => true,
							'minimumResultsForSearch' => '-1',
						],
					]); 
				?>

				<?php 
            		echo $form->field($model, 'parent')->widget(Select2::classname(), [
						'data' => \yii\helpers\ArrayHelper::map($auth->roles, 'name', 'name'),
						'options' => [
							'placeholder' => 'Select parent',
						],
						'pluginOptions' => [
							'allowClear' => true,
							'minimumResultsForSearch' => '-1',
						],
					]); 
				?>

                <?= $form->field($model, 'description') ?>

                <?= $form->field($model, 'name') ?>

                <?= Html::submitButton('Создать', [
                    'class' => 'btn btn-primary btn-block',
                ]) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>