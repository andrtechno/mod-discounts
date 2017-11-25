<?php

namespace panix\mod\discounts\widgets\countdown;

use Yii;
use panix\engine\CMS;

class Countdown extends \panix\engine\data\Widget {

    public $model;

    public function run() {
        if (Yii::$app->hasModule('discounts')) {
      
            if ($this->model->appliedDiscount && $this->model->discountEndDate) {
                $this->registerScript();
                      $this->registerTranslations();
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
                        return '" . CMS::GetFormatWord('app', 'DAYS', 0) . "';
                    } else if (num > 1 && num < 5){
                        return '" . CMS::GetFormatWord('app', 'DAYS', 1) . "';
                    } else {
                        return '" . CMS::GetFormatWord('app', 'DAYS', 2) . "';
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
         ", \yii\web\View::POS_HEAD, 'Countdown');
        //$this->view->registerJsFile(Yii::$app->assetManager->publish('@discounts/widgets/countdown/assets').'/jquery.countdown.min.js');
        CountdownAsset::register($this->view);
    }
    protected function registerTranslations($id='widgets/countdown') {
        $lang = Yii::$app->language;
        Yii::$app->i18n->translations['discounts/widgets/countdown/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@discounts/widgets/countdown/messages',
            'fileMap' => [
              'default' => 'default.php',
            ]
        ];
       // print_r(Yii::$app->i18n->translations);die;
    }
}
