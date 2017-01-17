<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/1/16
 * Time: 15:36
 */

namespace backend\components;


use yii\authclient\OAuth2;
use yii\helpers\Json;

class MeizuAuthClient extends OAuth2
{

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://uac.meizu.com/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://uac.meizu.com/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://uac.meizu.com';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'uc_basic_info';
        }
        $this->setNormalizeUserAttributeMap([
            'realname' => 'displayName',
            'email' => 'mail',
            'employee_id' => 'employeeId',
            'phone' => 'mobile',
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        $resp = $this->api('account/get.do', 'GET');
        return $resp['value'];
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'meizu';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return '魅族统一认证中心';
    }
}
