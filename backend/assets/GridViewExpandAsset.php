<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/16/16
 * Time: 11:19
 */

namespace backend\assets;


use yii\web\AssetBundle;

class GridViewExpandAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/GridViewExpandAssetMedia';

        $this->js = [
            'main.js'
        ];

        parent::init();
    }

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
