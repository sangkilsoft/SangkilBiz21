<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createVendorcat'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteVCat'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#MdvendorCat_cdvendcat').removeAttr('readonly');
    $('#MdvendorCat_cdvendcat').focus();
    $('#MdvendorCat_cdvendcat').val('');
    $('#MdvendorCat_dscrp').val('');
        
    $('#delBtn').linkbutton('disable');        
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#mdvendor-cat-form').serializeArray();
            $ajaxsimpan
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#MdvendorCat_cdvendcat').removeAttr('readonly');
    $('#MdvendorCat_cdvendcat').val('');
    $('#MdvendorCat_cdvendcat').val(''); 
    $('#MdvendorCat_dscrp').val('');
        
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
        
$('#MdvendorCat_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#MdvendorCat_cdvendcat').focus();
     }
 });
        
function clickRow(){
    $('#MdvendorCat_cdvendcat').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#MdvendorCat_dscrp').focus();
    $('#MdvendorCat_dscrp').select($('#MdvendorCat_dscrp').length);
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
        
function failed(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
    alert('Failed '+sender+' record..!');
}        
");
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "master";

$judul = "Vendor Category";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mdvendor-cat-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdvendcat'); ?></td>
                <td><?php echo $form->textField($model, 'cdvendcat', array('size' => 13, 'maxlength' => 13)); ?></td>
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
        'dataUrl' => array('dataVendorcat'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdvendcat', 'title' => 'Vendor Code',
                'htmlOptions' => array('width' => 100),
                'selector' => '#MdvendorCat_cdvendcat'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#MdvendorCat_dscrp'),
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