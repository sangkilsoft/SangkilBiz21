<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MdmGridColumn
 *
 * @author mdmunir
 */
class MdmInputColumn extends MdmDataColumn {

    //put your code here
    public $editorType = 'text';
    public $editorOptions;
    public $required = false;
    public $initEditor; 
    public $onKeydown;

    /**
     * @var array the HTML options for the header cell tag.
     */
    public function generateColumn() {
        $config = parent::generateColumn();
        if($this->required){
            if($this->editorType == 'text')
                $this->editorType = 'validatebox';
            $this->editorOptions['required'] = true;
        }
        if (isset($this->editorOptions)) {
            $config['editor'] = array(
                'type' => $this->editorType,
                'options' => $this->editorOptions,
            );
        } else {
            $config['editor'] = $this->editorType;
        }
        return $config;
    }

}

?>
