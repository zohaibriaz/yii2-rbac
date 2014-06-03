<?php

$this->title = 'RBAC page';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\helpers\Url;
use fourteenmeister\tree\Widget;
use yii\web\JsExpression;

echo Widget::widget([
	'id' => 'rbacTree',
	'expandAll' => true,
	'fontSize' => '1.5em',
	'jsOptions' => [
		'source' => [
			'url' => 'rbac/data',
			'cache' => false
		],
		'autoCollapse' => true,
		'debugLevel' => 0,
		'fx' => null,
		//'icons' => false,
		'extensions' => ['dnd', 'menu', 'edit', 'glyph'],
		//'checkbox' => true,
		//'selectMode' => 0,
		'glyph' => [
			'map' => [
				'doc' => "glyphicon glyphicon-file",
				'docOpen' => "glyphicon glyphicon-file",
				'checkbox' => "glyphicon glyphicon-unchecked",
				'checkboxSelected' => "glyphicon glyphicon-check",
				'checkboxUnknown' => "glyphicon glyphicon-share",
				'error' => "glyphicon glyphicon-warning-sign",
				'expanderClosed' => "glyphicon glyphicon-plus-sign",
				'expanderLazy' => "glyphicon glyphicon-plus-sign",
				// 'expanderLazy' => "glyphicon glyphicon-expand",
				'expanderOpen' => "glyphicon glyphicon-minus-sign",
				'folder' => "glyphicon glyphicon-folder-close",
				'folderOpen' => "glyphicon glyphicon-folder-open",
				'loading' => "glyphicon glyphicon-refresh"
				// 'loading' => "icon-spinner icon-spin"'
			]
		],
		/*'removeNode' => new JsExpression('function(event, data) {
			console.log(data);
		}'),*/
		'edit' => [
			'triggerStart' => ['dblclick'],
			'save' => new JsExpression('function(event, data){
				var value = data.value;
				var id = data.node.key;
				var type = data.node.data.type;
				$.post("' . Url::to("rbac/update") . '", { 
					value: value, 
					action: "rename", 
					id: id, 
					type: type
				}).done(function( data ) {
					$("#response").html(data);
					alertClose();
				});
			}'),
		],
		'menu' => [
			'selector' => '#contextMenu',
			'position' => [
				'my' => 'center'
			],
			'select' => new JsExpression('function(event, data){
				var id = data.node.key;
				var type = data.node.data.type;
				var action = $(data.menuItem).attr("id");
				var tree = data.tree;
				if(action == "refresh") {
					tree.reload();
				}
				if(action == "create") {
					$.post("' . Url::to("rbac/create") . '", {
						action: "create", 
						id: id, 
						type: type
					}).done(function( data ) {
						modalShow({
							level : "info", 
							title : "Создание новой роли/разрешения", 
							body : data,
							fade : null,
							events: {
								"hidden.bs.modal" : function() {
									tree.reload();
									$(".select2-hidden-accessible").remove();
								}
							},
							buttons: [
								$("<button class=\"btn btn-info\" type=\"button\" data-dismiss=\"modal\">Отмена</button>")
							]
						});
					});
				}
				if(action == "delete") {
					if (id == "admin") {
						alertShow("Эта роль является системной и не может быть удалена.", "danger");
						return true;
					}
					var body = "";
					var children = data.node.getChildren();
					if(children) {
						body = body + "<div>Также будут удалены все дочерние роли и разрешения:</div><ul>";
						$.each(data.node.getChildren(), function(index, node){
							var key = node.key;
							body = body + "<li>" + key + "</li>";
						})
						body = body + "</ul>";
					}
					modalShow({
						level : "danger", 
						title : "Подтвреждение удаления роли: \"<strong>" + id + "</strong>\"", 
						body : body,
						fade : null,
						buttons: [
							{
								"label" : "Удалить",
								"buttonClass" : "btn btn-danger",
								"options" : {
									"click" : function(e) {
										$(".modal").modal("hide");
										$.post("rbac/delete", { 
											id: id, 
											type: type
										}).done(function(data) {
											$("#response").html(data);
											alertClose();
											tree.reload();
										});
									}
								}
							},
							$("<button class=\"btn btn-info\" type=\"button\" data-dismiss=\"modal\">Отмена</button>")
						]
					});
				}
			}'),
		],
		'dnd' => [
            'preventVoidMoves' => true,
            'preventRecursiveMoves' => true,
            'autoExpandMS' => 400,
            'dragStart' => new JsExpression('function(node, data) {
            	var id = data.node.key;
            	if (id == "admin") {
            		return false;
            	}
                return true;
            }'),
            'dragEnter' => new JsExpression('function(node, data) {
                return true;
            }'),
            'dragOver' => new JsExpression('function(node, data) {
            	if(data.hitMode != "over" || !data.node.folder) {
            		var dropMarker = data.tree.ext.dnd.$dropMarker;
            		$(dropMarker).hide();
            		var helper = data.ui.helper;
            		$(helper).removeClass("fancytree-drop-accept");
            		$(helper).addClass("fancytree-drop-reject");
            		return false;
            	}
            }'),
            'dragDrop' => new JsExpression('function(node, data) {
            	if (!data.node.folder) {
            		return false;
            	}
				var target = data.node;
            	if(data.hitMode != "over" && data.node.key != "admin") {
            		return false;
            	}
            	if (data.hitMode != "over" && data.node.key == "admin") {					
					var target = data.node.parent;
            	}
            	var id = data.otherNode.key;
				var type = data.node.data.type;
				var tree = data.tree;
                $.post("' . Url::to("rbac/update") . '", {
					action: "move", 
					child: id, 
					parent: target.key
				}).done(function( data ) {
					$("#response").html(data);
					alertClose();
				});
                data.otherNode.moveTo(target);
            }'),
        ],
	]
]);

echo Html::tag('div', null, ['id' => 'rbacTree']);

echo yii\jui\Menu::widget([
	'items' => [
		['label' => 'Создать', 'url' => '', 'options' => ['id' => 'create']],
		['label' => 'Удалить', 'url' => '', 'options' => ['id' => 'delete']],
		['label' => 'Обновить', 'url' => '', 'options' => ['id' => 'refresh']],
	],
	'options' => [
		'class' => 'contextMenu ui-helper-hidden',
		'id' => 'contextMenu'
	],
	'itemOptions' => [
		'onClick' => 'return false;'
	]
]);

?>
