<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createUom'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){suksesUom(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteUom'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesUom(r,\'delete\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
        
    $('#MditemUom_cduom').removeAttr('readonly');
    $('#MditemUom_cduom').focus();
    $('#MditemUom_cduom').val('');
    $('#MditemUom_dscrp').val('');
        
    $('#delBtn').linkbutton('disable');        
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#mditem-uom-form').serializeArray();
            $ajaxsimpan
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#MditemUom_cduom').removeAttr('readonly');
    $('#MditemUom_cduom').val('');
    $('#MditemUom_dscrp').val(''); 
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
});        
   
$('#delBtn').click(function(){
    var bisa = $('#delBtn').linkbutton('options');
    if(bisa.disabled) return false;

    var data = $('#dg').mdmegrid('getSelections');
    var jmldata = data.length;
    var pesan = 'Delete '+jmldata+' selected data, Are you sure?';
    if(jmldata>0){
        if (!confirm(pesan)) return false;
	else{
            $ajaxdelete
            return false;
	}
    }
    else alert('No selected row..!');
});
        
$('#MditemUom_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#MditemUom_cduom').focus();
     }
 });
        
function clickRow(){
    $('#MditemUom_cduom').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#MditemUom_dscrp').focus();
    $('#MditemUom_dscrp').select($('#MditemUom_dscrp').length);
}
        
function suksesUom(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
    //alert('Successfully '+sender+' record..!');
    if(sender == 'delete') 
        $('#cancelBtn').click();
    else
        $('#newBtn').click();
        
    $('#dg').mdmegrid('load');
}
        
");
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "master";

$judul = "Item Uom";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mditem-uom-form',
            'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cduom'); ?></td>
                <td><?php echo $form->textField($model, 'cduom', array('size' => 13, 'maxlength' => 13)); ?></td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?></td>
            </tr>
        </table>
    </div><!-- form -->
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataUom'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cduom', 'title' => 'Uom Code',
                'htmlOptions' => array('width' => 100),
                'selector' => '#MditemUom_cduom'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#MditemUom_dscrp'),
            array('field' => 'update_date', 'title' => 'Last Update',
                'htmlOptions' => array('width' => 200)),
        ),
        'htmlOptions' => array(
            //'rownumbers' => "true",
            'fitColumns' => "true",
            'style' => "width:707px;height:300px",
        )
    ));
    ?>
    <?php $this->endWidget(); ?>
</div>
