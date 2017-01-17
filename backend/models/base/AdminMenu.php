<?php

namespace backend\models\base;

use Yii;

/**
 * This is the model class for table "{{%admin_menu}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $pid
 * @property string $url
 * @property integer $order
 * @property integer $level
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['pid', 'order', 'level'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'pid' => 'Pid',
            'url' => 'Url',
            'order' => 'Order',
            'level' => 'Level',
        ];
    }
}
