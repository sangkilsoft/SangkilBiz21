<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmEuiWidget
 *
 * @author mdmunir
 */
abstract class MdmEuiWidget extends CWidget {

    /**
     * @var array the initial JavaScript options that should be passed to the JUI plugin.
     */
    public $options = array();

    /**
     * @var array the HTML attributes that should be rendered in the HTML tag representing the JUI widget.
     */
    public $htmlOptions = array();

    /**
     * Initializes the widget.
     * This method will publish JUI assets if necessary.
     * It will also register jquery and JUI JavaScript files and the theme CSS file.
     * If you override this method, make sure you call the parent implementation first.
     */

    public function init() {
        Yii::app()->getClientScript()->registerPackage('jeasyui');
        parent::init();
    }

}

?>
