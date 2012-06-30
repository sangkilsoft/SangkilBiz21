<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createWhse'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateWhse'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteWhse'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#InvWarehouse_cdwhse').removeAttr('readonly');
    $('#InvWarehouse_cdwhse').focus();
    $('#InvWarehouse_cdwhse').val('');
    $('#InvWarehouse_cdunit').val('');
    $('#InvWarehouse_dscrp').val('');
    $('#InvWarehouse_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#inv-wrhouse-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#InvWarehouse_dscrp').removeAttr('readonly');
    $('#InvWarehouse_cdwhse').val('');
    $('#InvWarehouse_cdunit').val('');
    $('#InvWarehouse_dscrp').val(''); 
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
    $('#trns').html('');
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
        
$('#InvWarehouse_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#InvWarehouse_dscrp').focus();
     }
 });
        
function clickRow(){
    $('#InvWarehouse_cdwhse').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#InvWarehouse_dscrp').focus();
    $('#InvWarehouse_dscrp').select($('#InvWarehouse_dscrp').length);
    $('#trns').html('Update/Delete..!');
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
    $('#trns').html('');
}

function failed(r,sender){
    alert('Failed on '+sender+' record..!');
}
");

if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "master";

$judul = "Warehouse";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'inv-wrhouse-form',
            'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'cdwhse', array('size' => 13, 'maxlength' => 13)); ?>            
                </td>
            </tr>
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td>
                    <?php //echo $form->textField($model, 'cdunit', array('size' => 13, 'maxlength' => 13)); ?>            
                    <?php
                    $listorg = CHtml::listData(SysUnit::model()->FindAll(), 'cdunit', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdunit', $listorg);
                    ?>
                </td>
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
        'dataUrl' => array('dataWrhouse'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,),
        'columns' => array(
            array('field' => 'cdwhse', 'title' => 'Whse Code',
                'htmlOptions' => array('width' => 150, 'align' => 'center'),
                'selector' => '#InvWarehouse_cdwhse'),
            array('field' => 'cdunit', 'title' => 'Unit Code',
                'htmlOptions' => array('width' => 150),
                'selector' => '#InvWarehouse_cdunit'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#InvWarehouse_dscrp'),
            array('field' => 'update_date',
                'title' => 'Last Update',
                'htmlOptions' => array('width' => 200),),
        ),
        'htmlOptions' => array(
            'fitColumns' => "true",
            'style' => "width:707px;height:300px;padding-top:0.2em;",
        )
    ));
    ?>
    <?php $this->endWidget(); ?>
</div>