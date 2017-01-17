<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%oauth_user}}".
 *
 * @property string $source
 * @property string $source_id
 * @property integer $user_id
 * @property AdminUser $user
 */
class OauthUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source', 'source_id'], 'required'],
            [['user_id'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'source' => 'Source',
            'source_id' => 'Source ID',
            'user_id' => 'User ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'user_id']);
    }
}
