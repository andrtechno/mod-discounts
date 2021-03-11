<?php

namespace panix\mod\discounts;

use Yii;
use yii\base\BootstrapInterface;
use panix\engine\WebModule;
use app\web\themes\dashboard\sidebar\BackendNav;
use panix\mod\discounts\models\Discount;
use yii\db\Exception;

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
            try {
                $this->discounts = Discount::find()
                    ->published()
                    ->applyDate()
                    ->all();
            } catch (Exception $exception) {

            }
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
                        'visible' => Yii::$app->user->can('/discounts/admin/default/index') || Yii::$app->user->can('/discounts/admin/default/*')
                    ],
                ],
            ],
        ];
    }

}
