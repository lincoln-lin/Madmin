<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/9/16
 * Time: 11:32
 */

namespace backend\assets\ueditor;


use yii\web\AssetBundle;

class UeditorAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/media';
        parent::init();
    }

    public $js = [
        'ueditor.config.js',
        'ueditor.all.js',
        'lang/zh-cn/zh-cn.js',
    ];
}
