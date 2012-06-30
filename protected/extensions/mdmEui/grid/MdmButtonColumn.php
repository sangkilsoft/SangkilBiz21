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
class MdmButtonColumn extends MdmDataColumn {

    //put your code here
    public $title = 'action';
    public $field = 'action';
    public $frozen = true;

    public function generateColumn() {      
        $formater = <<< F
function(value,row,index){
    if(row.deleted === true){
        return "{$this->cancelDelButton}";
    }else{
        if(row.editing === true){
            return "{$this->saveButton}{$this->cancelButton}";
        }else{
            return "{$this->editButton}{$this->deleteButton}";
        }
    }
}
F;
        $this->formatter = 'js:'.$formater;
        $this->htmlOptions['align']='center';
        return parent::generateColumn();
    }
    
    protected function getEditButton() {
        $sel = "#{$this->grid->id}";
        return "<a title='edit row' class='l-btn-plain' style='text-decoration: none;' onclick='javascript:$(\\\"$sel\\\").mdmegrid(\\\"editRow\\\", \"+index+\");'><span class='l-btn-left'><span class='l-btn-text icon-edit' style='padding-left: 20px;'>&nbsp;</span></span></a>";
    }

    protected function getSaveButton() {
        $sel = "#{$this->grid->id}";
        return "<a title='save edit' class='l-btn-plain' style='text-decoration: none;' onclick='javascript:$(\\\"$sel\\\").mdmegrid(\\\"saveRow\\\");'><span class='l-btn-left'><span class='l-btn-text icon-save' style='padding-left: 20px; '>&nbsp;</span></span></a>";
    }

    protected function getCancelButton() {
        $sel = "#{$this->grid->id}";
        return "<a title='cancel edit' class='l-btn-plain' style='text-decoration: none;' onclick='javascript:$(\\\"$sel\\\").mdmegrid(\\\"cancelRow\\\");'><span class='l-btn-left'><span class='l-btn-text icon-undo' style='padding-left: 20px; '>&nbsp;</span></span></a>";
    }

    protected function getDeleteButton() {
        $sel = "#{$this->grid->id}";
        return "<a title='delete row' class='l-btn-plain' style='text-decoration: none;' onclick='javascript:$(\\\"$sel\\\").mdmegrid(\\\"deleteRow\\\", \"+index+\");'><span class='l-btn-left'><span class='l-btn-text icon-remove' style='padding-left: 20px; '>&nbsp;</span></span></a>";
    }

    protected function getCancelDelButton() {
        $sel = "#{$this->grid->id}";
        return "<a title='cancel delete' class='l-btn-plain' style='text-decoration: none;' onclick='javascript:$(\\\"$sel\\\").mdmegrid(\\\"cancelDelete\\\", \"+index+\");'><span class='l-btn-left'><span class='l-btn-text icon-undo' style='padding-left: 20px; '>&nbsp;</span></span></a>";
    }

}

?>
