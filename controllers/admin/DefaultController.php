<?php

namespace panix\mod\discounts\controllers\admin;

use Yii;
use panix\engine\controllers\AdminController;
use panix\mod\discounts\models\Discount;
use panix\mod\discounts\models\DiscountSearch;

class DefaultController extends AdminController
{
    public function actions()
    {
        return [
            'switch' => [
                'class' => \panix\engine\actions\SwitchAction::class,
                'modelClass' => Discount::class,
            ],
            'delete' => [
                'class' => \panix\engine\actions\DeleteAction::class,
                'modelClass' => Discount::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->pageName = Yii::t('discounts/default', 'MODULE_NAME');


        $this->breadcrumbs[] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/admin/shop']
        ];
        $this->breadcrumbs[] = $this->pageName;

        $this->buttons = [
            [
                'icon' => 'add',
                'label' => Yii::t('discounts/default', 'CREATE_DISCOUNT'),
                'url' => ['create'],
                'options' => ['class' => 'btn btn-success']
            ]
        ];
        $searchModel = new DiscountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUpdate($id = false)
    {
        $model = Discount::findModel($id, Yii::t('discounts/default', 'NO_FOUND_DISCOUNT'));


        $this->pageName = ($model->isNewRecord) ? Yii::t('discounts/default', 'Создание скидки') :
            Yii::t('discounts/default', 'Редактирование скидки');


        $this->breadcrumbs[] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/admin/shop']
        ];
        $this->breadcrumbs[] = [
            'label' => Yii::t('discounts/default', 'MODULE_NAME'),
            'url' => ['index']
        ];
        $this->breadcrumbs[] = $this->pageName;
        \panix\mod\discounts\DiscountAsset::register($this->view);


        $post = Yii::$app->request->post();

        if (!isset($post['Discount']['manufacturers'])) {
            $model->manufacturers = [];
        }
        if (!isset($post['Discount']['categories']))
            $model->categories = [];



        $isNew = $model->isNewRecord;
        if ($model->load($post)) {

            //if (!isset($post['Discount']['userRoles']))
            //    $model->userRoles = [];


            if ($model->validate()) {
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('app', ($isNew) ? 'SUCCESS_CREATE' : 'SUCCESS_UPDATE'));
                $redirect = (isset($post['redirect'])) ? $post['redirect'] : Yii::$app->request->url;
                if (!Yii::$app->request->isAjax)
                    return $this->redirect($redirect);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

}
