<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmLinkButton
 *
 * @author mdmunir
 */
Yii::import('mdmEui.MdmEuiWidget');

class MjbTree extends MdmEuiWidget {

    public $dataUrl;
    public $saveUrl;

    public function init() {
        parent::init();

        $id = $this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        $this->htmlOptions['class'] = 'easyui-tree';

        if (isset($this->dataUrl))
            $this->htmlOptions['url'] = CHtml::normalizeUrl(Yii::app()->baseUrl.'/index.php?r='.$this->dataUrl);
        if (isset($this->saveUrl))
            $this->htmlOptions['saveUrl'] = CHtml::normalizeUrl(Yii::app()->baseUrl.'/index.php?r='.$this->saveUrl);
        
        echo CHtml::tag('ul', $this->htmlOptions, '');
        $this->options['animate'] = true;

        $options = CJavaScript::encode($this->options);
        $script = <<< SCRIPT
jQuery('#{$id}').tree($options);
SCRIPT;
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, $script);
    }

}

?>
