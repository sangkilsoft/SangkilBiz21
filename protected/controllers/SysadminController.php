<?php

class SysadminController extends Controller {

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
                'actions' => array('create', 'update', 'admin', 'delete'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "adm";
        $this->render('index');
    }

    public function actionUsers() {
        $model = new User;

        $this->render('users', array(
            'model' => $model,
        ));
    }

    public function actionDataUsers() {
        $dataProvider = new CActiveDataProvider('User');
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

    public function actionDataUserunit() {
        $dataProvider = new CActiveDataProvider('Userunit');
        if (isset($_POST['rows'])) {
            $dataProvider->setPagination(array(
                'pageSize' => $_POST['rows'],
                'currentPage' => isset($_POST['page']) ? $_POST['page'] - 1 : 0,
            ));
        }
        $dataLoad = array();
        $i = 0;
        foreach ($dataProvider->getData() as $rows) {
            $dataLoad[$i]['id'] = $rows['id'];
            $dataLoad[$i]['cdunit'] = $rows['cdunit'];
            $dataLoad[$i]['dscrp'] = $rows->usr->username . " Assigned to " . $rows->unt->dscrp; //$rows['dscrp']; 
            $dataLoad[$i]['nama'] = $rows->usr->username;
            $dataLoad[$i]['unit'] = $rows->unt->dscrp;
            $i++;
        }
        echo CJSON::encode(array(
            'total' => $dataProvider->getTotalItemCount(),
            'rows' => $dataLoad, //$dataProvider->getData(),
        ));
    }

    public function actionCreateUser() {
        $data = $_POST['User'];

        $user = User::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'username=:username ';
        $criteria->params = array(':username' => $data['username']);

        $exist = $user->exists($criteria);
        if (!$exist) {
            $user = new User;
            $user->attributes = $data;
            $user->salt = $user->generateSalt();
            $user->password = $user->hashPassword($user->password, $user->salt);
        } else {
            echo "Username '" . $data['username'] . "' sudah terdaftar..!";
            return;
        }

        if (!$user->save())
            print_r($user->getErrors());
    }

    public function actionDeleteUser() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];
            $trns = Yii::app()->db->beginTransaction();
            try {
                $user = User::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'username=:username ';

                foreach ($data as $row) {
                    $criteria->params = array(':username' => $row['username']);
                    $user = $user->find($criteria);
                    if (!$user->delete()) {
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

    public function actionUpdateUser() {
        $retval = array();
        if (isset($_POST['User'])) {
            $trns = Yii::app()->db->beginTransaction();
            try {
                $data = $_POST['User'];
                if ($data['password'] == '')
                    $retval = array('type' => 'E', 'message' => 'Password tidak boleh kosong ..!');
                else if (strlen($data['password']) <= 6)
                    $retval = array('type' => 'E', 'message' => 'Panjang Password minimal 6 karakter ..!');

                if (count($retval) > 0) {
                    print_r(json_encode($retval));
                    return;
                }

                $user = User::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'username=:username ';
                $criteria->params = array(':username' => $data['username']);

                $user = $user->find($criteria);
                $user->attributes = $data;
                $user->salt = $user->generateSalt();
                $user->password = $user->hashPassword($user->password, $user->salt);

                if (!$user->save()) {
                    $trns->rollback();
                    print_r($user->getErrors());
                }

                $trns->commit();
            } catch (ErrorException $e) {
                $trns->rollback();
            }
        }
    }

    public function actionUserunit() {
        $model = new Userunit;
        if (isset($_POST['Userunit'])) {
            $model->attributes = $_POST['Userunit'];
            $model->is_default = true;
            if (!$model->save())
                $error = $model->getErrors();
        }
        $this->render('userunit', array('model' => $model));
    }

    public function actionNumgen() {
        $model = new SysNumgen;
        if (isset($_POST['SysNumgen'])) {
            $model->attributes = $_POST['SysNumgen'];
            if ($model->save())
                $this->render('numgen', array('model' => $model));
        }
        $this->render('numgen', array('model' => $model));
    }

    public function actionCreateNumgen() {
        $data = $_POST['SysNumgen'];
        
        $numGen = SysNumgen::model();
        $criteria = new CDbCriteria;
        $criteria->condition = 'cdnumgen=:cdnumgen ';
        $criteria->params = array(':cdnumgen' => $data['cdnumgen']);     

        $exist = $numGen->exists($criteria);
        if (!$exist) {
            $numGen = new SysNumgen;
            $numGen->attributes = $data;
        } else {
            echo "Code cdnumgen " . $data['cdnumgen'] . " sudah terdaftar..!";
            return;
        }

        if (!$numGen->save())
            print_r($numGen->getErrors());
    }

    public function actionDeleteNumgen() {
        if (isset($_POST['del'])) {
            $data = $_POST['del'];

            $trns = Yii::app()->db->beginTransaction();
            try {
                $numGen = SysNumgen::model();
                $criteria = new CDbCriteria;
                $criteria->condition = 'cdnumgen=:cdnumgen ';
                foreach ($data as $row) {
                    $criteria->params = array(':cdnumgen' => $row['cdnumgen']);
                    $numGen = $numGen->find($criteria);
                    if (!$numGen->delete()) {
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

    public function actionDataNumgen() {
        $dataProvider = new CActiveDataProvider('SysNumgen');
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

    public function actionVlookup() {
        $model = new Vlookup;
        if (isset($_POST['Vlookup'])) {
            $model->attributes = $_POST['Vlookup'];
            if (!$model->save())
                $error = $model->getErrors();
            $model = new Vlookup;
        }
        $this->render('vlookup', array('model' => $model));
    }

    public function actionDataLookup() {
        $model = new Vlookup;
        $criteria = new CDbCriteria;
        $criteria->order = 'groupv ASC, trim(cdlookup) ASC';
        $dataProvider = new CActiveDataProvider($model, array(
                    'criteria' => $criteria));
        
        $dataProvider->setPagination(array(
            'pageSize' => 1000,
        ));
        
        echo CJSON::encode(array(
            'total' => $dataProvider->getTotalItemCount(),
            'rows' => $dataProvider->getData(),
        ));
    }

}
