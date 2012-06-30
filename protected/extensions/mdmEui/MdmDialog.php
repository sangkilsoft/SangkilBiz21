<?php
//Yii::import('mdmEui.MdmEuiWidget');
/**
 * Description of MdmDialog
 *
 * @author mdmunir
 */

class MdmDialog extends MdmEuiWidget {
    //put your code here

    public $tagName='div';

    public function init() {
        parent::init();
        
        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        
        if(empty($this->options)) {
            $this->htmlOptions['class']='easyui-dialog';
        }else {
            $options=CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').dialog($options);");
        }
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
    }

    /**
     * Renders the close tag of the dialog.
     */
    public function run() {
        echo CHtml::closeTag($this->tagName);
    }
}
?>
