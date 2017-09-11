<?php

namespace panix\mod\discounts;

use Yii;
use panix\engine\WebModule;

class Module extends WebModule {



    public function getInfo() {
        return [
            'label' => Yii::t('discounts/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'icon-discounts',
            'description' => Yii::t('discounts/default', 'MODULE_DESC'),
            'url' => ['/admin/discounts'],
        ];
    }
    public function getNav() {
        return [
            [
                'label' => Yii::t('discounts/default','MODUL_NAME'),
                "url" => ['/admin/discounts'],
                'icon' => 'icon-discount'
            ],
        ];
    }

    public function getAdminMenu2() {
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('discounts/default','MODUL_NAME'),
                        'url' => ['/admin/discounts'],
                        'icon' => 'sa',
                    ),
                ),
            ),
        );
    }

 

}
