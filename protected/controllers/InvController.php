<?php

class InvController extends Controller {

    public $layout = '//layouts/column2';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

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

    public function actionIndex() {
        $this->render('index');
    }

    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('site/error', $error);
        }
    }

    public function actionFindStock() {
        $data = $_POST['InvmvStock'];
        $retval = array();
        if ($data['cditem'] == '')
            $retval = array('type' => 'E', 'message' => 'Item barang tidak boleh kosong ..!');

        $retval = array();
        if ($data['cdunit'] == '')
            $retval = array('type' => 'E', 'message' => 'Kode Unit & warehouse tidak boleh kosong ..!');

        if (count($retval) > 0) {
            print_r(json_encode($retval));
            return;
        }

        $model = new InvmvStock;
        $criteria = new CDbCriteria;
        $criteria->select = "date(date_mv) as dtmv, *";
        $criteria->condition = "date(date_mv) >= to_date('" . $data['date_fr'] . "','dd-mm-yyyy') AND date(date_mv) <= to_date('" . $data['date_to'] . "','dd-mm-yyyy')";
        $criteria->compare('cditem', $data['cditem']);
        $criteria->compare('cduom', $data['cduom']);
        $criteria->compare('cdwhse', $data['cdwhse']);
        $criteria->order = 'cditem DESC, mvstock_id DESC';
        $criteria->limit = $data['limit'];

        $hdrStock = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $hdrStock->setPagination(array(
            'pageSize' => $criteria->limit,
//            'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
        ));

        $i = 0;
        $dataStock = array();
        foreach ($hdrStock->getData() as $row) {
            $dataStock[$i]['date_mv'] = $row['date_mv'];
            $dataStock[$i]['cditem'] = $row['cditem'];
            $dataStock[$i]['cduom'] = $row['cduom'];
            $dataStock[$i]['qtymv'] = $row['qtymv'];
            $dataStock[$i]['qtynow'] = $row['qtynow'];
            $dataStock[$i]['refnum'] = $row['refnum'];
            $ndate = split(' ', $row['date_mv']);
            $dataStock[$i]['dtmv'] = $ndate[0];
            $dataStock[$i]['itemdesc'] = $row->item->dscrp;
            $i++;
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $hdrStock->getTotalItemCount(),
            'rows' => $dataStock, //$hdrStock->getData(),
        ));
    }

    public function actionFindSto() {
        $odata = $_POST['data'];
        $type = $_POST['type'];

        $data = array();
        foreach ($odata as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];
            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $namoe = $namoe[1];
            $data[$namoe] = $nilai;
        }


        $model = new InvtrfHdr;
        $criteria = new CDbCriteria;

        if ($type == "trns") {
            $criteria->compare('trf_num', $data['trf_num']);
        } elseif ($type == "rpt") {
            if ($data['cdunit'] == '') {
                echo CJSON::encode(array(
                    'type' => 'E',
                    'total' => 0,
                    'rows' => array(),
                    'message' => 'Unit penerima tidak boleh kosong ..!'
                ));
                return;
            }
            $criteria->condition = "date(date_trf) >= to_date('" . $data['date_trf'] . "','dd-mm-yyyy') AND date(date_trf) <= to_date('" . $data['date_to'] . "','dd-mm-yyyy')";
            $criteria->compare('cdwhse2', $data['cdwhse2']);
            $criteria->compare('status', $data['status']);
            $criteria->limit = $data['limit'];
        }

        $hdrSto = new CActiveDataProvider($model, array('criteria' => $criteria));

        $hdrSto->setPagination(array(
            'pageSize' => $criteria->limit,
        ));

        $i = 0;
        $dataHdr = array();
        foreach ($hdrSto->getData() as $row) {
            $dataHdr[$i]['trf_num'] = $row['trf_num'];            
            $dataHdr[$i]['cdunit'] = $row['cdunit'];
            $dataHdr[$i]['cdwhse'] = $row['cdwhse'];
            $dataHdr[$i]['cdwhse2'] = $row['cdwhse2'];
            $dataHdr[$i]['dscrp'] = $row['dscrp'];
            $dataHdr[$i]['date_trf'] = $row['date_trf'];
            $dataHdr[$i]['status'] = $row['status'];

            $status = Vlookup::model()->find('groupv=:groupv AND cdlookup=:cdlookup', array(':groupv' => 'transf_status', ':cdlookup' => $row['status']));
            $dataHdr[$i]['statusd'] = $status->getAttribute('dscrp');
            $i++;
        }
        
        if (!$hdrSto->getTotalItemCount() > 0) {
            echo CJSON::encode(array(
                'type' => 'E',
                'total' => $hdrSto->getTotalItemCount(),
                'rows' => $hdrSto->getData(),
                'message' => 'Data tidak ditemukan ..!'
            ));
            return;
        }

        $dataDtl = array();
        $jmlDtl = 0;
        if ($type == "trns") {
            $dtlModel = new InvtrfDtl;
            $dtlSto = new CActiveDataProvider($dtlModel, array(
                        'criteria' => $criteria,
                    ));

            $i = 0;
            foreach ($dtlSto->getData() as $row) {
                $dataDtl[$i]['cditem'] = $row['cditem'];
                $dataDtl[$i]['lnitem'] = $row['lnitem'];
                $dataDtl[$i]['nmitem'] = $row->item->dscrp; //'Descriptions of ' . $row['cditem'];
                $dataDtl[$i]['qtyitem'] = $row['qtytrf'];
                $dataDtl[$i]['uom'] = 'ea'; //$row['cduom'];
//                $dataDtl[$i]['pprise'] = $row['uomprice'];
                $dataDtl[$i]['sprise'] = $row['uomprice'];
                $dataDtl[$i]['lnum'] = $row['lnum'];
                $dataDtl[$i]['subtotal'] = $row['qtytrf'] * $row['uomprice'];
                $i++;
            }

            $jmlDtl = $dtlSto->getTotalItemCount();
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $hdrSto->getTotalItemCount(),
            'rows' => $dataHdr, //$hdrSto->getData(),
            'dtl' => $dataDtl,
            'jmldtl' => $jmlDtl,
            'message' => 'Transfer document found ..!'
        ));
        return;
    }

    public function actionFindStoDtl() {
        $data = $_POST;

        $model = new InvtrfDtl;
        $criteria = new CDbCriteria;
        $criteria->compare('trf_num', $data['trf_num']);
        $criteria->limit = '1000';

        $dtlSto = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $dtlSto->setPagination(array(
            'pageSize' => $criteria->limit,
        ));

        if (!$dtlSto->getTotalItemCount() > 0) {
            echo CJSON::encode(array(
                'type' => 'E',
                'total' => $dtlSto->getTotalItemCount(),
                'rows' => $dtlSto->getData(),
                'message' => 'Data tidak ditemukan ..!'
            ));
            return;
        }

        $i = 0;
        foreach ($dtlSto->getData() as $row) {
            $dataDtl[$i]['cditem'] = $row['cditem'];
            $dataDtl[$i]['lnitem'] = $row['lnitem'];
            $dataDtl[$i]['cditemsa'] = $row->item->dscrp; //'Descriptions of ' . $row['cditem'];
            $dataDtl[$i]['qtytrf'] = $row['qtytrf'];
            $dataDtl[$i]['cduom'] = 'ea'; //$row['cduom'];
//                $dataDtl[$i]['pprise'] = $row['uomprice'];
            $dataDtl[$i]['uomprice'] = $row['uomprice'];
            $dataDtl[$i]['lnum'] = $row['lnum'];
            $dataDtl[$i]['subtotal'] = $row['qtytrf'] * $row['uomprice'];
            $i++;
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $dtlSto->getTotalItemCount(),
            'rows' => $dataDtl,
        ));
    }

    public function actionFindGR() {
        $data = $_POST['InvgrHdr']['gr_num'];

        $model = new InvgrHdr;
        $criteria = new CDbCriteria;
        $criteria->compare('gr_num', $data, true);

        $hdrPro = new CActiveDataProvider($model, array(
                    'criteria' => $criteria,
                ));

        $modelDtl = new InvgrDtl();
        $dtlPro = new CActiveDataProvider($modelDtl, array(
                    'criteria' => $criteria,
                ));

        $dataDtl = array();
        $i = 0;
        foreach ($dtlPro->getData() as $row) {
            $dataDtl[$i]['cditem'] = $row['cditem'];
            $dataDtl[$i]['lnitem'] = $row['lnitem'];
            $dataDtl[$i]['nmitem'] = 'Descriptions of ' . $row['cditem'];
            $dataDtl[$i]['qtyitem'] = $row['qty'];
            $dataDtl[$i]['uom'] = $row['cduom'];
            $dataDtl[$i]['pprise'] = $row['uomcost'];
            $dataDtl[$i]['markup'] = $row['markup'];
            $dataDtl[$i]['sprise'] = $row['uomprice'];
            $i++;
        }
        $dtlPro->getItemCount();
        echo CJSON::encode(array(
            'hdr' => $hdrPro->getData(),
            'dtl' => $dataDtl,
            'jml' => $dtlPro->getItemCount()
        ));
    }

    public function actionFindPO() {
        $pnum = strtoupper(trim($_POST['pnum']));
        if ($pnum == '') {
            echo CJSON::encode(array(
                'type' => 'E',
                'message' => 'PO Number must not be blank..'
            ));
            return;
        }

        $model = new InvpurchHdr;
        $criteria = new CDbCriteria;
        $criteria->compare('purch_num', $pnum, false);
        $criteria->limit = 1000;

        $hdrPurch = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $hdrPurch->setPagination(array(
            'pageSize' => $criteria->limit,
        ));

        if (!$hdrPurch->getTotalItemCount() > 0) {
            echo CJSON::encode(array(
                'type' => 'E',
                'message' => 'PO Number not found..'
            ));
            return;
        }

        $dtlModel = new InvpurchDtl;
        $dtlPurch = new CActiveDataProvider($dtlModel, array(
                    'criteria' => $criteria,
                ));
        $dtlPurch->setPagination(array(
            'pageSize' => $criteria->limit,
        ));

        $dataDtl = array();
        $i = 0;
        foreach ($dtlPurch->getData() as $row) {
            $dataDtl[$i]['cditem'] = $row['cditem'];
            $dataDtl[$i]['lnitem'] = $row['lnitem'];
            $dataDtl[$i]['nmitem'] = 'Descriptions of ' . $row['cditem'];
            $dataDtl[$i]['qtyitem'] = $row['qtypurch'];
            $dataDtl[$i]['uom'] = $row['cduom'];
            $dataDtl[$i]['pprise'] = $row['uomcost'];
            $dataDtl[$i]['markup'] = $row['markup'];
            $dataDtl[$i]['sprise'] = $row['uomprice'];
            $dataDtl[$i]['lnum'] = $row['lnum'];
            $i++;
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $hdrPurch->getTotalItemCount(),
            'rows' => $hdrPurch->getData(),
            'dtl' => $dataDtl,
            'jmldtl' => $dtlPurch->getTotalItemCount()
        ));
    }

    public function actionGr() {
        $model = new InvgrHdr;

        if (isset($_POST['InvgrHdr'])) {
            $model->attributes = $_POST['InvgrHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('gr', array('model' => $model));
    }

    public function actionGi() {
        $model = new InvgiHdr;

        if (isset($_POST['InvgiHdr'])) {
            $model->attributes = $_POST['InvgiHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('gi', array('model' => $model));
    }

    public function actionSto() {
        $model = new InvtrfHdr;
        $model->date_trf = date('d-m-Y');
        if (isset($_POST['InvtrfHdr'])) {
            $model->attributes = $_POST['InvtrfHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('sto', array('model' => $model));
    }

    public function actionTrc() {
        $model = new InvmvStock;

        if (isset($_POST['InvmvStock'])) {
            $model->attributes = $_POST['InvmvStock'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('trc', array('model' => $model, 'skrg' => date('d-m-Y')));
    }

    public function actionStoreport() {
        $model = new InvtrfHdr;
        $this->render('storeport', array('model' => $model, 'skrg' => date('d-m-Y')));
    }

    public function actionActGRWhse() {
        $data = $_POST['InvgrHdr'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));
        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionActPOWhse() {
        $data = $_POST['InvpurchHdr'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));
        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionFindUnit() {
        $unit = $_POST['unit'];
        $data = SysUnit::model()->findAll('cdunit=:cdunit', array(':cdunit' => $unit));
        $data = CHtml::listData($data, 'cdunit', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value, 'selected' => true), CHtml::encode($name), true);
        }
    }

    public function actionFindWhse() {
        $whse = $_POST['whse'];
        $data = InvWarehouse::model()->findAll('cdwhse=:cdwhse', array(':cdwhse' => $whse));
        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value, 'selected' => true), CHtml::encode($name), true);
        }
    }

    public function actionFindWhse2() {
        $whse2 = $_POST['whse2'];
        $data = InvWarehouse::model()->findAll('cdwhse=:cdwhse2', array(':cdwhse2' => $whse2));
        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value, 'selected' => true), CHtml::encode($name), true);
        }
    }

    public function actionFindVendor() {
        $cdvend = $_POST['cdvend'];
        $data = Mdvendor::model()->findAll('cdvendcat=:cdvendcat AND cdvend=:cdvend', array(':cdvendcat' => '10', ':cdvend' => $cdvend));
        $data = CHtml::listData($data, 'cdvend', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value, 'selected' => true), CHtml::encode($name), true);
        }
    }

    public function actionFindStatus() {
        $status = $_POST['status'];

        $data = Vlookup::model()->findAll('groupv=:groupv AND convert_to_integer(cdlookup) >= :cdlookup ', array(':groupv' => 'purch_status', ':cdlookup' => $status));
        $data = CHtml::listData($data, 'cdlookup', 'dscrp');

        foreach ($data as $value => $name) {
            if ($status !== '-1')
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
        echo CHtml::tag('option', array('value' => '-1'), CHtml::encode('Canceled (-1)'), true);
    }

    public function actionFindStatusTrf() {
        $status = $_POST['status'];
        $data = Vlookup::model()->findAll('groupv=:groupv AND convert_to_integer(cdlookup) >= :cdlookup ', array(':groupv' => 'transf_status', ':cdlookup' => $status));
        $data = CHtml::listData($data, 'cdlookup', 'dscrp');

        foreach ($data as $value => $name) {
            if ($status !== '-1')
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
        echo CHtml::tag('option', array('value' => '-1'), CHtml::encode('Canceled (-1)'), true);
    }

    public function actionActGIWhse() {
        $data = $_POST['InvgiHdr'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));
        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionActTWhse() {
        $data = $_POST['InvmvStock'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));

        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionActStoWhse() {
        $data = $_POST['InvtrfHdr'];
        $data = InvWarehouse::model()->findAll('cdunit=:cdunit', array(':cdunit' => $data['cdunit']));

        $data = CHtml::listData($data, 'cdwhse', 'dscrp');
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionAutoItem() {
        if (Yii::app()->request->isAjaxRequest && isset($_GET['q'])) {
            $name = $_GET['q'];
            $limit = $_GET['limit'];

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

    public function actionCreateGR() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $newdata = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            if ($namoe !== 'date_gr') {
                $namoe = str_replace('[', ';', $namoe);
                $namoe = str_replace(']', ';', $namoe);

                $namoe = explode(';', $namoe);
                $namoe = $namoe[1];
            }
            $newdata[$namoe] = $nilai;
        }

        $trns = Yii::app()->db->beginTransaction();
        try {
            $gr = InvComp::createGR($newdata, $datadtl);
            if ($gr['type'] == 'E')
                $trns->rollback();
            else
                $trns->commit();
            print_r(json_encode($gr));
        } catch (ErrorException $e) {
            $trns->rollback();
            print_r(json_encode($e->getMessage()));
            return false;
        }
    }

    public function actionCreateSto() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $datahdrnw = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $namoe = $namoe[1];

            $datahdrnw[$namoe] = $nilai;
        }

        $trns = Yii::app()->db->beginTransaction();
        try {
            $sto = InvComp::createSto($datahdrnw, $datadtl);

            if ($sto['type'] == 'S')
                $trns->commit();
            else
                $trns->rollback();

            echo CJSON::encode($sto);
        } catch (ErrorException $e) {
            $trns->rollback();
            print_r($e->getMessage());
        }
        return;
    }

    public function actionCreateGI() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $newdata = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            if ($namoe !== 'date_gi') {
                $namoe = str_replace('[', ';', $namoe);
                $namoe = str_replace(']', ';', $namoe);

                $namoe = explode(';', $namoe);
                $namoe = $namoe[1];
            }
            $newdata[$namoe] = $nilai;
        }

        InvComp::createGI($newdata, $datadtl);
    }

    public function actionPurch() {
        $model = new InvpurchHdr;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='invpurch-hdr-purch-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['InvpurchHdr'])) {
            $model->attributes = $_POST['InvpurchHdr'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('purch', array('model' => $model));
    }

    public function actionCreatePurch() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $newdata = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $namoe = $namoe[1];

            $newdata[$namoe] = $nilai;
        }

        $trns = Yii::app()->db->beginTransaction();
        try {
            $po = InvComp::createPO($newdata, $datadtl);
            if ($po['type'] == 'E')
                $trns->rollback();
            else
                $trns->commit();
            print_r(json_encode($po));
        } catch (ErrorException $e) {
            $trns->rollback();
            print_r($e->getMessage());
            return false;
        }
    }

    public function actionUpdatePurch() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $newdata = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $namoe = $namoe[1];

            $newdata[$namoe] = $nilai;
        }

        $trns = Yii::app()->db->beginTransaction();
        try {
            $upd = InvComp::updtePO($newdata['oldstatus'], $newdata['status'], $newdata, $datadtl);
            if (isset($upd['type'])) {
                if ($upd['type'] == 'S')
                    $trns->commit();
                else
                    $trns->rollback();
            }else
                $trns->rollback();

            print_r(CJSON::encode($upd));
        } catch (ErrorException $e) {
            $trns->rollback();
            $pesan = array('type' => 'E', 'message' => $e->getMessage());
            print_r(CJSON::encode($pesan));
        }
    }

    public function actionUpdateSto() {
        $datahdr = $_POST['data'];
        $datadtl = $_POST['datadtl'];
        $datadtl = $datadtl['rows'];

        $datahdrnw = array();
        foreach ($datahdr as $rows) {
            $namoe = $rows['name'];
            $nilai = $rows['value'];

            $namoe = str_replace('[', ';', $namoe);
            $namoe = str_replace(']', ';', $namoe);
            $namoe = explode(';', $namoe);
            $namoe = $namoe[1];

            $datahdrnw[$namoe] = $nilai;
        }

        $trns = Yii::app()->db->beginTransaction();
        try {
            $upd = InvComp::updteSto($datahdrnw['oldstatus'], $datahdrnw['status'], $datahdrnw, $datadtl);
            if (isset($upd['type'])) {
                if ($upd['type'] == 'S')
                    $trns->commit();
                else
                    $trns->rollback();
            }else
                $trns->rollback();

            print_r(CJSON::encode($upd));
        } catch (ErrorException $e) {
            $trns->rollback();
            $pesan = array('type' => 'E', 'message' => $e->getMessage());
            print_r(CJSON::encode($pesan));
        }
    }

}
