<?php

Yii::import('zii.widgets.CMenu');

class AddOn extends CMenu {

    public $title = 'User Menu';

    public function init() {
        $this->title = $this->title;
        parent::init();
    }

    protected function renderMenu() {
        echo CHtml::image(Yii::app()->request->baseUrl . '/images/yii70.png','Yii powered', array("style"=>"width:156px;height:34px" )); 
    }
}

?>