<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'SangkilBiz 2.0',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.jasPHP.*',
    ),
    'aliases' => array(
        'mdmEui' => 'ext.mdmEui',
        'MdmLinkButton' => 'ext.mdmEui.MdmLinkButton',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'bismillah',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    'defaultController' => 'site/index',
    // application components
    'components' => array(
        //'clientScript' => array('scriptMap' => array('styles.css' => '/sangkilbiz2/css/gridview/styles.css')),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'clientScript' => array(
            'packages' => array(
                'jeasyui' => array(
                    'basePath' => 'ext.jeasyui',
                    'js' => array('jquery.easyui.min.js'),
                    'css' => array('themes/default/easyui.css', 'themes/icon.css'),
                    'depends' => array('jquery'),
                )
            )
        ),
        'db' => array(
            //'connectionString' => 'pgsql:host=localhost;port=5432;dbname=sangkilbizdb',
            'connectionString' => 'pgsql:host=localhost;port=5432;dbname=nsangkilbiz',            
            'username' => 'mangkus',
            'password' => 'rahasia',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        /*
          'urlManager' => array(
          'urlFormat' => 'path',
          'showScriptName' => true,
          'rules' => array(
          'post/<id:\d+>/<title:.*?>' => 'post/view',
          'posts/<tag:.*?>' => 'post/index',
          '<controller:\w+>/<action:\w+>/*' => '<controller>/<action>',
          ),
          ),
         * 
         */
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'items',
            'assignmentTable' => 'assignments',
            'itemChildTable' => 'itemchildren',
        ),
        'jasPHP' => array(
            'class' => 'jasPHP',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
