<?php

namespace backend\models\base;

use Yii;

/**
 * This is the model class for table "{{%auth_item_profile}}".
 *
 * @property string $item_name
 * @property integer $expire
 */
class AuthItemProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name'], 'required'],
            [['expire'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'expire' => 'Expire',
        ];
    }
}
