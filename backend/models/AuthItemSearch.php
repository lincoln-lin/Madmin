<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace backend\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

class AuthItemSearch extends Model
{
    public $status;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $rule_name;

    /** @var \backend\components\DbManager */
    protected $manager;

    /** @var int */
    protected $type;

    /** @inheritdoc */
    public function __construct($type, $config = [])
    {
        parent::__construct($config);
        $this->manager = \Yii::$app->authManager;
        $this->type    = $type;
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'default' => ['name', 'description', 'rule_name', 'status'],
        ];
    }

    /**
     * @param  array              $params
     */
    public function search($params = [])
    {

        $query = (new Query)->select(['name', 'description', 'rule_name'])
            ->andWhere(['type' => $this->type])
            ->andWhere('name not like "!!!%"')
            ->from($this->manager->itemTable);

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
            'query' => $query,
            'key' => 'name',
        ]);

        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'rule_name', $this->rule_name]);
        }

        if ($this->status == 1) { // 未过期
            $query->leftJoin(AuthItemProfile::tableName().' p', 'item_name=name')
                ->andWhere(AuthItemProfile::getValidWhere());
        } else if ($this->status == 2) { // 已过期
            $query->leftJoin(AuthItemProfile::tableName().' p', 'item_name=name')
                ->andWhere(AuthItemProfile::getInValidWhere());
        }

        $profiles = AuthItemProfile::find()->indexBy('item_name')->all();

        $childs = (new Query)
            ->select(['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'])
            ->from([\Yii::$app->authManager->itemTable, \Yii::$app->authManager->itemChildTable])
            ->where(['name' => new Expression('[[child]]')])
            ->all();
        $childs = ArrayHelper::index($childs, null, 'parent');

        return [$dataProvider, $profiles, $childs];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'status' => '过期状态',
        ];
    }
}
