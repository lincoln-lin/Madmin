<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/30/16
 * Time: 1:44 PM
 */

namespace backend\models;


use yii\base\Model;

/**
 * Class KeyStorage
 * @package backend\models
 * 
 * @property string $AUTH_USER
 * @property string $AUTH_PASSWORD
 */
class KeyStorage extends Model
{
    protected $_data = [];

    public function rules()
    {
        return [
            [['AUTH_USER', 'AUTH_PASSWORD'], 'safe'],
        ];
    }

    public function save()
    {
        foreach ($this->_data as $k => $v) {
            $model = $this->getModel($k);
            $model->value = $this->encode($k, $v);
            $model->save(false);
            if ($cache = \Yii::$app->cache) {
                $cache->delete('KeyStorage:' . $k);
            }
        }
    }

    public function __get($name)
    {
        try {
            parent::__get($name);
        } catch (\Exception $e) {
            if (isset($this->_data[$name])) {
                return $this->_data[$name];
            } else {
                return $this->getFromStorage($name);
            }
        }
    }

    protected function getModel($name)
    {
        $model = \backend\models\base\KeyStorage::findOne(['name' => $name]);
        if (!$model) {
            $model = new \backend\models\base\KeyStorage(['name' => $name]);
        }

        return $model;
    }

    public function getFromStorage($name)
    {
        $cache = \Yii::$app->cache;
        if ($cache && ($data = $cache->get('KeyStorage:' . $name))) {
            return $data;
        }

        $model = $this->getModel($name);
        if (!$model->isNewRecord) {
            $data = $this->decode($model->name, $model->value);
        } else {
            $data = $this->decode($model->name, '');
        }

        if ($cache) {
            $cache->set('KeyStorage:' . $name, $data);
        }
        return $data;
    }

    public function decode($name, $value)
    {
        $func = $this->getEncoders($name, true);
        return $func ? call_user_func($func, $value, $name) : $value;
    }

    public function encode($name, $value)
    {
        $func = $this->getEncoders($name);
        return $func ? call_user_func($func, $value) : $value;
    }

    public function __set($name, $value)
    {
        try {
            return parent::__set($name, $value);
        } catch (\Exception $e) {
            $this->_data[$name] = $value;
        }
    }

    public function encoders()
    {
        return [
            [['NAME1','NAME2'], 'encoder_func', 'decoder_func'],
        ];
    }

    public function getEncoders($name, $getDecoder = false)
    {
        $map = $this->encoders();
        foreach ($map as $col) {
            if (in_array($name, (array)$col[0])) {
                if ($getDecoder) {
                    return $col[2];
                } else {
                    return $col[1];
                }
            }
        }
        return null;
    }

    public function setData($data)
    {
        \Yii::configure($this, $data);
    }

    public function attributeLabels()
    {
        return [
            'AUTH_USER' => '帐号',
            'AUTH_PASSWORD' => '密码',
        ];
    }
}
