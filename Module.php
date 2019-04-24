<?php

namespace panix\mod\discounts;


use Yii;
use panix\engine\WebModule;
use panix\mod\admin\widgets\sidebar\BackendNav;

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
        return (new BackendNav())->findMenu('shop')['items'];
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
