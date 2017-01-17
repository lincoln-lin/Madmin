<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;

/**
 * AdminUserSearch represents the model behind the search form about `backend\models\AdminUser`.
 */
class AdminUserSearch extends AdminUser
{
    public $role;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['auth_key', 'password_hash', 'password_reset_token', 'email', 'role', 'realname'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return
        ArrayHelper::merge(
            parent::attributeLabels(),
            [
            ]
        );
    }

    public function init()
    {
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     */
    public function search($params)
    {
        $query = AdminUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['class' => 'backend\components\Pagination'],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'realname', $this->realname]);

        /** @var DbManager $authManager */
        $authManager = Yii::$app->authManager;
        if ($this->role) {
            $query->rightJoin($authManager->assignmentTable, "user_id=id")
                ->where(['item_name' => $this->role]);
        }

        if ($this->parentUid) {
            $query->innerJoin('{{%user_child}} uc', 'uc.child_uid=id')
                ->andWhere(['uid' => $this->parentUid]);
            $roles = [];
        } else {
            $roles = (new Query())
                ->select('item_name,user_id')
                ->from($authManager->assignmentTable)
                ->where([
                    'user_id' => ArrayHelper::getColumn($dataProvider->models, 'id')
                ])
                ->all();
        }

        return [$dataProvider, $roles];
    }

}
