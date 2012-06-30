<?php

class SalesController extends Controller {

    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            //'accessControl', // perform access control for CRUD operations
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

    public function actionGrosir() {
        $model = new InvgrHdr;

        if (isset($_POST['InvgrHdr'])) {
            $model->attributes = $_POST['InvgrHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('grosir', array('model' => $model));
    }

    public function actionFindSales() {
        $data = $_POST['SalesHdr'];
        $retval = array();
        if ($data['cdwhse'] == '')
            $retval = array('type' => 'E', 'message' => 'Kode Unit & warehouse tidak boleh kosong ..!');

        if (count($retval) > 0) {
            print_r(json_encode($retval));
            return;
        }

        $model = new SalesHdr();
        $criteria = new CDbCriteria;
        $criteria->select = 'sal_num';
        $criteria->condition = "date(date_sales) >= to_date('" . $data['date_sales'] . "','dd-mm-yyyy') AND date(date_sales) <= to_date('" . $data['date_sales2'] . "','dd-mm-yyyy')";
        $criteria->compare('cdwhse', $data['cdwhse']);

        $hdrSales = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $hdrSales->setPagination(array(
            'pageSize' => 1000,
        ));

        $hdrNum = "";
        $first = true;
        foreach ($hdrSales->getData() as $row) {
            if ($first)
                $hdrNum .= "'" . $row['sal_num'] . "'";
            else
                $hdrNum .= ",'" . $row['sal_num'] . "'";
            $first = false;
        }

        $model2 = new SalesDtl;
        $criteria2 = new CDbCriteria;
        $criteria2->condition = "sal_num IN($hdrNum)";
        $criteria2->order = "cditem ASC";
        $criteria2->limit = $data['limit'];

        $dtlSales = new CActiveDataProvider($model2, array(
                    'criteria' => $criteria2));

        $dtlSales->setPagination(array(
            'pageSize' => $data['limit'],
        ));

        $i = 0;
        $dataDtl = array();
        $sumqty = 0;
        $stotal = 0;
        foreach ($dtlSales->getData() as $row) {
            $dataDtl[$i]['sal_num'] = $row['sal_num'];
            $dataDtl[$i]['lnum'] = $row['lnum'];
            $dataDtl[$i]['cditem'] = $row['cditem'];
            $dataDtl[$i]['itemdesc'] = $row->item->dscrp;
            $dataDtl[$i]['uom'] = $row->item->cduom;
            $dataDtl[$i]['lnitem'] = $row['lnitem'];
            $dataDtl[$i]['qty'] = $row['qty'];
            $dataDtl[$i]['uomprice'] = number_format($row['uomprice']);
            $dataDtl[$i]['uomdiskon'] = $row['uomdiskon'];
            $dataDtl[$i]['stotal'] = number_format(($row['uomprice'] - ($row['uomdiskon'] / 100 * $row['uomprice'])) * $row['qty']);

            $sumqty = $sumqty + $row['qty'];
            $stotal = $stotal + (($row['uomprice'] - ($row['uomdiskon'] / 100 * $row['uomprice'])) * $row['qty']);
            $i++;
        }

        $footer = array(array('cditem' => 'Total', 'qty' => $sumqty, 'stotal' => number_format($stotal)));
        if ($dtlSales->getTotalItemCount() > 0) {
            echo CJSON::encode(array(
                'type' => 'S',
                'total' => $dtlSales->getTotalItemCount(),
                'rows' => $dataDtl, //$dtlSales->getData(),
                'footer' => $footer,
            ));
            return;
        }
    }

    public function actionSalesDtl() {
        $model = new SalesHdr;
        $model->date_sales = date('d-m-Y');
        $model->dscrp = 'Default Sales Retail';
        $this->render('salesdtl', array('model' => $model));
    }

    public function actionPriceCat() {
        $model = new MdpriceCat;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='invpurch-hdr-purch-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['MdpriceCat'])) {
            $model->attributes = $_POST['MdpriceCat'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('priceCat', array('model' => $model));
    }

    public function actionCreatePCat() {
        $data = $_POST['MdpriceCat'];
        $pcat = MdpriceCat::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdpcat=:cdpcat';
        $criteria->params = array(':cdpcat' => $data['cdpcat']);

        $exist = $pcat->exists($criteria);
        if (!$exist) {
            $pcat = new MdpriceCat;
            $pcat->cdpcat = $data['cdpcat'];
            $pcat->dscrp = $data['dscrp'];
        } else {
            $pcat = $pcat->find($criteria);
            $pcat->cdpcat = $data['cdpcat'];
            $pcat->dscrp = $data['dscrp'];
        }

        if (!$pcat->save())
            print_r($icat->getErrors());
    }

    public function actionDataPCat() {
        $dataProvider = new CActiveDataProvider('MdpriceCat');
        if (isset($_POST['rows'])) {
            $dataProvider->setPagination(array(
                'pageSize' => $_POST['rows'],
                'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
            ));
        }
        echo CJSON::encode(array(
            'total' => $dataProvider->getTotalItemCount(),
            'rows' => $dataProvider->getData(),
        ));
    }

    public function actionDeletePCat() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $icat = MdpriceCat::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdpcat=:cdpcat';

                foreach ($data as $row) {
                    $criteria->params = array(':cdpcat' => $row['cdpcat']);
                    $icat = $icat->find($criteria);
                    if (!$icat->delete()) {
                        $trns->rollback();
                        echo ('Delete failed..!');
                        return false;
                    }
                }
                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionPriceItem() {
        $model = new MditemPrice;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='mditem-price-priceItem-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['MditemPrice'])) {
            $model->attributes = $_POST['MditemPrice'];
            if ($model->save()) {
                // form inputs are valid, do something here
                $model = new MditemPrice;
                $this->render('priceItem', array('model' => $model));
            }
        }
        $this->render('priceItem', array('model' => $model));
    }

    public function actionAutoItem() {
        if (Yii::app()->request->isAjaxRequest && isset($_GET['q'])) {
            $name = $_GET['q'];
            $limit = $_GET['limit'];
        } else
            $name = $_POST['cditem'];

        $criteria = new CDbCriteria;
        $criteria->select = 'cditem, lnitem, dscrp, cduom';
        $criteria->condition = 'lower(cditem) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->condition .= 'or lower(dscrp) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->order = 'dscrp ASC';
        $criteria->limit = $limit;
        $pro = Mditem::model()->findAll($criteria);

        $returnVal = '';
        foreach ($pro as $row) {
            $returnVal .= $row->getAttribute('dscrp');
            $returnVal .= '|' . $row->getAttribute('lnitem');
            $returnVal .= '|' . $row->getAttribute('cditem');
            $returnVal .= '|' . $row->getAttribute('dscrp');
            $returnVal .= '|' . $row->getAttribute('cduom');

            $sprice = MditemPrice::model()->find('cditem=:cditem AND lnitem=:lnitem AND cduom=:cduom AND cdpcat=:cdpcat', array(':cditem' => $row->getAttribute('cditem'), ':lnitem' => $row->getAttribute('lnitem'),
                ':cduom' => $row->getAttribute('cduom'), ':cdpcat' => '01'));

            if (count($sprice) > 0)
                $returnVal .= '|' . number_format($sprice->getAttribute('val_price'), 0);
            else
                $returnVal .= '|0';

            $returnVal .= "\n";
        }
        echo $returnVal;
    }

    public function actionActStoWhse() {
        $data = $_POST['SalesHdr'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));

        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    
    public function actionDirectItem() {
        if (Yii::app()->request->isAjaxRequest && isset($_POST['itemkey'])) {
            $name = $_POST['itemkey'];
            $limit = 50;
        } else
            $name = $_POST['cditem'];

        $criteria = new CDbCriteria;
        $criteria->select = 'cditem, lnitem, dscrp, cduom';
        $criteria->condition = 'lower(cditem) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->condition .= 'or lower(dscrp) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->order = 'dscrp ASC';
        $criteria->limit = $limit;
        $pro = Mditem::model()->findAll($criteria);

        $returnVal = '';
        foreach ($pro as $row) {
            $returnVal .= $row->getAttribute('cditem') . ': ' . $row->getAttribute('dscrp');
            $returnVal .= '|' . $row->getAttribute('lnitem');
            $returnVal .= '|' . $row->getAttribute('cditem');
            $returnVal .= '|' . $row->getAttribute('dscrp');
            $returnVal .= '|' . $row->getAttribute('cduom');

            $sprice = MditemPrice::model()->find('cditem=:cditem AND lnitem=:lnitem AND cduom=:cduom AND cdpcat=:cdpcat', array(':cditem' => $row->getAttribute('cditem'), ':lnitem' => $row->getAttribute('lnitem'),
                ':cduom' => $row->getAttribute('cduom'), ':cdpcat' => '01'));

            if (count($sprice) > 0)
                $returnVal .= '|' . number_format($sprice->getAttribute('val_price'), 0);
            else
                $returnVal .= '|0';

            $returnVal .= "\n";
        }
        echo $returnVal;
    }

}
