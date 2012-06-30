<?php

class MditemController extends Controller {

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

    public function actionItemGroup() {
        $model = new MditemGroup;
        $this->render('itemGroup', array('model' => $model));
    }

    public function actionItemCat() {
        $model = new MditemCategory;
        $this->render('itemCat', array('model' => $model));
    }

    public function actionDataItemgroup() {
        $dataProvider = new CActiveDataProvider('MditemGroup');
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

    public function actionDataItemcat() {
        $dataProvider = new CActiveDataProvider('MditemCategory');
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

    public function actionAutoVendcat() {
        $name = $_GET['q'];

        // this was set with the "max" attribute of the CAutoComplete widget
        $vcat = MdvendorCat::model();
        $criteria = new CDbCriteria;
        $criteria->condition = " dscrp LIKE :vendesp ";
        $criteria->params = array(':vendesp' => "'%" . $name . "%'");

        $cats = $vcat->findAll($criteria);
        $returnVal = "";
        foreach ($cats as $row) {
            $returnVal .= $row->getAttribute('dscrp') . '|'
                    . $row->getAttribute('cdvendcat') . "\n";
        }
        echo $returnVal;
    }

    public function actionDataVendorcat() {
        $dataProvider = new CActiveDataProvider('MdvendorCat');
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

    public function actionCreateVendorcat() {
        $data = $_POST['MdvendorCat'];

        $vcat = MdvendorCat::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdvendcat=:cdvendcat';
        $criteria->params = array(':cdvendcat' => $data['cdvendcat']);

        $exist = $vcat->exists($criteria);
        if (!$exist) {
            $vcat = new MdvendorCat;
            $vcat->cdvendcat = $data['cdvendcat'];
            $vcat->dscrp = $data['dscrp'];
        } else {
            $vcat = $vcat->find($criteria);
            $vcat->cdvendcat = $data['cdvendcat'];
            $vcat->dscrp = $data['dscrp'];
        }

        if (!$vcat->save())
            print_r($vcat->getErrors());
    }

    public function actionDeleteVCat() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $vcat = MdvendorCat::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdvendcat=:cdvendcat';

                foreach ($data as $row) {
                    $criteria->params = array(':cdvendcat' => $row['cdvendcat']);
                    $vcat = $vcat->find($criteria);
                    if (!$vcat->delete()) {
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

    public function actionDeleteVend() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $vcat = Mdvendor::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdvend = :cdvend';

                foreach ($data as $row) {
                    $criteria->params = array(':cdvend' => $row['cdvend']);
                    $vcat = $vcat->find($criteria);
                    if (!$vcat->delete()) {
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

    public function actionCreateGroup() {
        $data = $_POST['MditemGroup'];

        $group = MditemGroup::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdgroup=:cdgroup';
        $criteria->params = array(':cdgroup' => $data['cdgroup']);

        $exist = $group->exists($criteria);
        if (!$exist) {
            $group = new MditemGroup;
            $group->cdgroup = $data['cdgroup'];
            $group->dscrp = $data['dscrp'];
        } else {
            $group = $group->find($criteria);
            $group->cdgroup = $data['cdgroup'];
            $group->dscrp = $data['dscrp'];
        }

        if (!$group->save())
            print_r($group->getErrors());
    }

    public function actionCreateICat() {
        $data = $_POST['MditemCategory'];

        $icat = MditemCategory::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdicat=:cdicat';
        $criteria->params = array(':cdicat' => $data['cdicat']);

        $exist = $icat->exists($criteria);
        if (!$exist) {
            $icat = new MditemCategory;
            $icat->cdicat = $data['cdicat'];
            $icat->dscrp = $data['dscrp'];
        } else {
            $icat = $icat->find($criteria);
            $icat->cdicat = $data['cdicat'];
            $icat->dscrp = $data['dscrp'];
        }

        if (!$icat->save())
            print_r($icat->getErrors());
    }

    public function actionDeleteGroup() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            ;
            try {
                $uom = MditemGroup::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdgroup=:cdgroup';

                foreach ($data as $row) {
                    $criteria->params = array(':cdgroup' => $row['cdgroup']);
                    $uom = $uom->find($criteria);
                    if (!$uom->delete()) {
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

    public function actionDeleteICat() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $icat = MditemCategory::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdicat=:cdicat';

                foreach ($data as $row) {
                    $criteria->params = array(':cdicat' => $row['cdicat']);
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

    public function actionItemUom() {
        $model = new MditemUom;
        $this->render('itemUom', array(
            'model' => $model,
        ));
    }

    public function actionDataUom() {
        $dataProvider = new CActiveDataProvider('MditemUom');
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

    public function actionCreateUom() {
        $data = $_POST['MditemUom'];

        $uom = MditemUom::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cduom=:cduom';
        $criteria->params = array(':cduom' => $data['cduom']);

        $exist = $uom->exists($criteria);
        if (!$exist) {
            $uom = new MditemUom;
            $uom->cduom = $data['cduom'];
            $uom->dscrp = $data['dscrp'];
        } else {
            $uom = $uom->find($criteria);
            $uom->cduom = $data['cduom'];
            $uom->dscrp = $data['dscrp'];
        }

        if (!$uom->save())
            print_r($uom->getErrors());
    }

    public function actionDeleteUom() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $uom = MditemUom::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cduom=:cduom';

                foreach ($data as $row) {
                    $criteria->params = array(':cduom' => $row['cduom']);
                    $uom = $uom->find($criteria);
                    if (!$uom->delete()) {
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

    public function actionVendorCat() {
        $model = new MdvendorCat;
        $this->render('vendorCat', array('model' => $model));
    }

    public function actionVendorList() {
        $model = new Mdvendor;
        $this->render('vendorList', array('model' => $model));
    }

    public function actionDataVendor() {
        $dataProvider = new CActiveDataProvider('Mdvendor');
        if (isset($_POST['rows'])) {
            $dataProvider->setPagination(array(
                'pageSize' => $_POST['rows'],
                'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
            ));
        }
        $rows = array();
        foreach ($dataProvider->getData() as $row) {
            //$r = get_object_vars($row);
            $r = array();
            foreach ($row as $attr => $val) {
                $r[$attr] = $val;
            }
            $r['kategori'] = $row->category->dscrp;
            $rows[] = $r;
        }

        echo CJSON::encode(array(
            'total' => $dataProvider->getTotalItemCount(),
            'rows' => $rows,
        ));
    }

    public function actionCreateVendor() {
        $data = $_POST['Mdvendor'];

        $vend = Mdvendor::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdvend=:cdvend';
        $criteria->params = array(':cdvend' => $data['cdvend']);

        $exist = $vend->exists($criteria);
        if (!$exist) {
            $vend = new Mdvendor;
            $vend->cdvend = $data['cdvend'];
            $vend->cdvendcat = $data['cdvendcat'];
            $vend->dscrp = $data['dscrp'];
        } else {
            $vend = $vend->find($criteria);
            $vend->cdvend = $data['cdvend'];
            $vend->cdvendcat = $data['cdvendcat'];
            $vend->dscrp = $data['dscrp'];
        }

        if (!$vend->save())
            print_r($vend->getErrors());
    }

    public function actionItems() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "master";

        $model = new Mditem;
        $model->lnitem = '10';
        $this->render('items', array('model' => $model));
    }

    public function actionDataItems() {
        $dataProvider = new CActiveDataProvider('Mditem');
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

    public function actionCreateItems() {
        $data = $_POST['Mditem'];

        $items = Mditem::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cditem=:cditem AND lnitem=:lnitem';
        $criteria->params = array(':cditem' => $data['cditem'], ':lnitem' => $data['lnitem']);

        $exist = $items->exists($criteria);
        if (!$exist) {
            $items = new Mditem;
            $items->cditem = $data['cditem'];
            $items->lnitem = $data['lnitem'];
            $items->dscrp = $data['dscrp'];
            $items->cduom = $data['cduom'];
            $items->cdgroup = $data['cdgroup'];
            $items->cdicat = $data['cdicat'];
        } else {
            echo "Code Item " . $data['cditem'] . " dg line " . $data['lnitem'] . " sudah terdaftar..!";
            return;
        }
        if (!$items->save())
            print_r($items->getErrors());
    }

    public function actionUpdateItems() {
        if (isset($_POST['Mditem'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['Mditem'];
                $item = Mditem::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cditem=:cditem AND lnitem=:lnitem ';
                $criteria->params = array(':cditem' => $data['cditem'], ':lnitem' => $data['lnitem']);

                $item = $item->find($criteria);
                $item->cditem = $data['cditem'];
                $item->lnitem = $data['lnitem'];
                $item->dscrp = $data['dscrp'];
                $item->cduom = $data['cduom'];
                $item->cdgroup = $data['cdgroup'];
                $item->cdicat = $data['cdicat'];

                if (!$item->save()) {
                    $trns->rollback();
                    print_r($item->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteItems() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $item = Mditem::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cditem=:cditem AND lnitem=:lnitem';

                foreach ($data as $row) {
                    $criteria->params = array(':cditem' => $row['cditem'], ':lnitem' => $row['lnitem']);
                    $item = $item->find($criteria);
                    if (!$item->delete()) {
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

    public function actionPriceCat() {
        $model = new MdpriceCat;
        if (isset($_POST['MdpriceCat'])) {
            $model->attributes = $_POST['MdpriceCat'];
            if (!$model->save()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('priceCat', array('model' => $model));
    }

}