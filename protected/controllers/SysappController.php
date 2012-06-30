<?php

class SysappController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     * ada updatenya
     */
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

    public function actionIndex() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "master";
        $this->render('index');
    }

    public function actionOrganization() {
        $model = new SysOrg;
        $this->render('Organization', array('model' => $model));
    }

    public function actionDataOrg() {
        $dataProvider = new CActiveDataProvider('SysOrg');
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

    public function actionCreateOrg() {
        $data = $_POST['SysOrg'];

        $org = SysOrg::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdorg=:cdorg ';
        $criteria->params = array(':cdorg' => $data['cdorg']);

        $exist = $org->exists($criteria);
        if (!$exist) {
            $org = new SysOrg;
            $org->cdorg = $data['cdorg'];
            $org->dscrp = $data['dscrp'];
        } else {
            echo "Code org " . $data['cdorg'] . " sudah terdaftar..!";
            return;
        }

        if (!$org->save())
            print_r($org->getErrors());
    }

    public function actionUpdateOrg() {
        if (isset($_POST['SysOrg'])) {
            $data = $_POST['SysOrg'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['SysOrg'];

                $org = SysOrg::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdorg=:cdorg ';
                $criteria->params = array(':cdorg' => $data['cdorg']);

                $org = $org->find($criteria);
                $org->cdorg = $data['cdorg'];
                $org->dscrp = $data['dscrp'];

                if (!$org->save()) {
                    $trns->rollback();
                    print_r($org->getErrors());
                }
                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteOrg() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $org = SysOrg::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdorg=:cdorg ';
                $i = 0;
                foreach ($data as $row) {
                    $criteria->params = array(':cdorg' => $data[$i]['cdorg']);
                    $org = $org->find($criteria);
                    if (!$org->delete()) {
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

    public function actionUnit() {
        $model = new SysUnit;
        $this->render('unit', array('model' => $model));
    }

    public function actionDataUnit() {
        $dataProvider = new CActiveDataProvider('SysUnit');
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

    public function actionCreateUnit() {
        $data = $_POST['SysUnit'];

        $unt = SysUnit::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdunit=:cdunit AND cdorg=:cdorg ';
        $criteria->params = array(':cdunit' => $data['cdunit'], ':cdorg' => $data['cdorg']);

        $exist = $unt->exists($criteria);
        if (!$exist) {
            $unt = new SysUnit;
            $unt->cdunit = $data['cdunit'];
            $unt->cdorg = $data['cdorg'];
            $unt->dscrp = $data['dscrp'];
        } else {
            echo "Code Unit " . $data['cdunit'] . " untuk Org " . $data['cdorg'] . " sudah terdaftar..!";
            return;
        }

        if (!$unt->save())
            print_r($unt->getErrors());
    }

    public function actionUpdateUnit() {
        if (isset($_POST['SysUnit'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['SysUnit'];
                $unt = SysUnit::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdunit=:cdunit ';
                $criteria->params = array(':cdunit' => $data['cdunit']);

                $unt = $unt->find($criteria);
                $unt->cdunit = $data['cdunit'];
                $unt->cdorg = $data['cdorg'];
                $unt->dscrp = $data['dscrp'];

                if (!$unt->save()) {
                    $trns->rollback();
                    print_r($unt->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteUnit() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $unt = SysUnit::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdunit=:cdunit AND cdorg=:cdorg ';
                $i = 0;
                foreach ($data as $row) {
                    $criteria->params = array(':cdunit' => $data[$i]['cdunit'], ':cdorg' => $data[$i]['cdorg']);
                    $unt = $unt->find($criteria);
                    if (!$unt->delete()) {
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

    public function actionWrhouse() {
        $model = new InvWarehouse;
        $this->render('wrhouse', array('model' => $model));
    }

    public function actionDataWrhouse() {
        $dataProvider = new CActiveDataProvider('InvWarehouse');
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

    public function actionCreateWhse() {
        $data = $_POST['InvWarehouse'];

        $whse = InvWarehouse::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdunit=:cdunit AND cdwhse=:cdwhse ';
        $criteria->params = array(':cdunit' => $data['cdunit'], ':cdwhse' => $data['cdwhse']);

        $exist = $whse->exists($criteria);
        if (!$exist) {
            $whse = new InvWarehouse;
            $whse->cdunit = $data['cdunit'];
            $whse->cdwhse = $data['cdwhse'];
            $whse->dscrp = $data['dscrp'];
        } else {
            echo "Code Whse " . $data['cdwhse'] . " untuk Unit " . $data['cdunit'] . " sudah terdaftar..!";
            return;
        }

        if (!$whse->save())
            print_r($whse->getErrors());
    }

    public function actionUpdateWhse() {
        if (isset($_POST['InvWarehouse'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['InvWarehouse'];

                $whse = InvWarehouse::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdwhse=:cdwhse ';
                $criteria->params = array(':cdwhse' => $data['cdwhse']);

                $whse = $whse->find($criteria);
                $whse->cdunit = $data['cdunit'];
                $whse->cdwhse = $data['cdwhse'];
                $whse->dscrp = $data['dscrp'];

                if (!$whse->save()) {
                    $trns->rollback();
                    print_r($whse->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }

//        if (isset($_POST['updt'])) {
//            $datax = $_POST['updt'];
//            $trns = Yii::app()->db->beginTransaction();
//            try {
//                $data = array();
//                foreach ($datax as $keys => $values) {
//                    foreach ($values as $key => $val) {
//                        if ($key == "name") {
//                            $nama = explode("[", $val);
//                            $nama = str_replace("]", "", $nama[1]);
//                        }
//                        if ($key == "value")
//                            $nilai = $val;
//                    }
//                    $data["$nama"] = $nilai;
//                }
//                $whse = InvWarehouse::model();
//                $criteria = new CDbCriteria;
//                $criteria->condition = 'cdunit=:cdunit AND cdwhse=:cdwhse ';
//                $criteria->params = array(':cdunit' => $data['cdunit'], ':cdwhse' => $data['cdwhse']);
//
//                $whse = $whse->find($criteria);
//                $whse->cdunit = $data['cdunit'];
//                $whse->cdwhse = $data['cdwhse'];
//                $whse->dscrp = $data['dscrp'];
//
//                if (!$whse->save()) {
//                    $trns->rollback();
//                    print_r($whse->getErrors());
//                }
//                $trns->commit();
//            } catch (ErrorException $e) {
//                $trns->rollback();
//            }
//        }
    }

    public function actionDeleteWhse() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $whse = InvWarehouse::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdunit=:cdunit AND cdwhse=:cdwhse ';
                foreach ($data as $row) {
                    $criteria->params = array(':cdunit' => $row['cdunit'], ':cdwhse' => $row['cdwhse']);
                    $whse = $whse->find($criteria);
                    if (!$whse->delete()) {
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

    public function actionLctor() {
        $model = new InvLocator;
        $this->render('lctor', array('model' => $model));
    }

    public function actionDataLctor() {
        $dataProvider = new CActiveDataProvider('InvLocator');
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

    public function actionCreateLctor() {
        $data = $_POST['InvLocator'];
        $lctr = InvLocator::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdloct=:cdloct AND cdwhse=:cdwhse ';
        $criteria->params = array(':cdloct' => $data['cdloct'], ':cdwhse' => $data['cdwhse']);

        $exist = $lctr->exists($criteria);
        if (!$exist) {
            $lctr = new InvLocator;
            $lctr->cdloct = $data['cdloct'];
            $lctr->cdwhse = $data['cdwhse'];
            $lctr->dscrp = $data['dscrp'];
        } else {
            echo "Code Locator " . $data['cdloct'] . " untuk Whse " . $data['cdwhse'] . " sudah terdaftar..!";
            return;
        }

        if (!$lctr->save())
            print_r($lctr->getErrors());
    }

    public function actionUpdateLctor() {
        if (isset($_POST['InvLocator'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['InvLocator'];
                $lctr = InvLocator::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdloct=:cdloct AND cdwhse=:cdwhse ';
                $criteria->params = array(':cdloct' => $data['cdloct'], ':cdwhse' => $data['cdwhse']);

                $lctr = $lctr->find($criteria);
                $lctr->cdloct = $data['cdloct'];
                $lctr->cdwhse = $data['cdwhse'];
                $lctr->dscrp = $data['dscrp'];

                if (!$lctr->save()) {
                    $trns->rollback();
                    print_r($lctr->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteLctor() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $lctr = InvLocator::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdloct=:cdloct AND cdwhse=:cdwhse ';
                $i = 0;
                foreach ($data as $row) {
                    $criteria->params = array(':cdloct' => $data[$i]['cdloct'], ':cdwhse' => $data[$i]['cdwhse']);
                    $lctr = $lctr->find($criteria);
                    if (!$lctr->delete()) {
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

}
