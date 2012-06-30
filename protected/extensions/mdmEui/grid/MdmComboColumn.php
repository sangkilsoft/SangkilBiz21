<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmComboColumn
 *
 * @author mdmunir
 */
class MdmComboColumn extends MdmInputColumn {

    //put your code here
    public $data;
    public $url;  
    public $valueField = 'value';
    public $textField = 'text';

    public function generateColumn() {
        $this->editorType = 'combobox';
        $this->editorOptions = $this->getOption();
        return parent::generateColumn();
    }

    protected function getOption() {
        $option = isset ($this->editorOptions)?$this->editorOptions:array();
        if (isset($this->data))
            $option['data'] = $this->data;
        if (isset($this->url)){
            $option['url'] = CHtml::normalizeUrl($this->url);
        }
        $option['valueField'] = $this->valueField;
        $option['textField'] = $this->textField;

        return $option;
    }

}

?>
