<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createOrg'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateOrg'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteOrg'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#SysOrg_cdorg').attr('readonly','true');  
$('#SysOrg_dscrp').attr('readonly','true');
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#SysOrg_cdorg').removeAttr('readonly');
    $('#SysOrg_cdorg').focus();
    $('#SysOrg_cdorg').val('');
    $('#SysOrg_dscrp').val('');
    $('#SysOrg_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('disable');   
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#sys-Orgn-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#SysOrg_dscrp').removeAttr('readonly');
    $('#SysOrg_cdorg').val('');
    $('#SysOrg_dscrp').val(''); 
        
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
        
$('#SysOrg_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#SysOrg_dscrp').focus();
     }
 });
        
function clickRow(){
    $('#SysOrg_cdorg').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#SysOrg_dscrp').focus();
    $('#SysOrg_dscrp').select($('#SysOrg_dscrp').length);
    $('#trns').html('Update/Delete..!');
}
           
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
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
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "master";

$judul = "Organization";
$this->pageTitle = Yii::app()->name . " - $judul";

$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'sys-Orgn-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdorg'); ?></td>
                <td>
                    <div>
                        <?php echo $form->textField($model, 'cdorg', array('size' => 13, 'maxlength' => 13)); ?>  
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 32)); ?>            </td>
            </tr>
        </table>    
    </div><!-- form -->
    <br/>
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataOrg'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdorg', 'title' => 'Code',
                'htmlOptions' => array('width' => 100, 'align' => 'center'),
                'selector' => '#SysOrg_cdorg'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#SysOrg_dscrp'),
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