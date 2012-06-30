<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createGL'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateGroup'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$ajaxcoa = CHtml::ajax(array(
            'url' => array('autoCoa'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){chgCode(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

Yii::app()->clientScript->registerScript('form', "
$('#trns').html('New..!'); 
$('#delBtn').linkbutton('disable'); 
$('#printBtn').linkbutton('disable'); 
$('#cancelBtn').linkbutton('disable'); 
        
$('#newBtn').click(function(){
    var href = 'index.php?r=fico/glentri';
    window.open(href,'_self');
    return false;
});
        
$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{ 
            var data = $('#fico-gl-form').serializeArray();
            var datadtl = $('#dg').mdmegrid('getData');
                
            if(tipe == 'New..!') 
                $ajaxsimpan
            else 
                $ajaxupdate

            return false;
	}
});     
   
$('#delBtn').click(function(){        
    $('#cdfigroup').val('');
    $('#cdacc').val('');
    $('#deacc').val('');
    $('#debit').val('');
    $('#crdit').val('');
    $('#cdacc').focus();
        
    var row = $('#dg').datagrid('getSelected'); 
    if (row){  
        var index = $('#dg').datagrid('getRowIndex', row);
        $('#dg').datagrid('deleteRow', index);
        $('#dg').mdmegrid('acceptChanges');
        $('#dg').mdmegrid('unselectAll');
    }
});
         
var dataJson = [];
$('#ok').click(function(){ 
    var cdfigroup = $('#cdfigroup').val();
    var cdacc = $('#cdacc').val();
    var dscrp = $('#deacc').val();
    var debit = $('#debit').val();
    var kredit = $('#crdit').val();
        
    var row = $('#dg').datagrid('getSelected');
    if (row){  
        var index = $('#dg').datagrid('getRowIndex', row);
        var data = {'cdacc':cdacc, 'cdfigroup': cdfigroup, 'dscrp': dscrp, 'debit':debit, 'kredit':kredit};
        $('#dg').datagrid('updateRow',{index: index,row: data});
        $('#dg').mdmegrid('unselectAll');
    }else{
        if(cdacc == '' || cdacc == '' ) return;
        dataJson.push({'cdacc':cdacc, 'cdfigroup': cdfigroup, 'dscrp': dscrp, 'debit':debit, 'kredit':kredit});
        $('#dg').mdmegrid('loadData',dataJson);
    }
    
    $('#cdfigroup').val('');
    $('#cdacc').val('');
    $('#deacc').val('');
    $('#debit').val('');
    $('#crdit').val('');
    $('#cdacc').focus();
    
    $('#saveBtn').linkbutton('enable');        
});
      
$('#debit').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#ok').click();
         $('#cdacc').focus();
     }
 });
      
$('#crdit').keypress(function(event){
     if (event.keyCode == 13){ 
         $('#ok').click();
         $('#cdacc').focus();
     }
 });
        
function clickRow(index,row){ 
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
}
 
function sukses(r,sender){ 
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){  
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: true,
            showIcon: true,
            type: 'success',
        });
        $('#InvgrHdr_gr_num').val(ret.val);
        $('#InvgrHdr_gr_num').attr('readonly','true');
        $('#cancelBtn').click();
    }else if(ret.type == 'W'){
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: false,
            showIcon: true,
            type: 'error',
        });
    }else if(ret.type == 'E'){
        var msg = ret.message;   
        var msgtodispaly = '';
        if(typeof msg.cdunit != 'undefined'){ msgtodispaly = msg.cdunit; }
        else if(typeof msg.cdwhse != 'undefined'){ msgtodispaly = msg.cdwhse;}
        else if(typeof msg.refnum != 'undefined'){ msgtodispaly = msg.refnum;}
        else if(typeof msg.date_gr != 'undefined'){ msgtodispaly = msg.date_gr;}
        else { msgtodispaly = ret.message; }
        if(msgtodispaly != ''){
            $('#StatusBar').jnotifyAddMessage({
                text: ''+msgtodispaly+'',
                permanent: false,
                showIcon: true,
                type: 'error',
            });
        }
        return;
    }else{
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'error',
            });
    }  
    return true;
}        
        
function failed(r,sender){
    $('#StatusBar').jnotifyAddMessage({
        text: 'GL creation failed, check data entries',
        permanent: false,
        showIcon: true,
        type: 'error',
    });
}  
  
$('#cdacc').change(function(event){
     var data = $('#entribar-form').serializeArray();
     $ajaxcoa
 });
        
function chgCode(r,sender){
      var item = r.split('|');
        
      $('#cdfigroup').val(item[4]);
      $('#cdacc').val(item[2]);
      $('#deacc').val(item[1]);
      if(item[3] == 'k' || item[3] == 'K'){
        $('#debit').val('0');
        $('#crdit').focus();
      }else{
        $('#crdit').val('0');
        $('#debit').focus();
      }
}
        
$('#crdit').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'}); 

");
?>
<?php
$judul = "Entri Jurnal";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        // Initialize the extension
        $this->widget('ext.jnotify.JNotify', array(
            'statusBarId' => 'StatusBar',
            'notificationId' => 'Notification',
            'notificationHSpace' => '30px',
            'notificationWidth' => '280px',
            'notificationShowAt' => 'topRight',
        ));
        ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fico-gl-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'cdfigl'); ?></td>
                <td><?php echo $form->textField($model, 'cdfigl'); ?></td>
                <td><?php echo $form->labelEx($model, 'refnum'); ?></td>
                <td><?php echo $form->textField($model, 'refnum'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td>
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::activeDropDownList($model, 'cdunit', $listunit, array('width' => '700px'));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'gl_date'); ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'gl_date',
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;',
                            'value' => date('d-m-Y'),
                        ),
                    ));
                    ?>
                </td>
            </tr>
            <tr style="vertical-align: top;">
                <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td>
                    <?php
                    echo $form->textArea($model, 'dscrp', array('cols' => 28, 'rows' => '2',
                        'maxlength' => 64));
                    ?>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'entribar-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <br/>
        <table border="0" width="100%" >
            <tr>
                <td>
                    <?php //echo CHtml::label('Acc Code', 'cdacc', array('size' => '11')) ?>
                    <?php
                    echo CHtml::hiddenField('cdfigroup', '', array('id' => 'cdfigroup'));
                    /*
                      //                    $this->widget('CAutoComplete', array(
                      //                        'name' => 'cdacc',
                      //                        //'value' => ($model->isNewRecord ? '' : $model->idSiswa->nm_siswa),
                      //                        'cacheLength' => 0,
                      //                        'url' => array('autoCoa'),
                      //                        'max' => 30,
                      //                        'minChars' => 1,
                      //                        'delay' => 100,
                      //                        'matchCase' => false,
                      //                        'htmlOptions' => array('size' => '11', 'id' => 'cdacc'),
                      //                        'methodChain' => ".result(chgCode)",
                      //                    ));
                     * 
                     */
                    ?>
                    <?php
                    /*
                      //                    $this->widget('CAutoComplete', array(
                      //                        'name' => 'deacc',
                      //                        'url' => array('autoCoa'),
                      //                        'max' => 30,
                      //                        'minChars' => 1,
                      //                        'delay' => 100,
                      //                        'options' => array(
                      //                            'minLength' => '2',
                      //                        ),
                      //                        'htmlOptions' => array(
                      //                            'style' => 'height:18px;width:270px;',
                      //                            'id' => 'deacc'
                      //                        ),
                      //                        'methodChain' => ".result(chgCode)",
                      //                    ));
                      //                    echo CHtml::label('Acc Description', 'deacc', array('size' => '29'));

                     */

                    $modelacc = FicoCoa::model()->with('group')->findAll(array('order' => 'cdfiacc ASC'));
                    $datalist = CHtml::listData($modelacc, 'cdfiacc', 'dscrp', 'group.dscrp');
                    echo CHtml::dropDownList('cdacc', '', $datalist, array('prompt' => '--Pilih Account--', 'style' => 'width:28em;'));
                    echo CHtml::hiddenField('deacc', '', array('id' => 'deacc'));
                    ?>
                </td>
                <td>
                    <?php
                    //echo CHtml::label('Debit', 'debit', array('size' => '14'))    
                    ?>
                    <?php
                    $this->widget('ext.moneymask.MMask', array(
                        'element' => '#debit',
                        'id' => 'satu',
                        'currency' => 'PHP',
                        'config' => array(
                            'showSymbol' => false,
                            'defaultZero' => false,
                            'precision' => 0,
                        )
                    ));
                    echo CHtml::textField('debit', '', array('size' => '14', 'id' => 'debit'));
                    ?>
                </td>
                <td>
                    <?php
//                  echo CHtml::label('Kredit', 'crdit', array('size' => '16'));
                    echo CHtml::textField('crdit', '', array('size' => '12', 'id' => 'crdit'));
                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'ok',
                        'text' => '',
                        'htmlOptions' => array('iconCls' => 'icon-ok', 'plain' => 'true')
                    ));
                    ?>
                </td>
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            //'dataUrl' => array('dataGroup'),
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'pageSize' => 10,
                'singleSelect' => true,
            ),
            'columns' => array(
                array(
                    //'class' => 'MdmInputColumn',
                    'field' => 'cdacc',
                    'title' => 'Acc Code',
                    'selector' => '#cdacc',
                    'htmlOptions' => array('width' => 250)),
                array(
                    //'class' => 'MdmInputColumn',
                    'field' => 'dscrp', 'title' => 'Acc Description',
                    'selector' => '#deacc',
                    'htmlOptions' => array('width' => 800)),
                array(
                    //'class' => 'MdmInputColumn',
                    'field' => 'debit', 'title' => 'Debit',
                    'selector' => '#debit',
                    'htmlOptions' => array('width' => 400, 'align' => 'right')),
                array(
                    //'class' => 'MdmInputColumn',
                    'field' => 'kredit', 'title' => 'Kredit',
                    'selector' => '#crdit',
                    'htmlOptions' => array('width' => 400, 'align' => 'right')),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:706px;height:240px",
            )
        ));
        ?>
    </div><!-- form -->
</div>