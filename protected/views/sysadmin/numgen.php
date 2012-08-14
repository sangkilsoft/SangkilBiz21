<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createNumgen'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateNumgen'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteNumgen'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#SysUnit_cdunit').attr('readonly','true');  
$('#SysUnit_dscrp').attr('readonly','true');
        
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    
    $('#User_username').removeAttr('readonly');
    $('#User_username').focus();
    $('#User_username').val('');
    $('#User_password').val('');
    $('#User_email').val('');
    $('#User_profile').val('');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#numgen-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
        
    $('#User_username').val('');
    $('#User_password').val('');
    $('#User_email').val('');
    $('#User_profile').val('');
        
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
       
function clickRow(){
    $('#delBtn').linkbutton('enable');
//    $('#saveBtn').linkbutton('enable');        
    $('#trns').html('Update/Delete..!');
}
           
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
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

$judul = "Number Generator";
$this->pageTitle = Yii::app()->name . ' - ' . $judul;
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'numgen-form',
            'enableAjaxValidation' => false,
                ));
        ?>

        <?php echo $form->errorSummary($model); ?>
        <table border="0" width="100%">
            <tr>
                <td>
                    <?php echo $form->labelEx($model, 'cdnumgen'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'cdnumgen', array('size' => 13, 'maxlength' => 13)); ?>
                </td>
                <td>
                    <?php echo $form->labelEx($model, 'year'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'year', array('size' => 2, 'maxlength' => 2)); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $form->labelEx($model, 'prefix'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'prefix', array('size' => 8, 'maxlength' => 8)); ?>
                </td>
                <td>
                    <?php echo $form->labelEx($model, 'date'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'date'); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $form->labelEx($model, 'pattern'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'pattern'); ?>
                </td>
                <td>
                    <?php echo $form->labelEx($model, 'last_value'); ?>
                </td>
                <td>
                    <?php echo $form->textField($model, 'last_value'); ?>
                </td>
            </tr>
            <tr >
                <td style="border-bottom: 0px; height: 50px;">
                    <?php echo $form->labelEx($model, 'startnum'); ?>
                </td>
                <td style="border-bottom: 0px;">
                    <?php echo $form->textField($model, 'startnum', array('size' => 13, 'maxlength' => 13)); ?>
                </td>
                <td style="border-bottom: 0px;">
                    <?php echo $form->labelEx($model, 'dscrp'); ?>
                </td style="border-bottom: 0px;">
                <td style="border-bottom: 0px;">
                    <?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?>
                </td>
            </tr>
        </table>

        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            'dataUrl' => array('dataNumgen'),
            'options' => array(
                'pagination' => true,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow();}',
                'pageSize' => 10,
                'singleSelect' => true,
            ),
            'columns' => array(
                array('field' => 'cdnumgen', 'title' => 'Code',
                    'htmlOptions' => array('width' => 150, 'align' => 'left'),
                    'selector' => '#SysNumgen_cdnumgen'),
                array('field' => 'dscrp', 'title' => 'Description',
                    'htmlOptions' => array('width' => 500),
                    'selector' => '#SysNumgen_dscrp'),
                array('field' => 'prefix', 'title' => 'prefix',
                    'htmlOptions' => array('width' => 300),
                    'selector' => '#SysNumgen_prefix'),
                array('field' => 'pattern', 'title' => 'pattern',
                    'htmlOptions' => array('width' => 300),
                    'selector' => '#SysNumgen_pattern'),
                array('field' => 'startnum', 'title' => 'startnum',
                    'htmlOptions' => array('width' => 300),
                    'selector' => '#User_profile'),
                array('field' => 'last_value', 'title' => 'last value',
                    'htmlOptions' => array('width' => 300),
                    'selector' => '#SysNumgen_last_value'),
            ),
            'htmlOptions' => array(
                'fitColumns' => "true",
                'style' => "width:707px;height:300px;padding-top:0.2em;",
            )
        ));
        ?>
        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>