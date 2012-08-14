<?php
$ajaxsimpang = CHtml::ajax(array(
            'url' => array('createGroup'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdateg = CHtml::ajax(array(
            'url' => array('updateGroup'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxdeleteg = CHtml::ajax(array(
            'url' => array('deleteGroup'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$ajaxsimpand = CHtml::ajax(array(
            'url' => array('createCoa'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdated = CHtml::ajax(array(
            'url' => array('updateCoa'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxdeleted = CHtml::ajax(array(
            'url' => array('deleteCoa'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
      
    var act = $('#tt').tabs('getSelected');  
    var tabtitle = act.panel('options').title;    // the corresponding tab object  
    if(tabtitle == 'COA Group'){
        $('#dg').mdmegrid('unselectAll');
        $('#FicoCoagroup_cdfigroup').removeAttr('readonly');
        $('#FicoCoagroup_cdfigroup').focus();
        $('#FicoCoagroup_cdfigroup').val('');
        $('#FicoCoagroup_dscrp').val('');
        $('#FicoCoagroup_dscrp').removeAttr('readonly');
    }else{
        $('#dg2').mdmegrid('unselectAll');
        $('#FicoCoa_cdfiacc').removeAttr('readonly');
        $('#FicoCoa_cdfiacc').focus();
        $('#FicoCoa_cdfiacc').val('');
        $('#FicoCoa_dscrp').val('');
        $('#FicoCoa_dscrp').removeAttr('readonly');
    }
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();
        var act = $('#tt').tabs('getSelected');  
        var tabtitle = act.panel('options').title; 
        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{ 
            if(tabtitle == 'COA Group'){
                var data = $('#fico-coagroup-form').serializeArray();
                if(tipe == 'New..!') 
                    $ajaxsimpang
                else 
                    $ajaxupdateg
        
                return false;
            }else{
                var data = $('#fico-coa-form').serializeArray();
                if(tipe == 'New..!') 
                    $ajaxsimpand
                else 
                    $ajaxupdated
        
                return false;
            }
            
	}
});
    
$('#cancelBtn').click(function(){
    $('#dg').mdmegrid('unselectAll');
    $('#dg2').mdmegrid('unselectAll');
        
    $('#FicoCoagroup_cdfigroup').val('');
    $('#FicoCoagroup_dscrp').val(''); 
    $('#FicoCoa_cdfiacc').val(''); 
    $('#FicoCoa_dscrp').val(''); 
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
    $('#trns').html('');
});        
   
$('#delBtn').click(function(){
    var act = $('#tt').tabs('getSelected');  
    var tabtitle = act.panel('options').title; 
    var bisa = $('#delBtn').linkbutton('options');
    if(bisa.disabled) return false;
    
    if(tabtitle == 'COA Group')
        var data = $('#dg').mdmegrid('getSelections');
    else var data = $('#dg2').mdmegrid('getSelections');
    
    var jmldata = data.length;
    var pesan = 'Delete '+jmldata+' selected data, Are you sure?';
    if(jmldata>0){
        if (!confirm(pesan)) return false;
	else{
            if(tabtitle == 'COA Group'){
               $ajaxdeleteg        
                return false;
            }else{
                $ajaxdeleted
                return false;
            }
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
    $('#FicoCoagroup_cdfigroup').attr('readonly', 'true');
    $('#FicoCoagroup_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    $('#FicoCoagroup_dscrp').focus();
    $('#FicoCoagroup_dscrp').select($('#FicoCoagroup_dscrp').length);
    $('#trns').html('Update/Delete..!');
}
    
function clickRow2(){
    $('#FicoCoa_cdfiacc').attr('readonly', 'true');
    $('#FicoCoa_dscrp').removeAttr('readonly');
        
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
    
    var dat = $('#dg2').mdmegrid('getSelected');
    var actv = dat['dk'];
    if(actv == 'D')actv=0;
        else actv=1;
        
    $('#FicoCoa_cdfigroup').val(dat['cdfigroup']);
    $('#FicoCoa_dk_'+actv).attr('checked', 'checked');
        
    $('#FicoCoa_dscrp').focus();
    $('#FicoCoa_dscrp').select($('#FicoCoa_dscrp').length);
    $('#trns').html('Update/Delete..!');
}
        
function sukses(r,sender){
    if(r!=''){ 
        alert(r);
        return true;
    } 
    //alert('Successfully '+sender+' record..!');
        $('#cancelBtn').click();
    if(sender !== 'delete') $('#newBtn').click();
        
    $('#dg').mdmegrid('load');
    $('#dg2').mdmegrid('load');
    $('#trns').html('');
}

function failed(r,sender){
    alert('Failed on '+sender+' record..!');
}  
    
");
?>

<?php
$judul = "Chart of Account (COA)";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <div id="tt" class="easyui-tabs" style="width:705px; height:455px;">
            <div title="COA Group" style="padding:20px">
                <?php
                $form2 = $this->beginWidget('CActiveForm', array(
                    'id' => 'fico-coagroup-form',
                    'enableAjaxValidation' => false,
                        ));
                ?>
                <table width="100%" style="font-size: 1.2em;">
                    <tr>
                        <td width="16%"><?php echo $form2->labelEx($model2, 'cdfigroup'); ?></td>
                        <td><?php echo $form2->textField($model2, 'cdfigroup', array('size' => '13', 'readonly' => 'true')); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $form2->labelEx($model2, 'dscrp'); ?></td>
                        <td><?php echo $form2->textField($model2, 'dscrp', array('size' => '42', 'readonly' => 'true')); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $form2->labelEx($model2, 'hdr'); ?></td>
                        <td>
                            <?php
                            $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv order by cdlookup', array(':groupv' => 'headercoa')), 'cdlookup', 'dscrp');
                            echo CHtml::activeDropDownList($model2, 'hdr', $listnyo, array('width' => '700px'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                    </tr>
                </table>
                <?php $this->endWidget(); ?>
                <?php
                $this->widget('mdmEui.grid.MdmEGrid', array(
                    'id' => 'dg',
                    'dataUrl' => array('dataGroup'),
                    'options' => array(
                        'pagination' => true,
                        'rownumbers' => true,
                        'onSelect' => 'js:function(index,row){clickRow();}',
                        'pageSize' => 30,
                        'singleSelect' => true,
                    ),
                    'columns' => array(
                        array('field' => 'cdfigroup', 'title' => 'Code',
                            'htmlOptions' => array('width' => 150),
                            'selector' => '#FicoCoagroup_cdfigroup'),
                        array('field' => 'dscrp', 'title' => 'Description',
                            'htmlOptions' => array('width' => 500),
                            'selector' => '#FicoCoagroup_dscrp'),
                        array('field' => 'hdr', 'title' => 'Header',
                            'htmlOptions' => array('width' => 300),
                            'selector' => '#FicoCoagroup_hdr'),
                        array('field' => 'update_date', 'title' => 'Last Update',
                            'htmlOptions' => array('width' => 250)),
                    ),
                    'htmlOptions' => array(
                        //'rownumbers' => "true",
                        'fitColumns' => "true",
                        'style' => "width:663px;height:265px",
                    )
                ));
                ?>
            </div>
            <div title="COA Detail" style="padding:20px">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'fico-coa-form',
                    'enableAjaxValidation' => false,
                        ));
                ?>
                <table border="0" width="100%" style="font-size: 1.2em;">
                    <tr>
                        <td><?php echo $form->labelEx($model, 'cdfiacc'); ?></td>
                        <td><?php echo $form->textField($model, 'cdfiacc', array('size' => '13', 'readonly' => 'true')); ?></td>
                        <td><?php echo $form->labelEx($model, 'cdfigroup'); ?></td>
                        <td>
                            <?php //echo $form->textField($model, 'cdfigroup'); ?>
                            <?php
                            $toarray = array();
                            $criteria = new CDbCriteria;
                            $criteria->order = 'cdfigroup ASC';
                            
                            $dtlist = FicoCoagroup::model()->FindAll($criteria);
                            foreach ($dtlist as $row) {
                                $toarray[] = array('cdfigroup' => $row['cdfigroup'], 'dscrp' => $row['cdfigroup'] . ' ' . $row['dscrp']);
                            }
                            
                            $listnyo = CHtml::listData($toarray, 'cdfigroup', 'dscrp');
                            echo CHtml::activeDropDownList($model, 'cdfigroup', $listnyo, array('width' => '950px'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                        <td><?php echo $form->textField($model, 'dscrp', array('size' => '32', 'readonly' => 'true')); ?></td>
                        <td><?php echo $form->labelEx($model, 'dk'); ?></td>
                        <td>
                            <?php //echo $form->textField($model, 'dk'); ?>
                            <?php
                            $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv', array(':groupv' => 'dk')), 'cdlookup', 'dscrp');
                            echo CHtml::activeRadioButtonList($model, 'dk', $listnyo, array('separator' => ' ', 'labelOptions' => array('style' => 'display:inline')));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                    </tr>
                </table>
                <?php $this->endWidget(); ?>
                <?php
                $this->widget('mdmEui.grid.MdmEGrid', array(
                    'id' => 'dg2',
                    'dataUrl' => array('dataCoa'),
                    'options' => array(
                        'pagination' => true,
                        'rownumbers' => true,
                        'onSelect' => 'js:function(index,row){clickRow2();}',
                        'pageSize' => 30,
                        'singleSelect' => true,
                    ),
                    'columns' => array(
                        array('field' => 'cdfiacc', 'title' => 'Code',
                            'htmlOptions' => array('width' => 150),
                            'selector' => '#FicoCoa_cdfiacc'),
                        array('field' => 'dscrp', 'title' => 'Description',
                            'htmlOptions' => array('width' => 500),
                            'selector' => '#FicoCoa_dscrp'),
//                        array('field' => 'cdfigroup', 'title' => 'CdGroup',
//                            'htmlOptions' => array('width' => 100),
//                            'selector' => '#FicoCoa_cdfigroup'),
                        array('field' => 'group', 'title' => 'Group',
                            'htmlOptions' => array('width' => 200),),
                        array('field' => 'dk', 'title' => 'D/K',
                            'htmlOptions' => array('width' => 100),
                            'selector' => '#FicoCoa_dk'),
                        array('field' => 'update_date', 'title' => 'Last Update',
                            'htmlOptions' => array('width' => 150)),
                    ),
                    'htmlOptions' => array(
                        //'rownumbers' => "true",
                        'fitColumns' => "true",
                        'style' => "width:663px;height:295px",
                    )
                ));
                ?>
            </div>
        </div>

    </div><!-- form -->
</div>