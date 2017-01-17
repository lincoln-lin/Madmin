<?php


namespace backend\models;

use backend\helpers\Html;
use yii\rbac\Item;


class AuthItemProfile extends \backend\models\base\AuthItemProfile
{
    public static function getValidWhere()
    {
        return 'p.expire = 0 or p.expire>'.time() . ' or p.expire is null';
    }

    public static function getInValidWhere()
    {
        return 'p.expire > 0 and p.expire<'.time();
    }

    public static function isItemExpired(Item $item)
    {
        $ap = static::findOne(['item_name' => $item->name]);
        if ($ap === null) {
            return false;
        }
        return $ap->expire > 0 && $ap->expire < time();
    }

    public function getExpireLabel()
    {
        $expire = $this->expire;
        return $expire > time()
            ? Html::tag('label', date('Y-m-d H:i', $expire) . '('.ceil(($expire-time())/86400).'天后过期)', ['class' => 'label label-info'])
            : ($expire > 0 ? Html::tag('label', '已过期', ['class' => 'label label-warning']) : Html::tag('label', '永不过期', ['class' => 'label label-success']));
    }
}
