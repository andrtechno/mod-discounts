<?php

namespace panix\mod\discounts\widgets\countdown;

use yii\web\AssetBundle;

class CountdownAsset extends AssetBundle {

    public $sourcePath = '@discounts/widgets/countdown/assets';
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $css = [
        'css/countdown.css'
    ];
    public $js = [
        'js/jquery.countdown.js'
    ];

}
