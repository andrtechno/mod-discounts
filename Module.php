<?php

namespace panix\mod\discounts;

use panix\mod\discounts\models\Discount;
use Yii;
use yii\base\BootstrapInterface;
use panix\engine\WebModule;
use panix\mod\admin\widgets\sidebar\BackendNav;

class Module extends WebModule implements BootstrapInterface
{

    public $icon = 'discount';

    /**
     * @var null
     */
    public $discounts = null;
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($this->discounts === null) {

            $this->discounts = Discount::find()
                ->published()
                ->applyDate()
                ->all();
        }
    }

    public function getInfo()
    {
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

    public function getAdminMenu()
    {
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
