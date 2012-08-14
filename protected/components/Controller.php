<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    public $mmenu = array(
        'items' => array(
            array('label' => 'Home', 'url' => array('/site/index'), 'itemOptions' => array('class' => 'test')),
            array('label' => 'About', 'url' => array('/site/page', 'view' => 'about'), 'itemOptions' => array('class' => 'icon_chart')),
            array('label' => 'Login', 'url' => array('/site/login')),
        /*
         * array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
         */
        ),
    );

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $oprs = array();

}