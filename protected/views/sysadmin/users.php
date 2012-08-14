<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createUser'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateUser'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteUser'),
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
            var data = $('#user-form').serializeArray();
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
    $('#User_username').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#User_password').val('');
    $('#User_password').focus();
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

$judul = "Users";
$this->pageTitle = Yii::app()->name . ' - ' . $judul;
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
            ));
    ?>
        <table border="0" width="100%">
                <tr>
                    <td><?php echo $form->labelEx($model, 'username'); ?></td>
                    <td><?php echo $form->textField($model, 'username', array('size' => 15, 'maxlength' => 128)); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'password'); ?></td>
                    <td><?php echo $form->passwordField($model, 'password', array('size' => 25, 'maxlength' => 128)); ?></td>
                </tr>
                <tr>
                    <td><?php echo $form->labelEx($model, 'email'); ?></td>
                    <td><?php echo $form->textField($model, 'email', array('size' => 25, 'maxlength' => 128)); ?></td>
                </tr>
                <tr>
                    <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'profile'); ?></td>
                    <td style="border-bottom: none;"><?php echo $form->textArea($model, 'profile', array('rows' => 3, 'cols' => 30)); ?></td>
                </tr>
        </table>
        <br/>
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataUsers'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'username', 'title' => 'User Name',
                'htmlOptions' => array('width' => 150, 'align' => 'left'),
                'selector' => '#User_username'),
            array('field' => 'email', 'title' => 'Email',
                'htmlOptions' => array('width' => 300),
                'selector' => '#User_email'),
            array('field' => 'profile', 'title' => 'Profile',
                'htmlOptions' => array('width' => 500),
                'selector' => '#User_profile'),
        ),
        'htmlOptions' => array(
            'fitColumns' => "true",
            'style' => "width:706px;height:300px;padding-top:0.2em;",
        )
    ));
    ?>
<?php $this->endWidget(); ?>

</div><!-- form -->
</div>

