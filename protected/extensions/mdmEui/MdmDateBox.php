<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmDateBox
 *
 * @author mdmunir
 */
Yii::import('mdmEui.MdmEuiWidget');

class MdmDateBox extends MdmEuiWidget {

    public function init() {
        parent::init();

        $id = $this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        if (empty($this->options)) {
            $this->htmlOptions['class'] = 'easyui-datebox';
        } else {
            $options = CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, "jQuery('#{$id}').datebox($options);");
        }
        echo CHtml::tag('input', $this->htmlOptions, '');
    }

}

?>
