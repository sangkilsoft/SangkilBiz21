<?php

class FicoController extends Controller {

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
            Yii::app()->user->mmenu = "fico";

        $this->render('index');
    }

    public function actionGlentri() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $ActP = FiComp::getActivePeriode();
        $model = new FicoGl;

        $this->render('glentri', array('model' => $model, 'periode' => $ActP));
    }

    public function actionCreateGL() {
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
            $bill = FiComp::createGL($newdata, $datadtl);
            if ($bill['type'] == 'E')
                $trns->rollback();
            else
                $trns->commit();

            echo CJSON::encode($bill);
            return;
        } catch (ErrorException $e) {
            $trns->rollback();
            echo CJSON::encode($e->getMessage());
            return;
        }
    }

    public function actionCoangroup() {
        $model = new FicoCoa;
        $model2 = new FicoCoagroup;
        $this->render('coangroup', array('model' => $model, 'model2' => $model2));
    }

    public function actionCoa() {
        $model = new FicoNcoa;
        $this->render('coa', array('model' => $model));
    }

    public function actionCreateNCoa() {
        $data = $_POST['FicoNcoa'];

        $coa = new FicoNcoa;
        $coa->cdfiacc = $data['cdfiacc'];
        $coa->dscrp = $data['dscrp'];
        $coa->dk = $data['dk'];
        $coa->begining_balance = $data['begining_balance'];
        $coa->parent_id_coa = $data['parent_id'];
        $coa->strata = $data['strata'];

        if (!$coa->save())
            print_r($coa->getErrors());
    }

    private $_idacc = null;

    public function actionNCoaTree() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $this->_idacc = isset($_POST['id']) ? intval($_POST['id']) : '11';
        
        $model = new FicoNcoa;
        $criteria = new CDbCriteria;
        $criteria->condition = 'parent_id_coa =:id';
        $criteria->order = 'id_coa ASC';
        $criteria->params = array(':id' => $this->_idacc);

        $result = array();
        $vncoa = $model->findAll($criteria);
        foreach ($vncoa as $rows) {
            $node = array();
            $node['id'] = $rows['id_coa'];
            $node['dscrp'] = $rows['dscrp'];
            $node['text'] = $rows['cdfiacc'] . "-" . $rows['dscrp'];
            $node['create_by'] = $rows['create_by'];
            $node['lastupdate_by'] = $rows['update_by'];
            $node['saldo'] = number_format($rows['begining_balance']);

            $node['state'] = $this->has_dtlncoa($rows['id_coa']) ? 'closed' : 'open';

            array_push($result, $node);
        }
        echo CJSON::encode($result);
    }

    protected function has_dtlncoa($id) {
        $mdl = new FicoNcoa;
        $crta = new CDbCriteria;
        $crta->condition = 'parent_id_coa=:id';
        $crta->params = array(':id' => $id);

        return $mdl->exists($crta);
    }

    public function actionCreateGroup() {
        $data = $_POST['FicoCoagroup'];
        $group = FicoCoagroup::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdfigroup=:cdfigroup';
        $criteria->params = array(':cdfigroup' => $data['cdfigroup']);
        $exist = $group->exists($criteria);
        if (!$exist) {
            $group = new FicoCoagroup;
            $group->cdfigroup = $data['cdfigroup'];
            $group->dscrp = $data['dscrp'];
            $group->hdr = $data['hdr'];
        } else {
            echo "Code Group " . $data['cdfigroup'] . " telah terdaftar";
            return false;
        }

        if (!$group->save())
            print_r($group->getErrors());
    }

    public function actionCreateCoa() {
        $data = $_POST['FicoCoa'];
        $coa = FicoCoa::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdfiacc=:cdfiacc';
        $criteria->params = array(':cdfiacc' => $data['cdfiacc']);

        $exist = $coa->exists($criteria);
        if (!$exist) {
            $coa = new FicoCoa;
            $coa->cdfiacc = $data['cdfiacc'];
            $coa->dscrp = $data['dscrp'];
            $coa->cdfigroup = $data['cdfigroup'];
            $coa->dk = trim($data['dk']);
        } else {
            echo "Code Group " . $data['cdfiacc'] . " telah terdaftar";
            return false;
        }
        if (!$coa->save())
            print_r($coa->getErrors());
    }

    public function actionUpdateCoa() {
        if (isset($_POST['FicoCoa'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['FicoCoa'];
                $coa = FicoCoa::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdfiacc=:cdfiacc';
                $criteria->params = array(':cdfiacc' => $data['cdfiacc']);

                $coa = $coa->find($criteria);
                $coa->dscrp = $data['dscrp'];
                $coa->cdfigroup = $data['cdfigroup'];
                $coa->dk = trim($data['dk']);

                if (!$coa->save()) {
                    $trns->rollback();
                    print_r($coa->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteCoa() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $coa = FicoCoa::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdfiacc=:cdfiacc';
                foreach ($data as $row) {
                    $criteria->params = array(':cdfiacc' => $row['cdfiacc']);
                    $coa = $coa->find($criteria);
                    if (!$coa->delete()) {
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

    public function actionDataCoa() {
        $model = new FicoCoa;
        $criteria = new CDbCriteria;
        $criteria->order = 'cdfiacc ASC';

        $dataProvider = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        if (isset($_POST['rows'])) {
            $dataProvider->setPagination(array(
                'pageSize' => $_POST['rows'],
                'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
            ));
        }
        $rows = array();
        foreach ($dataProvider->getData() as $row) {
            $r = array();
            foreach ($row as $attr => $val) {
                $r[$attr] = $val;
            }
            $r['group'] = $row->group->dscrp;
            $rows[] = $r;
        }
        echo CJSON::encode(array(
            'total' => $dataProvider->getTotalItemCount(),
            'rows' => $rows, //$dataProvider->getData(),
        ));
    }

    public function actionAutoCoa() {
//        if (Yii::app()->request->isAjaxRequest && isset($_GET['q'])) {
        $name = $_POST['cdacc'];
//            $name = $_GET['q'];
//            $limit = $_GET['limit'];

        $criteria = new CDbCriteria;
        $criteria->select = 'cdfiacc, cdfigroup, dscrp, dk';  // only select the 'title' column
        $criteria->condition = 'lower(trim(cdfiacc)) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->condition .= 'or lower(trim(dscrp)) like \'%' . strtolower(trim($name)) . '%\' ';
        $criteria->order = 'cdfiacc ASC';
//            $criteria->limit = $limit;
        $pro = FicoCoa::model()->findAll($criteria);

        $returnVal = '';
        foreach ($pro as $row) {
            $returnVal .= $row->getAttribute('cdfiacc') . ' ' . $row->getAttribute('dscrp') . '(' . $row->getAttribute('dk') . ')';
            $returnVal .= '|' . $row->getAttribute('dscrp');
            $returnVal .= '|' . $row->getAttribute('cdfiacc');
            $returnVal .= '|' . $row->getAttribute('dk');
            $returnVal .= '|' . $row->getAttribute('cdfigroup');
            $returnVal .= "\n";
        }
        print_r($returnVal);
//        }
    }

    public function actionUpdateGroup() {
        if (isset($_POST['FicoCoagroup'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['FicoCoagroup'];
                $group = FicoCoagroup::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdfigroup=:cdfigroup';
                $criteria->params = array(':cdfigroup' => $data['cdfigroup']);

                $group = $group->find($criteria);
                $group->cdfigroup = $data['cdfigroup'];
                $group->dscrp = $data['dscrp'];
                $group->hdr = $data['hdr'];

                if (!$group->save()) {
                    $trns->rollback();
                    print_r($group->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionDeleteGroup() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $group = FicoCoagroup::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdfigroup=:cdfigroup';
                foreach ($data as $row) {
                    $criteria->params = array(':cdfigroup' => $row['cdfigroup']);
                    $group = $group->find($criteria);
                    if (!$group->delete()) {
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

    public function actionDataGroup() {
        $model = new FicoCoagroup;
        $criteria = new CDbCriteria;
        $criteria->order = 'cdfigroup ASC';

        $dataProvider = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

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

    public function actionGl() {
        $model = new FicoGl;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='fico-gl-gl-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['FicoGl'])) {
            $model->attributes = $_POST['FicoGl'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }

        $this->render('gl', array('model' => $model));
    }

    public function actionPeriode() {
        $model = new FicoPeriode;
        $this->render('periode', array('model' => $model));
    }

    public function actionClosing() {
        $ActP = FiComp::getActivePeriode();
        $model = new FicoGl;
        $this->render('closing', array('model' => $model, 'periode' => $ActP));
    }

    public function actionDataPeriode() {
        $dataProvider = new CActiveDataProvider('FicoPeriode');
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

    public function actionCreatePeriode() {
        $model = new FicoPeriode;
        if (isset($_POST['FicoPeriode'])) {
            $model->attributes = $_POST['FicoPeriode'];
            $model->date_fr = $_POST['frDate'];
            $model->date_to = $_POST['toDate'];
            if (!$model->save())
                print_r($model->getErrors());
        }
    }

    public function actionDeletePeriode() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $fper = FicoPeriode::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'id_periode=:id_periode';

                foreach ($data as $row) {
                    $criteria->params = array(':id_periode' => $row['id_periode']);
                    $fper = $fper->find($criteria);
                    if (!$fper->delete()) {
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

    public function actionUpdatePeriode() {
        if (isset($_POST['FicoPeriode'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['FicoPeriode'];
                $fper = FicoPeriode::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'id_periode=:id_periode';
                $criteria->params = array(':id_periode' => $data['id_periode']);

                $fper = $fper->find($criteria);
                $fper->attributes = $_POST['FicoPeriode'];
                $fper->date_fr = $_POST['frDate'];
                $fper->date_to = $_POST['toDate'];

                if (!$fper->save()) {
                    $trns->rollback();
                    print_r($fper->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionRptGl() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $model = new FicoGl;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='fico-gl-rptGl-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['FicoGl'])) {
            $model->attributes = $_POST['FicoGl'];
            if ($model->validate()) {
                // form inputs are valid, do something here
                return;
            }
        }
        $this->render('rptGl', array('model' => $model));
    }

    public function actionRptbesar() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $model = new FicoGl;
        $this->render('rptbesar', array('model' => $model));
    }

    public function actionAccTree() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $id = isset($_POST['id']) ? intval($_POST['id']) : '0';

        $model = new Vaccount;
        $criteria = new CDbCriteria;
        $criteria->condition = 'parentid=:id';
        $criteria->order = 'id ASC';
        $criteria->params = array(':id' => $id);

        $result = array();
        $vndor = $model->findAll($criteria);
        foreach ($vndor as $rows) {
            $node = array();
            $node['id'] = $rows['id'];
            $node['text'] = $rows['id'] . "-" . $rows['txt'];
            $node['state'] = $this->has_accdtl($rows['id']) ? 'closed' : 'open';
            array_push($result, $node);
        }
        echo CJSON::encode($result);
    }

    protected function has_accdtl($id) {
        $mdl = new Vaccount;
        $crta = new CDbCriteria;
        $crta->condition = 'parentid=:id';
        $crta->params = array(':id' => $id);

        return $mdl->exists($crta);
    }

    public function actionVendorPO() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "fico";

        $id = isset($_POST['id']) ? intval($_POST['id']) : '0';

        $model = new Vhutang;
        $criteria = new CDbCriteria;
        $criteria->condition = 'parentid=:id';
        $criteria->order = 'id ASC';
        $criteria->params = array(':id' => $id);

        $result = array();
        $vndor = $model->findAll($criteria);
        foreach ($vndor as $rows) {
            $node = array();
            $node['id'] = $rows['id'];
            $node['text'] = $rows['txt'];
            $node['state'] = $this->has_child($rows['id']) ? 'closed' : 'open';
            $node['lain'] = "test data tambahan";
            array_push($result, $node);
        }
        echo CJSON::encode($result);
    }

    protected function has_child($id) {
        $mdl = new Vhutang;
        $crta = new CDbCriteria;
        $crta->condition = 'parentid=:id';
        $crta->params = array(':id' => $id);

        return $mdl->exists($crta);
    }

    public function actionFindHutang() {
        $pnum = strtoupper(trim($_POST['pnum']));

        $model = new FicoHutang;
        $criteria = new CDbCriteria;
        $criteria->compare('purch_num', $pnum, false);

        $dthutang = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $dthutang->setPagination(array(
            'pageSize' => 1000,
//            'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
        ));

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $dthutang->getTotalItemCount(),
            'rows' => $dthutang->getData(),
        ));
    }

    public function actionAllHutang() {
        $cdvend = strtoupper(trim($_POST['cdvend']));

        $model = new FicoHutang;
        $criteria = new CDbCriteria;
        $criteria->condition = 'total_hutang > total_bayar ';
        $criteria->compare('cdvend', $cdvend, false);

        $dthutang = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $dthutang->setPagination(array(
            'pageSize' => 1000,
        ));

        $nval = array();
        $i = 0;
        foreach ($dthutang->getData() as $rows) {
            $nval[$i]["purch_num"] = $rows["purch_num"];
            $nval[$i]["date_post"] = $rows["date_post"];
            $nval[$i]["total_hutang"] = $rows["total_hutang"];
            $nval[$i]["total_bayar"] = $rows["total_bayar"];
            $nval[$i]["sisa"] = $rows['total_hutang'] - $rows['total_bayar'];
            $i++;
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $dthutang->getItemCount(),
            'rows' => $nval,
            'footer' => array('total_bayar' => 'Total', 'sisa' => '1800')
        ));
    }

    public function actionFindBayar() {
        $pnum = strtoupper(trim($_POST['pnum']));

        $model = new FicoBayar;
        $criteria = new CDbCriteria;
        $criteria->compare('purch_num', $pnum, false);

        $dtbayar = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));

        $dtbayar->setPagination(array(
            'pageSize' => 1000,
        ));

        if (!$dtbayar->getTotalItemCount() > 0) {
            echo CJSON::encode(array(
                'type' => 'E', 'message' => 'Tidak ada pembayaran untuk ' . $pnum
            ));
            return;
        }

        $nval = array();
        $i = 0;
        foreach ($dtbayar->getData() as $rows) {
            $nval[$i]["jml_bayar"] = $rows["jml_bayar"];
            $nval[$i]["cdfigl"] = $rows["cdfigl"];
            $ntgl = split(' ', $rows["create_date"]);
            $nval[$i]["create_date"] = $ntgl[0];
            $i++;
        }

        echo CJSON::encode(array(
            'type' => 'S',
            'total' => $dtbayar->getTotalItemCount(),
            'rows' => $nval,
        ));
    }

    public function actionFindGL() {
        $data_src = $_POST['FicoGl'];
        $model = new FicoGl;
        $criteria = new CDbCriteria;
        //$criteria->select = 'cdfigl';

        if ($data_src['cdunit'] !== "")
            $criteria->condition .= 'cdunit =:cdunit AND ';

        $criteria->condition .= 'date(gl_date) between to_date(:gl_date,\'dd-mm-yyyy\') AND to_date(:gl_date2,\'dd-mm-yyyy\')';

        if ($data_src['cdunit'] !== "")
            $criteria->params = array(':cdunit' => $data_src['cdunit'], ':gl_date' => $data_src['gl_date'], ':gl_date2' => $data_src['gl_date2']);
        else
            $criteria->params = array(':gl_date' => $data_src['gl_date'], ':gl_date2' => $data_src['gl_date2']);

        $hdrgl = $model->findAll($criteria);
        $dtl = array();
        if (count($hdrgl) > 0) {
            $i = 0;
            foreach ($hdrgl as $row) {
                $dtl[$i]['idgldtl'] = '';
                $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
                $dtl[$i]['cdfiacc'] = '';
                $dtl[$i]['debit'] = '';
                $dtl[$i]['kredit'] = '';

                $dtl[$i]['create_by'] = '';
                $dtl[$i]['create_date'] = '';
                $ndate = split('-', $row->getAttribute('gl_date'));
                $dtl[$i]['gl_date'] = $ndate[2] . '-' . $ndate[1] . '-' . $ndate[0];
                $dtl[$i]['dscrp'] = strtoupper($row->getAttribute('dscrp'));
                $dtl[$i]['cdunit'] = $row->getAttribute('cdunit');
                $dtl[$i]['coadscrp'] = '';
                $i++;

                $model2 = new FicoGldtl;
                $criteria2 = new CDbCriteria;
                $criteria2->condition = 'cdfigl =:cdfigl';
                $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'));
                //$criteria2->order = 'cdfigl DESC, debit DESC, kredit DESC';

                $dtlpro = new CActiveDataProvider($model2, array(
                            'criteria' => $criteria2));

                foreach ($dtlpro->getData() as $rows) {
                    if ($rows['debit'] == 0)
                        $dtl[$i]['debit'] = '-';
                    else
                        $dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');

                    if ($rows['kredit'] == 0) {
                        $dtl[$i]['cdfigl'] = $rows['cdfiacc'];
                        $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = $rows->coa->dscrp;
                        $dtl[$i]['kredit'] = '-';
                    } else {
                        $dtl[$i]['cdfigl'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $rows['cdfiacc'];
                        $dtl[$i]['cdfiacc'] = '&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;;' . $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $rows->coa->dscrp;
                        $dtl[$i]['kredit'] = number_format($rows['kredit'], 0, '.', ',');
                    }

                    $dtl[$i]['idgldtl'] = $rows['idgldtl'];
                    $dtl[$i]['create_by'] = $rows['create_by'];
                    $dtl[$i]['create_date'] = $rows['create_date'];
                    $dtl[$i]['gl_date'] = '';
                    $dtl[$i]['cdunit'] = '';
                    $dtl[$i]['coadscrp'] = $rows->coa->dscrp;
                    $i++;
                }
            }
        }

        if (count(count($dtl)) > 0) {
            echo CJSON::encode(array(
                'type' => 'S',
                'total' => count($dtl),
                'rows' => $dtl,
            ));
            return;
        } else {
            echo CJSON::encode(array('type' => 'E', 'message' => 'Data tidak ditemukan'));
            return;
        }
    }

    /*
      public function actionFindBB() {
      $data_src = $_POST['FicoGl'];
      //echo CJSON::encode($data_src);

      $model = new FicoGl;
      $criteria = new CDbCriteria;

      if ($data_src['cdunit'] !== "")
      $criteria->condition .= 'cdunit =:cdunit AND ';
      $criteria->condition .= 'date(gl_date) between to_date(:gl_date,\'dd-mm-yyyy\') AND to_date(:gl_date2,\'dd-mm-yyyy\')';
      if ($data_src['cdunit'] !== "")
      $criteria->params = array(':cdunit' => $data_src['cdunit'], ':gl_date' => $data_src['gl_date'], ':gl_date2' => $data_src['gl_date2']);
      else
      $criteria->params = array(':gl_date' => $data_src['gl_date'], ':gl_date2' => $data_src['gl_date2']);

      $hdrgl = $model->findAll($criteria);
      $dtl = array();
      if (count($hdrgl) > 0) {
      $i = 0;
      foreach ($hdrgl as $row) {
      $model2 = new FicoGldtl;
      $criteria2 = new CDbCriteria;
      $criteria2->condition = 'cdfigl =:cdfigl AND cdfiacc=:cdfiacc';
      $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'), ':cdfiacc' => $data_src['acc_id']);
      $criteria2->order = 'cdfigl DESC, debit DESC, kredit DESC';

      $dtlpro = new CActiveDataProvider($model2, array(
      'criteria' => $criteria2));

      foreach ($dtlpro->getData() as $rows) {
      $ndate = split('-', $row->getAttribute('gl_date'));
      $dtl[$i]['gl_date'] = $ndate[2] . '-' . $ndate[1] . '-' . $ndate[0];
      if ($rows['kredit'] == 0) {
      $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
      $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
      $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;$dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
      $dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
      $dtl[$i]['kredit'] = '-';
      } else {
      $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
      $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
      $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;
      $dtl[$i]['debit'] = '-';
      $dtl[$i]['kredit'] = number_format($rows['kredit'], 0, '.', ',');
      }

      $dtl[$i]['idgldtl'] = $rows['idgldtl'];
      $dtl[$i]['create_by'] = $rows['create_by'];
      $dtl[$i]['create_date'] = $rows['create_date'];
      //$dtl[$i]['gl_date'] = '';
      //$dtl[$i]['cdunit'] = '';
      $dtl[$i]['coadscrp'] = $rows->coa->dscrp;
      $i++;
      }
      }
      }

      if (count(count($dtl)) > 0) {
      echo CJSON::encode(array(
      'type' => 'S',
      'total' => count($dtl),
      'rows' => $dtl,
      ));
      return;
      } else {
      echo CJSON::encode(array('type' => 'E', 'message' => 'Data tidak ditemukan'));
      return;
      }
      }
     */

    public function actionFindBB4Closing() {
        $data_src = $_POST['FicoGl'];
        if ($data_src['acc_id'] == "") {
            echo CJSON::encode(array('type' => 'E', 'message' => 'Pilih Account..!'));
            return;
        }

//        echo CJSON::encode($data_src);
//        return;

        $criteria = new CDbCriteria;
        $criteria->condition = 'id_periode =:id_periode ';
        if ($data_src['cdunit'] !== "") {
            $criteria->condition .= ' AND cdunit =:cdunit ';
            $criteria->params = array(':id_periode' => $data_src['periode_id'], ':cdunit' => $data_src['cdunit']);
        } else
            $criteria->params = array(':id_periode' => $data_src['periode_id']);

        //$criteria->condition .= 'date(gl_date) between to_date(:gl_date,\'dd-mm-yyyy\') AND to_date(:gl_date2,\'dd-mm-yyyy\')';

        $model = new FicoGl;
        $hdrgl = $model->findAll($criteria);
        $saldoawal = 100000;
        $dtl = array();
        if (count($hdrgl) > 0) {
            $i = 0;
            $saldo = $saldoawal;
            $dtl[$i]['gl_date'] = "";
            $dtl[$i]['cdfigl'] = "";
            $dtl[$i]['cdfiacc'] = "";
            $dtl[$i]['dscrp'] = "SALDO AWAL PERIODE"; //$rows->coa->dscrp;$dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
            $dtl[$i]['debit'] = "";
            $dtl[$i]['kredit'] = "";

            $dtl[$i]['idgldtl'] = "";
            $dtl[$i]['create_by'] = "";
            $dtl[$i]['create_date'] = "";
            //$dtl[$i]['gl_date'] = '';
            //$dtl[$i]['cdunit'] = '';
            $dtl[$i]['coadscrp'] = "";
            $dtl[$i]['saldo'] = number_format($saldo, 0, '.', ',');
            $i++;
            foreach ($hdrgl as $row) {
                $model2 = new FicoGldtl;
                $criteria2 = new CDbCriteria;
                if ($data_src['acc_id'] !== "") {
                    $criteria2->condition = 'cdfigl =:cdfigl AND cdfiacc=:cdfiacc';
                    $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'), ':cdfiacc' => $data_src['acc_id']);
                } else {
                    $criteria2->condition = 'cdfigl =:cdfigl';
                    $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'));
                }

                $criteria2->order = 'cdfigroup ASC, cdfigl ASC, debit DESC, kredit DESC';

                $dtlpro = new CActiveDataProvider($model2, array(
                            'criteria' => $criteria2));
                foreach ($dtlpro->getData() as $rows) {
                    $saldo += $rows['debit'] - $rows['kredit'];
                    $ndate = split('-', $row->getAttribute('gl_date'));
                    $dtl[$i]['gl_date'] = $ndate[2] . '-' . $ndate[1] . '-' . $ndate[0];
                    if ($rows['kredit'] == 0) {
                        $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
                        $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;$dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
                        $dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
                        $dtl[$i]['kredit'] = '-';
                        //$saldo += $rows['debit'];
                    } else {
                        $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
                        $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;
                        $dtl[$i]['debit'] = '-';
                        $dtl[$i]['kredit'] = number_format($rows['kredit'], 0, '.', ',');
                        //$saldo -= $rows['kredit'];
                    }

                    $dtl[$i]['idgldtl'] = $rows['idgldtl'];
                    $dtl[$i]['create_by'] = $rows['create_by'];
                    $dtl[$i]['create_date'] = $rows['create_date'];
                    //$dtl[$i]['gl_date'] = '';
                    //$dtl[$i]['cdunit'] = '';
                    $dtl[$i]['coadscrp'] = $rows->coa->dscrp;
                    $dtl[$i]['saldo'] = number_format($saldo, 0, '.', ',');
                    $i++;
                }
            }
        }

        if (count(count($dtl)) > 0) {
            echo CJSON::encode(array(
                'type' => 'S',
                'total' => count($dtl),
                'rows' => $dtl,
            ));
            return;
        } else {
            echo CJSON::encode(array('type' => 'E', 'message' => 'Data tidak ditemukan'));
            return;
        }
    }

    public function actionFindBB() {
        $data_src = $_POST['FicoGl'];
        if ($data_src['acc_id'] == "") {
            echo CJSON::encode(array('type' => 'E', 'message' => 'Pilih Account..!'));
            return;
        }

//        echo CJSON::encode($data_src);
//        return;

        $criteria = new CDbCriteria;
        $criteria->condition = 'id_periode =:id_periode ';
        if ($data_src['cdunit'] !== "") {
            $criteria->condition .= ' AND cdunit =:cdunit ';
            $criteria->params = array(':id_periode' => $data_src['periode_id'], ':cdunit' => $data_src['cdunit']);
        } else
            $criteria->params = array(':id_periode' => $data_src['periode_id']);

        //$criteria->condition .= 'date(gl_date) between to_date(:gl_date,\'dd-mm-yyyy\') AND to_date(:gl_date2,\'dd-mm-yyyy\')';

        $model = new FicoGl;
        $hdrgl = $model->findAll($criteria);
        $saldoawal = 100000;
        $dtl = array();
        if (count($hdrgl) > 0) {
            $i = 0;
            $saldo = $saldoawal;
            $dtl[$i]['gl_date'] = "";
            $dtl[$i]['cdfigl'] = "";
            $dtl[$i]['cdfiacc'] = "";
            $dtl[$i]['dscrp'] = "SALDO AWAL PERIODE"; //$rows->coa->dscrp;$dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
            $dtl[$i]['debit'] = "";
            $dtl[$i]['kredit'] = "";

            $dtl[$i]['idgldtl'] = "";
            $dtl[$i]['create_by'] = "";
            $dtl[$i]['create_date'] = "";
            //$dtl[$i]['gl_date'] = '';
            //$dtl[$i]['cdunit'] = '';
            $dtl[$i]['coadscrp'] = "";
            $dtl[$i]['saldo'] = number_format($saldo, 0, '.', ',');
            $i++;
            foreach ($hdrgl as $row) {
                $model2 = new FicoGldtl;
                $criteria2 = new CDbCriteria;
                if ($data_src['acc_id'] !== "") {
                    $criteria2->condition = 'cdfigl =:cdfigl AND cdfiacc=:cdfiacc';
                    $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'), ':cdfiacc' => $data_src['acc_id']);
                } else {
                    $criteria2->condition = 'cdfigl =:cdfigl';
                    $criteria2->params = array(':cdfigl' => $row->getAttribute('cdfigl'));
                }

                $criteria2->order = 'cdfigroup ASC, cdfigl ASC, debit DESC, kredit DESC';

                $dtlpro = new CActiveDataProvider($model2, array(
                            'criteria' => $criteria2));
                foreach ($dtlpro->getData() as $rows) {
                    $saldo += $rows['debit'] - $rows['kredit'];
                    $ndate = split('-', $row->getAttribute('gl_date'));
                    $dtl[$i]['gl_date'] = $ndate[2] . '-' . $ndate[1] . '-' . $ndate[0];
                    if ($rows['kredit'] == 0) {
                        $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
                        $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;$dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
                        $dtl[$i]['debit'] = number_format($rows['debit'], 0, '.', ',');
                        $dtl[$i]['kredit'] = '-';
                        //$saldo += $rows['debit'];
                    } else {
                        $dtl[$i]['cdfigl'] = $row->getAttribute('cdfigl');
                        $dtl[$i]['cdfiacc'] = $rows['cdfiacc'];
                        $dtl[$i]['dscrp'] = $row->getAttribute('dscrp'); //$rows->coa->dscrp;
                        $dtl[$i]['debit'] = '-';
                        $dtl[$i]['kredit'] = number_format($rows['kredit'], 0, '.', ',');
                        //$saldo -= $rows['kredit'];
                    }

                    $dtl[$i]['idgldtl'] = $rows['idgldtl'];
                    $dtl[$i]['create_by'] = $rows['create_by'];
                    $dtl[$i]['create_date'] = $rows['create_date'];
                    //$dtl[$i]['gl_date'] = '';
                    //$dtl[$i]['cdunit'] = '';
                    $dtl[$i]['coadscrp'] = $rows->coa->dscrp;
                    $dtl[$i]['saldo'] = number_format($saldo, 0, '.', ',');
                    $i++;
                }
            }
        }

        if (count(count($dtl)) > 0) {
            echo CJSON::encode(array(
                'type' => 'S',
                'total' => count($dtl),
                'rows' => $dtl,
            ));
            return;
        } else {
            echo CJSON::encode(array('type' => 'E', 'message' => 'Data tidak ditemukan'));
            return;
        }
    }

}