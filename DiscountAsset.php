<?php

namespace panix\mod\discounts;

use yii\web\AssetBundle;

class DiscountAsset extends AssetBundle {

    public $sourcePath = __DIR__.'/assets/admin';

    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $js = [
        'default.update.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
