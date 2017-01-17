<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/17/16
 * Time: 17:57
 */

namespace backend\assets\papaparse;


use yii\web\AssetBundle;

class PapaParseAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__ . '/media';
        parent::init();
    }

    public $js = [
        'papaparse.min.js',
    ];

}