<?php

namespace panix\mod\discounts;

use Yii;
use panix\engine\WebModule;

class Module extends WebModule {

    public $icon = 'discount';

    public function getInfo() {
        return [
            'label' => Yii::t('discounts/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => $this->icon,
            'description' => Yii::t('discounts/default', 'MODULE_DESC'),
            'url' => ['/admin/discounts/default/index'],
        ];
    }

    public function getAdminSidebar()
    {
        return (new \panix\engine\bootstrap\BackendNav)->findMenu('shop')['items'];
    }

    public function getAdminMenu() {
        return [
            'shop' => [
                'items' => [
                    [
                        'label' => Yii::t('discounts/default', 'MODULE_NAME'),
                        'url' => ['/admin/discounts/default/index'],
                        'icon' => $this->icon,
                    ],
                ],
            ],
        ];
    }

}
