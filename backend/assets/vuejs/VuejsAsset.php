<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 7/29/16
 * Time: 11:41
 */

namespace backend\assets\vuejs;


use yii\web\AssetBundle;

class VuejsAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = __DIR__ . '/media';
        parent::init();
    }

    public $js = [
        'vue.min.js',
    ];

}