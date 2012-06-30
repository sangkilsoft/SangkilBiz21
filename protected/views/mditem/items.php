<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createItems'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateItems'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteItems'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$loaditem = CHtml::ajax(array(
            'url' => array('dataItems'),
            'type' => 'POST',
            'success' => 'js:function(r){entri(r,\'entri\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    
    $('#dg').mdmegrid('unselectAll');
    $('#Mditem_cditem').removeAttr('readonly');
    $('#Mditem_cditem').focus();
    $('#Mditem_cditem').val('');
    $('#Mditem_cduom').val('');
    $('#Mditem_cdgroup').val('');
    $('#Mditem_cdicat').val('');
    $('#Mditem_dscrp').val('');
    $('#Mditem_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#mditem-items-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#Mditem_dscrp').removeAttr('readonly');
    $('#Mditem_cditem').val('');
    $('#Mditem_dscrp').val(''); 
    $('#Mditem_cduom').val('');
    $('#Mditem_cdgroup').val('');
    $('#Mditem_cdicat').val('');
        
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
        
$('#Mditem_dscrp').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#saveBtn').click();
         $('#Mditem_dscrp').focus();
     }
 });
        
function clickRow(){
    $('#Mditem_cditem').attr('readonly', 'true');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#Mditem_dscrp').focus();
    $('#Mditem_dscrp').select($('#Mditem_dscrp').length);
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
        
//$('#entriBtn').click(function(){
//    loaditem;
//});

function entri(r,sender){
    if(r!=''){ 
        var i = 0;
        var obj = jQuery.parseJSON(r);
        var jml = obj.total;
        for(i=0; i<jml; i++){
            addItems(obj.rows[i].cditem, obj.rows[i].lnitem, obj.rows[i].dscrp, obj.rows[i].cduom, obj.rows[i].cdgroup, obj.rows[i].cdicat);
        }
    } 
}
        
//$loaditem        
//init();
");
?>

<?php
$judul = "Item Master";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'mditem-items-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%">
            <tr>
                <td width="16%"><?php echo $form->labelEx($model, 'cditem'); ?></td>
                <td width="45%"><?php echo $form->textField($model, 'cditem', array('size' => 13, 'maxlength' => 13)); ?></td>
                <td width="16%"><?php echo $form->labelEx($model, 'cduom'); ?></td>
                <td>
                    <?php //echo $form->textField($model, 'cduom', array('size' => 4, 'maxlength' => 32)); ?>
                    <?php
                    $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                    echo CHtml::activeDropDownList($model, 'cduom', $listnyo);
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'lnitem'); ?></td>
                <td><?php echo $form->textField($model, 'lnitem', array('size' => 4, 'maxlength' => 32)); ?></td>
                <td><?php echo $form->labelEx($model, 'cdgroup'); ?></td>
                <td><?php
                    //echo $form->textField($model, 'cdgroup', array('size' => 4, 'maxlength' => 32)); 
                    $listnyo = CHtml::listData(MditemGroup::model()->FindAll(), 'cdgroup', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdgroup', $listnyo);
                    ?></td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->textField($model, 'dscrp', array('size' => 32, 'maxlength' => 128)); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php echo $form->labelEx($model, 'cdicat'); ?></td>
                <td style="border-bottom:0px; height: 40px;"><?php
                    //echo $form->textField($model, 'cdicat', array('size' => 4, 'maxlength' => 32)); 
                    $listnyo = CHtml::listData(MditemCategory::model()->FindAll(), 'cdicat', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdicat', $listnyo);
                    ?>
                </td>
            </tr>
        </table>
    </div><!-- form -->
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataItems'),
        'options' => array(
            'pagination' => true,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow();}',
            'pageSize' => 10,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'cditem', 'title' => 'Code',
                'htmlOptions' => array('width' => 250),
                'selector' => '#Mditem_cditem'),
            array('field' => 'lnitem', 'title' => 'Line',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mditem_lnitem'),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),
                'selector' => '#Mditem_dscrp'),
            array('field' => 'cduom', 'title' => 'Uom',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mditem_cduom'),
            array('field' => 'cdgroup', 'title' => 'Group',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mditem_cdgroup'),
            array('field' => 'cdicat', 'title' => 'Categori',
                'htmlOptions' => array('width' => 100),
                'selector' => '#Mditem_cdicat'),
            array('field' => 'update_date', 'title' => 'Last Update',
                'htmlOptions' => array('width' => 250)),
        ),
        'htmlOptions' => array(
            //'rownumbers' => "true",
            'fitColumns' => "true",
            'style' => "width:707px;height:325px",
        )
    ));
    ?>
    <?php $this->endWidget(); ?>
</div>