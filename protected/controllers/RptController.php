<?php

class RptController extends Controller {

    public $layout = '//layouts/white';

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
        //$this->render('index');
        $title = 'hello World!';
        Yii::app()->jasPHP->create(getcwd() . '/reports/', 'report1.jrxml', array('title' => $title, 'dtl' => 'ini detail param'));
    }
    
    public function actionGLDtl() {
        //$this->render('index');
        $title = 'hello World!';
        Yii::app()->jasPHP->create(getcwd() . '/reports/', 'report3.jrxml', array('title' => $title, 'dtl' => 'ini detail param'));
    }

}