<?php

Yii::import('zii.widgets.CMenu');

class MenuBar extends CMenu {

    protected function renderMenu() {
        echo "<div class=\"menubar\">";
        $this->widget('ext.mdmEui.MdmLinkButton', array(
            'id' => 'newBtn',
            'text' => 'New',
            'htmlOptions' => array('iconCls' => 'icon-add', 'plain' => 'true')
        ));
        $this->widget('ext.mdmEui.MdmLinkButton', array(
            'id' => 'delBtn',
            'text' => 'Delete',
            'htmlOptions' => array('iconCls' => 'icon-no', 'plain' => 'true', 'disabled' => 'disabled')
        ));
        $this->widget('ext.mdmEui.MdmLinkButton', array(
            'id' => 'saveBtn',
            'text' => 'Save',
            'htmlOptions' => array('iconCls' => 'icon-save', 'plain' => 'true', 'disabled' => 'disabled')
        ));
        $this->widget('ext.mdmEui.MdmLinkButton', array(
            'id' => 'printBtn',
            'text' => 'Print',
            'htmlOptions' => array('iconCls' => 'icon-print', 'plain' => 'true', 'disabled' => 'disabled')
        ));
        $this->widget('ext.mdmEui.MdmLinkButton', array(
            'id' => 'cancelBtn',
            'text' => 'Cancel',
            'htmlOptions' => array('iconCls' => 'icon-undo', 'plain' => 'true')
        ));
        echo CHtml::label('', '', array('id' => 'trns', 'style' => "color:#AA2808;font-size:0.85em;"));
        echo "</div>";
    }

}

?>