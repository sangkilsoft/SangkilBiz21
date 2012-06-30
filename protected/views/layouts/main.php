<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
<!--        <script src="<?php //echo Yii::app()->request->baseUrl; ?>/css/itemdb.js" type="text/javascript" charset="utf-8"></script>-->

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="header">
            <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
            <div id="logo-desc">Empowering your system</div>            
        </div><!-- header -->
        <div id="topmenu">
            <div class="container" id="mainmenu" >
                <?php
//                $this->widget('MenuBar');
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/site/index')),
                        array('label' => 'Sys Admin', 'url' => array('/sysadmin/index'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Data Master', 'url' => array('/mditem/items'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Purchasing', 'url' => array('/purc/index'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Inventory', 'url' => array('/inv/index'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Sales', 'url' => array('/Sales/index'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Accounting', 'url' => array('/fico/glentri'), 'visible' => !Yii::app()->user->isGuest),
                        //array('label' => 'Contact', 'url' => array('/site/contact')),
                        //array('label' => 'About', 'url' => array('/site/page', 'view' => 'about')),
                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                    ),
                ));
                ?>
            </div><!-- mainmenu -->
        </div>
        <div class="container" id="page">
            <?php echo $content; ?>
        </div><!-- page -->
        <div id="footer">
            <div class="container" id="infooter">
                &copy; <?php echo date('Y'); ?> SangkilSoft.Corp
            </div>
        </div><!-- footer -->
    </body>
</html>