<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 7/25/16
 * Time: 10:48
 */

namespace backend\assets\zeroclipboard;


use yii\web\AssetBundle;

class ZeroClipboardAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/media';
        parent::init();
    }

    public $js = [
        'jquery.zeroclipboard.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}