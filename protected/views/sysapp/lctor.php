<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createLctor'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateLctor'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteLctor'),
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
    $('#InvLocator_cdloct').removeAttr('readonly');
    $('#InvLocator_cdloct').focus();
    $('#InvLocator_cdloct').val('');
    $('#InvLocator_cdwhse').val('');
    $('#InvLocator_dscrp').val('');
    $('#InvLocator_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#inv-lctor-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#InvLocator_dscrp').removeAttr('readonly');
    $('#InvLocator_cdloct').val('');
    $('#InvLocator_cdwhse').val('');
    $('#InvLocator_dscrp').val(''); 
        
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
        
$('#InvLocator_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#InvLocator_dscrp').focus();
     }
 });
        
function clickRow(){
    $('#InvLocator_cdloct').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#InvLocator_dscrp').focus();
    $('#InvLocator_dscrp').select($('#InvLocator_dscrp').length);
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

$judul = "Locator";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'inv-lctor-form',
            'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdloct'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'cdloct', array('size' => 13, 'maxlength' => 13)); ?>            
                </td>
            </tr>
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php echo $form->textField($model, 'cdwhse', array('size' => 13, 'maxlength' => 13)); ?>            
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
        'dataUrl' => array('dataLctor'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdloct', 'title' => 'Code Locator',
                'htmlOptions' => array('width' => 170, 'align' => 'center'),
                'selector' => '#InvLocator_cdloct'),
            array('field' => 'cdwhse', 'title' => 'Code Whse',
                'htmlOptions' => array('width' => 170, 'align' => 'center'),
                'selector' => '#InvLocator_cdwhse'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#InvLocator_dscrp'),
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