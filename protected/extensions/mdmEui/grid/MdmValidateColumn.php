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
class MdmValidateColumn extends MdmInputColumn {

    //put your code here
    public $validType;  

    public function generateColumn() {
        $this->editorType = 'validatebox';
        $this->editorOptions = $this->getOption();
        return parent::generateColumn();
    }

    protected function getOption() {
        $option = isset ($this->editorOptions)?$this->editorOptions:array();
        $option['validType'] = $this->validType;
        return $option;
    }

}

?>
