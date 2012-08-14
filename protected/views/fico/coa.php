<?php
$ajaxSave = CHtml::ajax(array(
            'url' => array('createNCoa'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));
Yii::app()->clientScript->registerScript('form', "

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        var data = $('#fico-coaform').serializeArray();
        
        if (!confirm('Are you sure?')) return false;
	else{ 
            $ajaxSave            
	}
});
 
 $('#dgtree').treegrid({
        onClickRow:function(node){
                var level = $('#dgtree').treegrid('getLevel');  
                var parent = $('#dgtree').treegrid('getSelected');  
        
                $('#FicoNcoa_parent_id_coa').val(parent['text']);
                $('#FicoNcoa_parent_id').val(parent['id']);
                $('#FicoNcoa_strata').val(level);
        }
    });
    
function sukses(r,source){
    window.location.reload();
}
");
?>

<?php
$judul = "Chart of Account (COA)";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fico-coaform',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table width="100%">
            <tr>
                <td width="16%"><?php echo $form->labelEx($model,'cdfiacc'); ?></td>
                <td><?php echo $form->textField($model,'cdfiacc',array('size'=>12,'maxlength'=>12)); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td><?php echo $form->textField($model, 'dscrp', array('size' => '42')); ?></td>
            </tr> 
            <tr>
                <td><?php echo CHtml::label("dk-balance", 'id'); //CHtml::Label($model, 'dk') .''.CHtml::activeLabel($model, 'begining_balance'); ?></td>
                <td>
                    <?php 
                    $listnyo = array('D','K');
                    echo CHtml::activeDropDownList($model, 'dk', $listnyo, array('id'=>'dk', 'width' => '700px'));
                    echo '&nbsp;';
                    echo $form->textField($model,'begining_balance',array('size'=>12,'maxlength'=>12));
                    ?>
                </td>
            </tr> 
            <tr>
                <td><?php echo $form->labelEx($model,'parent_id_coa'); ?></td>
                <td>
                    <?php 
                    echo $form->textField($model,'parent_id_coa',array('size'=>'62', 'value'=>'0 - Root', 'readonly' => 'true')); 
                    echo CHtml::hiddenField("FicoNcoa[parent_id]", '0', array('id'=>'FicoNcoa_parent_id'));
                    echo CHtml::hiddenField("FicoNcoa[strata]", '0', array('id'=>'FicoNcoa_strata'));?>
                </td>
            </tr> 
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <?php
        $this->widget('mdmEui.grid.MjbTreegrid', array(
            'id' => 'dgtree',
            'dataUrl' => array('fico/nCoaTree'),
            'options' => array(
                'pagination' => false,
                //'onSelect' => 'js:function(index,row){clickRow();}',
                //'pageSize' => 30,
                //'singleSelect' => true,
                'idField' => 'id',
                'treeField' => 'text',
            ),
            'columns' => array(
                array('field' => 'text', 'title' => 'Account', 'htmlOptions' => array('width' => '360')),
                array('field' => 'create_by', 'title' => 'Create By', 'htmlOptions' => array('width' => '100')),
                array('field' => 'lastupdate_by', 'title' => 'Last Update by', 'htmlOptions' => array('width' => '100')),
                array('field' => 'saldo', 'title' => 'Saldo Awal', 'htmlOptions' => array('width' => '100')),
            ),
            'htmlOptions' => array(
                'rownumbers' => "false",
                'fitColumns' => "true",
                'style' => "height:465px",
            )
        ));
        
//        $this->widget('mdmEui.grid.MjbTreegrid', array(
//            'id' => 'dgtree',
//            'dataUrl' => array('fico/AccTree'),
//            'options' => array(
//                'pagination' => false,
//                //'onSelect' => 'js:function(index,row){clickRow();}',
//                //'pageSize' => 30,
//                //'singleSelect' => true,
//                'idField' => 'id',
//                'treeField' => 'text',
//            ),
//            'columns' => array(
//                array('field' => 'text', 'title' => 'Account', 'htmlOptions' => array('width' => '360')),
//                array('field' => 'create_by', 'title' => 'Create By', 'htmlOptions' => array('width' => '100')),
//                array('field' => 'lastupdate_by', 'title' => 'Last Update by', 'htmlOptions' => array('width' => '100')),
//                array('field' => 'saldo', 'title' => 'Saldo Awal', 'htmlOptions' => array('width' => '100')),
//            ),
//            'htmlOptions' => array(
//                'rownumbers' => "false",
//                'fitColumns' => "true",
//                'style' => "height:465px",
//            )
//        ));
        ?>
    </div><!-- form -->
</div>