<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createPeriode'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updatePeriode'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deletePeriode'),
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
    $('#FicoPeriode_nmperiode').removeAttr('readonly');  
    $('#FicoPeriode_nmperiode').focus();
    $('#FicoPeriode_nmperiode').val('');
        
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#fico-periode-form').serializeArray();
            if(tipe == 'New..!') $ajaxsimpan
            else $ajaxupdate
            return false;
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
        
    $('#FicoPeriode_nmperiode').val(''); 
    $('#frDate').val('');
    $('#toDate').val('');
        
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
  
function clickRow(){
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
    $('#trns').html('Update/Delete..!');
        
     var data = $('#dg').mdmegrid('getSelected');
     var actv = data['is_active'];
     var dtfr = data['date_fr'].split('-');
     var dtto = data['date_to'].split('-');

     $('#frDate').val(dtfr[2]+'-'+dtfr[1]+'-'+dtfr[0]);
     $('#toDate').val(dtto[2]+'-'+dtto[1]+'-'+dtto[0]);
     $('#FicoPeriode_is_active_'+actv).attr('checked', 'checked');
        
}
           
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
    //alert('Successfully '+sender+' record..!');
//    if(sender == 'delete') 
        $('#cancelBtn').click();
//    else
//        $('#newBtn').click();
        
    $('#dg').mdmegrid('load');
    $('#trns').html('');
}

function failed(r,sender){
    alert('Failed on '+sender+' record..!');
}  

");

$judul = "Account Periode";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<script type="text/javascript">
    $('#FicoPeriode_is_active').val();
</script>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fico-periode-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <table width="100%" >
            <tr>
                <td width="16%"><?php echo $form->labelEx($model, 'nmperiode'); ?></td>
                <td>
                    <?php 
                    echo $form->textField($model, 'nmperiode',array('size'=>'14','readonly'=>'true'))."&nbsp;".
                            $form->textField($model, 'tahun',array('size'=>'10')); 
                    ?></td>
                <td><?php echo $form->labelEx($model, 'date_fr'); ?></td>
                <td>
                    <?php
                    echo CHtml::activeHiddenField($model, 'id_periode');
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'frDate',
                        'model' => $model,
                        'attribute' => 'date_fr',
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;'
                        ),
                    ));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'is_active'); ?></td>
                <td>
                    <?php
                    $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv',array(':groupv'=>'activeornot')), 'cdlookup', 'dscrp');
                    echo CHtml::activeRadioButtonList($model, 'is_active', $listnyo, array('separator' => ' ', 'labelOptions' => array('style' => 'display:inline')));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'date_to'); ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'toDate',
                        'model' => $model,
                        'attribute' => 'date_to',
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;'
                        ),
                    ));
                    ?>
                </td>
            <tr>
                <td>&nbsp;</td>
                <td></td>
            </tr>
                
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            'dataUrl' => array('dataPeriode'),
            'options' => array(
                'pagination' => true,
                'rownumbers' => false,
                'onSelect' => 'js:function(index,row){clickRow();}',
                'pageSize' => 10,
                'singleSelect' => true,
            ),
            'columns' => array(
                array('field' => 'id_periode', 'title' => 'No',
                    'htmlOptions' => array('width' => 100),
                    'selector' => '#FicoPeriode_id_periode'),
                array('field' => 'nmperiode', 'title' => 'Periode',
                    'htmlOptions' => array('width' => 250),
                    'selector' => '#FicoPeriode_nmperiode'),
                array('field' => 'tahun', 'title' => 'Tahun',
                    'htmlOptions' => array('width' => 150),
                    'selector' => '#FicoPeriode_tahun'),
                array('field' => 'date_fr', 'title' => 'From Date',
                    'htmlOptions' => array('width' => 500),
                    'selector' => '#frDate'),
                array('field' => 'date_to', 'title' => 'To Date',
                    'htmlOptions' => array('width' => 500),
                    'selector' => '#toDate'),
                array('field' => 'is_active', 'title' => 'Active',
                    'htmlOptions' => array('width' => 500),
                    'selector' => '#FicoPeriode_is_active'),
                array('field' => 'update_date', 'title' => 'Last Update',
                    'htmlOptions' => array('width' => 250)),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:706px;height:300px",
            )
        ));
        ?>
    </div><!-- form -->
</div>