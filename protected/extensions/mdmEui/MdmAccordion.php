<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of MdmLayout
 *
 * @author mdmunir
 */
class MdmAccordion extends MdmEuiWidget {
    public $panels=array();
    public $tagName='div';
    public $panelTag='div';

    private $_opened=true;

    public function init() {
        parent::init();

        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;

        if(empty($this->options)) {
            $this->htmlOptions['class']='easyui-accordion';
        }else {
            $options=CJavaScript::encode($this->options);
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').accordion($options);");
        }
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        $this->initPanel();
    }

    /**
     * Renders the content of the portlet.
     */
    public function run() {
        echo CHtml::closeTag($this->tagName);
        $this->_opened = FALSE;
    }

    protected function initPanel() {
        foreach ($this->panels as $panel=>$value) {
            if(is_array($value)) {
                $value['title'] = $panel;
                if(isset ($value['content'])) {
                    $content = $value['content'];
                    unset ($value['content']);
                }else {
                    $content = '';
                }
                echo CHtml::tag($this->panelTag, $value, $content)."\n";
            }elseif (is_string($value)) {
                echo CHtml::tag($this->panelTag, array('title'=>$panel), $value)."\n";
            }
        }
    }

    public function getOpened() {
        return $this->_opened;
    }

    public function beginPanel($title,$option,$content=false) {
        if(!$this->_opened) {
            throw new CException(Yii::t('yii','Panel tidak bisa digunakan diluar accordion.'));
        }
        $option['title'] = $title;
        echo CHtml::openTag($this->panelTag,$option)."\n";
        if($content !== FALSE)
            echo $content."\n";
    }

    public function endPanel() {
        echo CHtml::closeTag($this->panelTag);
    }

    public function panel($title,$option,$content=false) {
        if(!$this->_opened) {
            throw new CException(Yii::t('yii','Panel tidak bisa digunakan diluar accordion.'));
        }
        $option['title'] = $title;
        echo CHtml::tag($this->panelTag, $option,$content);
    }
}
?>
