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

class MdmLinkButton extends MdmEuiWidget {
    //put your code here
    public $tagName = 'a';
    public $text;
    public $kind='custom';

    public function init() {
        parent::init();

        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        if($this->kind != 'custom') {
            $this->htmlOptions['iconCls']= 'icon-'.$this->kind;
            if(!isset ($this->text))
                $this->text = $this->kind;
        }else {
            if(!isset ($this->text))
                $this->text = '';
        }
        if(empty($this->options)) {
            $this->htmlOptions['class']='easyui-linkbutton';
        }else {
            $options=CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').linkbutton($options);");
        }
        echo CHtml::tag($this->tagName,$this->htmlOptions,$this->text);
    }
}
?>
