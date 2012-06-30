<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createVendor'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create/update\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteVend'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#Mdvendor_cdvend').removeAttr('readonly');
    $('#Mdvendor_cdvend').focus();
    $('#Mdvendor_cdvend').val('');
    $('#Mdvendor_dscrp').val('');
        
    $('#delBtn').linkbutton('disable');        
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#mdvendor-form').serializeArray();
            $ajaxsimpan
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#Mdvendor_cdvend').removeAttr('readonly');
    $('#Mdvendor_cdvend').val('');
    $('#Mdvendor_cdvend').val(''); 
    $('#Mdvendor_dscrp').val('');
        
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
        
$('#Mdvendor_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#Mdvendor_cdvend').focus();
     }
 });
        
function clickRow(){
    $('#Mdvendor_cdvend').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#Mdvendor_dscrp').focus();
    $('#Mdvendor_dscrp').select($('#Mdvendor_dscrp').length);
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

$judul = "Vendor List";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mdvendor-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="23%"><?php echo $form->labelEx($model, 'cdvend'); ?></td>
                <td><?php echo $form->textField($model, 'cdvend', array('size' => 13, 'maxlength' => 13)); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdvendcat'); ?></td>
                <td>
                    <?php //echo $form->textField($model, 'cdvendcat', array('size' => 13, 'maxlength' => 13)); ?>
                    <?php
                    $listcvend = CHtml::listData(MdvendorCat::model()->FindAll(), 'cdvendcat', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdvendcat', $listcvend);
                    ?>
                </td>
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
        'dataUrl' => array('dataVendor'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cdvend', 'title' => 'Code',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mdvendor_cdvend'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 400),
                'selector' => '#Mdvendor_dscrp'),
            array('field' => 'cdvendcat', 'title' => 'Kd Kategori',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mdvendor_cdvendcat'),
            array('field' => 'kategori', 'title' => 'Nm Kategori',
                'htmlOptions' => array('width' => 100)),
            array('field' => 'update_date', 'title' => 'Last Update',
                'htmlOptions' => array('width' => 250)),
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