<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createGroup'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteGroup'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#MditemGroup_cdgroup').removeAttr('readonly');
    $('#MditemGroup_cdgroup').focus();
    $('#MditemGroup_cdgroup').val('');
    $('#MditemGroup_dscrp').val('');
        
    $('#delBtn').linkbutton('disable');        
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#mditem-group-form').serializeArray();
            $ajaxsimpan
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#MditemGroup_cdgroup').removeAttr('readonly');
    $('#MditemGroup_cdgroup').val('');
    $('#MditemGroup_dscrp').val(''); 
        
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
        
$('#MditemGroup_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#MditemGroup_cdgroup').focus();
     }
 });
        
function clickRow(){
    $('#MditemGroup_cdgroup').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#MditemGroup_dscrp').focus();
    $('#MditemGroup_dscrp').select($('#MditemGroup_dscrp').length);
}
           
function sukses(r,sender){
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

$judul = "Item Group";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mditem-group-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdgroup'); ?></td>
                <td><?php echo $form->textField($model, 'cdgroup', array('size' => 13, 'maxlength' => 13)); ?>            </td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?>            </td>
            </tr>
        </table>
    </div><!-- form -->
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataItemgroup'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdgroup', 'title' => 'Group Code',
                'htmlOptions' => array('width' => 100),
                'selector' => '#MditemGroup_cdgroup'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#MditemGroup_dscrp'),
            array('field' => 'update_date',
                'title' => 'Last Update',
                'htmlOptions' => array('width' => 200),),
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

