<?php

class RetailController extends Controller {

    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                //'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "sales";
        $this->render('index');
    }

    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionRetail() {
        $model = new SalesHdr;
        $model->date_sales = date('d-m-Y');
        $model->dscrp = 'Default Sales Retail';
        if (isset($_POST['InvgrHdr'])) {
            $model->attributes = $_POST['InvgrHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('retail', array('model' => $model));
    }

    public function actionCreateRetail() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];

        if (isset($datadtl['rows']))
            $datadtl = $datadtl['rows'];
        else {
            print_r(json_encode(array('type' => 'E', 'message' => 'Tidak ada item yang disimpan')));
            return;
        }

        $newhdr = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $colmn = $namoe[1];

            $newhdr[$colmn] = $nilai;
        }
        $newhdr['type'] = 'retail';
        $newhdr['dscrp'] = 'Sales Retail';

        $trns = Yii::app()->db->beginTransaction();
        try {
            $sal = SalComp::createSales($newhdr, $datadtl);
            if ($sal['type'] == 'E')
                $trns->rollback();
            else
                $trns->commit();

            echo CJSON::encode($sal);
        } catch (ErrorException $e) {
            $trns->rollback();
            print_r(json_encode($e->getMessage()));
            return false;
        }
    }

}
