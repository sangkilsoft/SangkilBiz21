<?php

class PurcController extends Controller {

    public $layout = '//layouts/column2';

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
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionBayar() {
        $model = new FicoBayar;
        $po = (isset($_GET['po'])) ? $_GET['po'] : null;
        $model = $model->model()->find('purch_num=:po', array(':po' => $po));
        $this->render('bayar', array('model' => $model));
    }

    public function actionHutang() {
        $model = new FicoBayar;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='fico-bayar-hutang-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['FicoBayar'])) {
            $model->attributes = $_POST['FicoBayar'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('hutang', array('model' => $model));
    }

    public function actionRkphutang() {
        $model = new FicoBayar;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='fico-bayar-rkphutang-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['FicoBayar'])) {
            $model->attributes = $_POST['FicoBayar'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('rkphutang', array('model' => $model));
    }

    public function actionBayarHutang() {
        $data = $_POST;

        $trns = Yii::app()->db->beginTransaction();
        try {
            $hdr = new InvpurchHdr;
            $criteria = new CDbCriteria;
            $criteria->condition = 'purch_num=:purch_num';
            $criteria->params = array(':purch_num' => $data['purch_num']);

            $pohdr = $hdr::model()->find($criteria);

            //entri hdr jurnal ---------------------------------
            $billhdr['dscrp'] = 'Pembayaran Hutang';
            $billhdr['gl_date'] = date('d-m-Y');
            $billhdr['refnum'] = $pohdr->purch_num;
            $billhdr['cdunit'] = $pohdr->cdunit;

            //debit hutang
            $billdtl[0] = array('cdacc' => '2001', 'cdfigroup' => '2000', 'debit' => $data['jml_bayar'], 'kredit' => '0');
            //kredit kas
            $billdtl[1] = array('cdacc' => '1003', 'cdfigroup' => '1000', 'debit' => '0', 'kredit' => $data['jml_bayar']);

            $bill = FiComp::createGL($billhdr, $billdtl);
            if ($bill['type'] == 'E') {
                $trns->rollback();
                echo CJSON::encode($bill);
                return;
            }

            //bayar hutang per PO
            $data['cdfigl'] = $bill['val'];
            $bayar = FiComp::bayarHutang($data);

            echo CJSON::encode($bayar);
            $trns->commit();
        } catch (Exception $e) {
            return array('type' => 'E', 'message' => $e->getMessage());
        }
        //echo CJSON::encode(array('type' => 'S', 'message' => $bill['message']));
    }

}