<?php

namespace fourteenmeister\rbac\models;

use yii\db\ActiveRecord;
use fourteenmeister\rbac\models\rbacForm;

/**
 * This is the model class for table "auth_item".
 *
 * @property integer $name
 */

class rbacForm extends \yii\db\ActiveRecord
{
    
    public $parent;

    public static function tableName()
    {
        return \Yii::$app->authManager->itemTable;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['type', 'description', 'name', 'parent'], 'required'],
            ['parent', 'exist', 'targetAttribute' => 'name'],
            ['name', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();        
        $parent = (new \yii\db\Query())
            ->select('parent')
            ->where(['child' => $this->name])
            ->from(\Yii::$app->authManager->itemChildTable)
            ->scalar();
        if($parent)
            $this->parent = $parent;
    }

}

?>