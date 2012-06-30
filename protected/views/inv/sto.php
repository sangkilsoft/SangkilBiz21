<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createSto'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCreate(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updateSto'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesUpdate(r,\'update\');}',
            'error' => 'js:function(r){failed(r,\'update\');}'
        ));

$findSto = CHtml::ajax(array(
            'url' => array('findSto'),
            'data' => array('data' => 'js:data', 'type' => 'trns'),
            'type' => 'POST',
            'success' => 'js:function(r){foundSto(r);}',
            'error' => 'js:function(r){noSto(r);}'
        ));

$ajaxunit = CHtml::ajax(array(
            'url' => array('findUnit'),
            'data' => array('unit' => 'js:unit'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCUnit(r,\'create\');}'
        ));

$ajaxwhse = CHtml::ajax(array(
            'url' => array('findWhse'),
            'data' => array('whse' => 'js:whse'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCWhse(r,\'create\');}'
        ));

$ajaxwhse2 = CHtml::ajax(array(
            'url' => array('findWhse2'),
            'data' => array('whse2' => 'js:whse2'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCWhse2(r,\'create\');}'
        ));

$ajaxstatus = CHtml::ajax(array(
            'url' => array('findStatusTrf'),
            'data' => array('status' => 'js:status'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCStatus(r,\'create\');}'
        ));

Yii::app()->clientScript->registerScript('form', "  
var SelIndex = -1; 
$('#trns').html('New..!'); 
$('#saveBtn').linkbutton('enable');
        
$('#srcBtn').click(function(){
     var data = $('#invtrf-form').serializeArray();
     $findSto
});
        
$('#newBtn').click(function(){
    var href = 'index.php?r=inv/sto';
    window.open(href,'_self');
    return true;
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#invtrf-form').serializeArray();
            var datadtl = $('#dg').mdmegrid('getData');
            if(tipe == 'New..!') 
                $ajaxsimpan
            else if(tipe == 'Update..!'){ 
                $ajaxupdate
            }
            return false;
	}
});
    
$('#cancelBtn').click(function(){        
    $('#dg').mdmegrid('unselectAll');
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
    $('#trns').html('');
});        
   
$('#delBtn').click(function(){
    var row = $('#dg').datagrid('getSelected');
    if (row){         
        $('#cditem').val('');
        $('#nmitem').val('');
        $('#qtyitem').val('');
        $('#uom').val('');
        $('#stotal').val('');
        $('#sprise').val('');
        $('#lnitem').val('');
        
        var index = $('#dg').datagrid('getRowIndex', row);
        $('#dg').datagrid('deleteRow', index);
        $('#dg').mdmegrid('acceptChanges');
        $('#dg').mdmegrid('unselectAll');
    } 
});
   
function clickRow(index,row){ 
    var oldstatus = $('#oldstatus').val();
    if(oldstatus == 2 || oldstatus == -1){
        $('#StatusBar').jnotifyAddMessage({
                text: 'Receipt record can\'t be updated',
                permanent: false,
                showIcon: true,
                type: 'error',
                disappearTime: '1000'
            });
        $('#cditem').val('');
        $('#nmitem').val('');
        $('#qtyitem').val('');
        $('#uom').val('');
        $('#stotal').val('');
        $('#sprise').val('');
        $('#lnitem').val('');
        $('#dg').mdmegrid('unselectAll');
        return false
    }
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    if(typeof row['lnum'] != 'undefined') $('#lnum').val(row['lnum']);
    else  $('#lnum').val('');
      
    if(typeof row['lnitem'] != 'undefined') $('#lnitem').val(row['lnitem']);
    else  $('#lnitem').val('');
}
       
function suksesCreate(r,sender){
    var ret = JSON.parse(r);
    if(ret.type == 'S'){  
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: true,
            showIcon: true,
            type: 'success'
        });
        $('#cancelBtn').click();
    }else if(ret.type == 'W'){
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: false,
            showIcon: true,
            type: 'error'
        });
    }else if(ret.type == 'E'){
        var msg = ret.message;     
        if(typeof msg.cdunit != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdunit+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.cdwhse != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdwhse2+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.cdwhse2 != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdwhse2+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.dscrp != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.dscrp+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.date_trf != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.date_trf+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else{ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+ret.message+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
    }else{
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'info'
            });
    }  
    $('#trns').html('');
    return;
}        
        
function suksesUpdate(r,sender){
    var ret = JSON.parse(r);
    if(ret.type == 'S'){  
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: false,
            showIcon: true,
            type: 'success'
        });
        $('#cancelBtn').click();
    }else if(ret.type == 'W'){
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: false,
            showIcon: true,
            type: 'error'
        });
    }else if(ret.type == 'E'){
        var msg = ret.message;     
        if(typeof msg.cdunit != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdunit+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.cdwhse != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdwhse2+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.cdwhse2 != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.cdwhse2+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.dscrp != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.dscrp+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else if(typeof msg.date_trf != 'undefined'){ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+msg.date_trf+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
        else{ 
            $('#StatusBar').jnotifyAddMessage({
                    text: ''+ret.message+'',
                    permanent: false,
                    showIcon: true,
                    type: 'error'
                });
            return;
        }
    }else{
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });
    }  
    $('#trns').html('');
    return;
}    
        
function foundSto(r,sender){
    var ret = JSON.parse(r);
    if(ret.type == 'S'){  
        var hdr = ret.rows; 
        var dtl = ret.dtl;
        var tgl = hdr[0].date_trf;
        
        var unit = hdr[0].cdunit;
        $ajaxunit
        
        var whse = hdr[0].cdwhse;
        $ajaxwhse
         
        var whse2 = hdr[0].cdwhse2;
        $ajaxwhse2
        
        //change status
        var status = hdr[0].status;
        if(status == '-1') 
            $('#saveBtn').linkbutton('disable');
        else $('#saveBtn').linkbutton('enable');
        $('#oldstatus').val(status);
        $ajaxstatus 
        
        tgl = tgl.substr(-2,2)+'-'+tgl.substr(-5,2)+'-'+tgl.substr(0,4);
        $('#trns').html('Update..!');
        $('#InvtrfHdr_dscrp').val(hdr[0].dscrp);
        $('#InvtrfHdr_date_trf').val(tgl);
        
        var len = dtl.length;
        dataJson =  [];
        for (var i = 0; i < len; i++)
        {
            dataJson.push({'lnum': dtl[i].lnum, 'cditem': dtl[i].cditem, 'nmitem': dtl[i].nmitem, 'lnitem': dtl[i].lnitem, 'qtyitem': dtl[i].qtyitem, 'uom':dtl[i].uom, 'sprise':dtl[i].sprise, 'stotal':dtl[i].subtotal });
        }
        
        $('#dg').mdmegrid('loadData',dataJson);
//        $('#StatusBar').jnotifyAddMessage({
//                text: 'Transfer Number was found ..',
//                permanent: false,
//                showIcon: true,
//                type: 'message',
//                disappearTime: '1000'
//            });
    }else if(ret.type == 'E'){
        var msg = ret.message;     
        $('#StatusBar').jnotifyAddMessage({
                text: ''+msg+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });        
        $('#dg').mdmegrid('loadData',ret); 
    }
    $('#dgdtl').mdmegrid('loadData',[]);
    return true;
}  
        
function suksesCUnit(r,sender){
    $('#InvtrfHdr_cdunit').html(r);
}

function suksesCWhse(r,sender){
    $('#InvtrfHdr_cdwhse').html(r);
}

function suksesCWhse2(r,sender){
    $('#InvtrfHdr_cdwhse2').html(r);
}
        
function suksesCStatus(r,sender){
    $('#InvtrfHdr_status').html(r);
}
        
function failed(r,sender){
    alert('Failed on '+ sender);
} 
        
function noSto(r,sender){
    alert('Failed on '+ sender);
}
        
function chgCode(event,item){
      $('#cditem').val(item[2]);
      $('#nmitem').val(item[3]);
      $('#lnitem').val(item[1]);
      $('#uom').val(item[4]);
      $('#sprise').val(item[5]);
      $('#qtyitem').focus();
}
 
$('#qtyitem').keyup(function(event){
    if (event.keyCode == 13) 
        return;
        
    var qtyitem = $('#qtyitem').val();
    var sprise = $('#sprise').val();

    if(qtyitem == '' || sprise == '') 
        return;

    sprise = sprise.replace(/,/g, '');
    sprise = parseFloat(sprise);  

    qtyitem = qtyitem.replace(/,/g, '');
    qtyitem = parseFloat(qtyitem); 

    var stotal =  sprise * qtyitem;

    $('#stotal').val(parseInt(stotal));
 });
 
$('#qtyitem').keydown(function(event){
     if (event.keyCode == 13){
        $('#ok').click();
     }
 });
        
var dataJson = [];
$('#ok').click(function(){ 
    var cditem = $('#cditem').val();
    var nmitem = $('#nmitem').val();
    var qtyitem = $('#qtyitem').val();
    var uom = $('#uom').val();
    var sprise = $('#sprise').val();
    var lnitem = $('#lnitem').val(); 
    var stotal = $('#stotal').val();
    var lnum = $('#lnum').val(); 
        
    var row = $('#dg').datagrid('getSelected');
    if (row){  
        var index = $('#dg').datagrid('getRowIndex', row);
        var data = {'lnum':lnum, 'cditem':cditem, 'nmitem': nmitem, 'lnitem': lnitem, 'qtyitem': qtyitem, 'uom':uom, 'sprise':sprise, 'stotal':stotal };
        $('#dg').datagrid('updateRow',{index: index,row: data});
        $('#dg').mdmegrid('unselectAll');
    }else{
        if(nmitem == '' || sprise == '' || uom == '' || qtyitem == '' ) return;
        dataJson.push({'cditem':cditem, 'nmitem': nmitem, 'lnitem': lnitem, 'qtyitem': qtyitem, 'uom':uom, 'sprise':sprise, 'stotal':stotal });
        $('#dg').mdmegrid('loadData',dataJson);
    }
        
    $('#cditem').val('');
    $('#nmitem').val('');
    $('#qtyitem').val('');
    $('#uom').val('');
    $('#stotal').val('');
    $('#sprise').val('');
    $('#lnitem').val('');
    $('#cditem').focus();
}); 
      
$('#pprise').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
$('#markup').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
        
");
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "inv";

$judul = "Stock Transfer";
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
            'id' => 'invtrf-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'trf_num'); ?></td>
                <td>
                    <?php
                    echo $form->textField($model, 'trf_num', array('size' => 12));
                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => '',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true')
                    ));
                    ?></td>
                <td><?php echo "Status" ?></td>
                <td>
                    <?php
                    //echo $form->textField($model, 'refnum', array('size' => 12)); 
                    $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv', array(':groupv' => 'transf_status')), 'cdlookup', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'status', $listnyo);
                    echo CHtml::hiddenField('InvtrfHdr[oldstatus]', '0', array('id' => 'oldstatus', 'size' => '2'));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td>
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::DropDownList('InvtrfHdr[cdunit]', '', $listunit, array('width' => '700px',
                        'id' => 'InvtrfHdr_cdunit',
                        'prompt' => '--Select Unit--',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('actStoWhse'),
                            'update' => '#InvtrfHdr_cdwhse',
                        ),
                    ));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse', array(), array('prompt' => '- From Warehose -'));
                    echo "&nbsp;to&nbsp;";
                    $listnyo = CHtml::listData(InvWarehouse::model()->FindAll(), 'cdwhse', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdwhse2', $listnyo, array('prompt' => '- Warehose -'));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td>
                    <?php
                    echo $form->textArea($model, 'dscrp', array('cols' => 21, 'rows' => '2',
                        'maxlength' => 64));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'date_trf'); ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'InvtrfHdr[date_trf]',
                        'model' => $model,
                        'attribute' => 'date_trf',
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
        </table>
        <?php $this->endWidget(); ?>
        <br/>
        <div id="entribar">
            <table border="0" width="100%" class="span-15">
                <tr>
                    <td>
                        <?php
                        //echo CHtml::textField('cditem', '', array('size' => 12));
                        $this->widget('CAutoComplete', array(
                            'name' => 'cditem',
                            'cacheLength' => 0,
                            'url' => array('autoItem'),
                            'max' => 30,
                            'minChars' => 2,
                            'delay' => 100,
                            'matchCase' => false,
                            'htmlOptions' => array('size' => '12', 'id' => 'cditem', 'maxlength' => 13),
                            'methodChain' => ".result(chgCode)",
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        //echo CHtml::textField('nmitem', '', array('size' => 21)); 
                        $this->widget('CAutoComplete', array(
                            'name' => 'nmitem',
                            'cacheLength' => 0,
                            'url' => array('autoItem'),
                            'max' => 30,
                            'minChars' => 2,
                            'delay' => 100,
                            'matchCase' => false,
                            'htmlOptions' => array('size' => '27', 'id' => 'nmitem', 'maxlength' => 64),
                            'methodChain' => ".result(chgCode)",
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::textField('qtyitem', '', array('size' => '5'));
                        echo CHtml::hiddenField('lnitem');
                        echo CHtml::hiddenField('lnum');
                        ?>
                    </td>
                    <td>
                        <?php
                        $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                        //print_r($listnyo);
                        echo CHtml::dropDownList('uom', '', $listnyo, array('prompt' => '-Uom-'));
                        ?>
                    </td>
                    <td>
                        <?php
                        $this->widget('ext.moneymask.MMask', array(
                            'element' => '#sprise',
                            'id' => 'masksprise',
                            'currency' => 'PHP',
                            'config' => array(
                                'showSymbol' => false,
                                'defaultZero' => false,
                                'precision' => 0,
                            )
                        ));
                        echo CHtml::textField('sprise', '', array('size' => 7, 'maxlength' => 10));
                        ?>
                    </td>
                    <td>
                        <?php
                        $this->widget('ext.moneymask.MMask', array(
                            'element' => '#stotal',
                            'id' => 'maskstotal',
                            'currency' => 'PHP',
                            'config' => array(
                                'showSymbol' => false,
                                'defaultZero' => false,
                                'precision' => 0,
                            )
                        ));
                        echo CHtml::textField('stotal', '', array('size' => 7, 'maxlength' => 10));
                        $this->widget('ext.mdmEui.MdmLinkButton', array(
                            'id' => 'ok',
                            'text' => '',
                            'htmlOptions' => array('iconCls' => 'icon-ok', 'plain' => 'true')
                        ));
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            'dataUrl' => array('dataItems'),
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'singleSelect' => true,
                'fitColumns' => false,
            ),
            'columns' => array(
                array('field' => 'cditem', 'title' => 'Code',
                    'htmlOptions' => array('width' => 100),
                    'selector' => '#cditem'),
                array('field' => 'nmitem', 'title' => 'Description',
                    'htmlOptions' => array('width' => 260),
                    'selector' => '#nmitem'),
                array('field' => 'qtyitem', 'title' => 'Qty',
                    'htmlOptions' => array('width' => 60),
                    'selector' => '#qtyitem'),
                array('field' => 'uom', 'title' => 'Uom',
                    'htmlOptions' => array('width' => 75),
                    'selector' => '#uom'),
                array('field' => 'sprise', 'title' => 'Sales Price',
                    'htmlOptions' => array('width' => 80),
                    'selector' => '#sprise'),
                array('field' => 'stotal', 'title' => 'Sub Total',
                    'htmlOptions' => array('width' => 80),
                    'selector' => '#stotal'),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:705px;height:300px",
            )
        ));
        ?>
        <?php
        $this->beginWidget('mdmEui.MdmDialog', array(
            'htmlOptions' => array('id' => 'dlg'),
            'options' => array(
                'title' => 'Test Dialogue',
                'autoOpen' => false,
                'modal' => true,
                'width' => 'auto',
                'height' => 'auto',
                )));

        //echo $this->renderPartial('_frmGR');
        $this->endWidget('mdmEui.MdmDialog');
        ?>
    </div><!-- form -->
</div>
