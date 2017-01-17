<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/27/16
 * Time: 11:16 AM
 */

namespace backend\models;


use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Exception;

class SqlQueryForm extends Model
{
    public $sql;
    
    public function rules()
    {
        return [
            ['sql', 'match', 'pattern' => '/^\s*(SELECT|SHOW|DESCRIBE)\b/i', 'message' => 'SQL不是读查询'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'sql' => 'SQL',
        ];
    }

    public function query()
    {
        if (!$this->validate() || !$this->sql) {
            return null;
        }
        try {
            $allModels = \Yii::$app->db->createCommand($this->sql)->queryAll();
        } catch (Exception $e) {
            $this->addError('sql', $e->getMessage());
            return null;
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => ['class' => 'backend\components\Pagination'],
        ]);
        
        return $dataProvider;
    }
}