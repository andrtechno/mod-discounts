<?php

namespace panix\mod\discounts\widgets\countdown;

use Yii;
use panix\engine\data\Widget;

class Countdown extends Widget {

    public $model;

    public function run() {
        if (Yii::$app->hasModule('discounts')) {
      
            if ($this->model->hasDiscount && $this->model->discountEndDate) {
                $this->registerScript();
                return $this->render($this->skin);
            }
        }
    }

    public function registerScript() {
        $time = strtotime($this->model->discountEndDate) * 1000;
        //$assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        //$cs = Yii::app()->clientScript;
        $this->view->registerJs("
            $(function(){
                $.fn.countdown_day = function(number) {
                    var num = number % 10;
                    if (num == 1){
                        return '" . Yii::t('wgt_Countdown/default', 'DAYS', ['n'=>0]) . "';
                    } else if (num > 1 && num < 5){
                        return '" . Yii::t('wgt_Countdown/default', 'DAYS', ['n'=>1]) . "';
                    } else {
                        return '" . Yii::t('wgt_Countdown/default', 'DAYS', ['n'=>2]) . "';
                    }
                };


                $('#countdown').countdown({
                    timestamp	: " . $time . ",
                    callback: function (days, hours, minutes, seconds) {
                        $('.date .key').html(days);
                        $('.hour .key').html(hours);
                        $('.minutes .key').html(minutes);
                        $('.seconds .key').html(seconds);
                        //$('.date .value').html($.fn.countdown_day(days));
                    }
                });
            });
         ", \yii\web\View::POS_END, 'Countdown');
        //$this->view->registerJsFile(Yii::$app->assetManager->publish('@discounts/widgets/countdown/assets').'/jquery.countdown.min.js');
        CountdownAsset::register($this->view);
    }

}
