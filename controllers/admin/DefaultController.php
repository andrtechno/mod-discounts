<?php

namespace panix\mod\discounts\controllers\admin;

use Yii;
use panix\engine\controllers\AdminController;
use panix\mod\discounts\models\Discount;
use panix\mod\discounts\models\DiscountSearch;

class DefaultController extends AdminController {

    public function actionIndex() {
        $this->pageName = Yii::t('discounts/default', 'MODULE_NAME');


        $this->breadcrumbs[] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/admin/shop']
        ];
        $this->breadcrumbs[] = $this->pageName;

        $this->buttons = [
            [
                'icon' => 'icon-add',
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

    /**
     * Update discount
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($id = false) {
        if ($id === true)
            $model = new Discount;
        else
            $model = Discount::findOne($id);

        if (!$model)
            $this->error404(Yii::t('discounts/default', 'NO_FOUND_DISCOUNT'));




        $this->pageName = ($model->isNewRecord) ? Yii::t('discounts/default', 'Создание скидки') :
                Yii::t('discounts/default', 'Редактирование скидки');


        $this->breadcrumbs[] = [
            'label' => Yii::t('shop/default', 'MODULE_NAME'),
            'url' => ['/shop']
        ];
        $this->breadcrumbs[] = [
            'label' => Yii::t('discounts/default', 'MODULE_NAME'),
            'url' => ['index']
        ];
        $this->breadcrumbs[] = $this->pageName;
        \panix\mod\discounts\assets\DiscountAsset::register($this->view);


        $post = Yii::$app->request->post();




        if ($model->load($post) && $model->validate()) {

            if (!isset($post['Discount']['manufacturers']))
                $model->discountManufacturers = [];
            if (!isset($post['Discount']['categories']))
                $model->discountCategories = [];
            if (!isset($post['Discount']['userRoles']))
                $model->userRoles = [];

            $model->save();
            Yii::$app->session->setFlash('success', \Yii::t('app', 'SUCCESS_CREATE'));
            if ($model->isNewRecord) {
                return Yii::$app->getResponse()->redirect(['/discounts']);
            } else {
                return Yii::$app->getResponse()->redirect(['/discounts/default/update', 'id' => $model->id]);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

}
