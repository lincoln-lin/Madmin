<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/16/16
 * Time: 5:03 PM
 */

namespace backend\assets;

use backend\Module;
use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/media/dist';

        $this->css = [
            'mz.css'
        ];

        $this->js = [
            'utils.js',
            'mz.js',
            'app.js',
        ];

        parent::init();
    }

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public static function getAssetUrl($file)
    {
        $file = ltrim($file, '/');
        $am = \Yii::$app->assetManager;
        list(,$url) = $am->publish($am->getBundle(get_called_class())->sourcePath);
        return $url."/$file";
    }
}
