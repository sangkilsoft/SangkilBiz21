<?php
$ajaxcari = CHtml::ajax(array(
            'url' => array('findPO'),
            'data' => array('pnum' => 'js:pnum'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCari(r,\'create\');}',
            'error' => 'js:function(r){failedCari(r,\'create\');}'
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

$ajaxstatus = CHtml::ajax(array(
            'url' => array('findStatus'),
            'data' => array('status' => 'js:status'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCStatus(r,\'create\');}'
        ));

$ajaxvendor = CHtml::ajax(array(
            'url' => array('findVendor'),
            'data' => array('cdvend' => 'js:cdvend'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCVendor(r,\'select\');}',
            'error' => 'js:function(r){failedCVendor(r,\'select\');}'
        ));

$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createPurch'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxupdate = CHtml::ajax(array(
            'url' => array('updatePurch'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesUpdate(r,\'update\');}',
            'error' => 'js:function(r){failedUpdate(r,\'update\');}'
        ));

Yii::app()->clientScript->registerScript('form', " 
$('#InvpurchHdr_purch_num').keydown(function(event){
     if (event.keyCode == 13){
        //Cari data 
        var pnum = $('#InvpurchHdr_purch_num').val();
        $ajaxcari
     }
 });
        
function suksesCari(r,sender){
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){  
        var  pnum = ret.rows; 
        
        var unit = pnum[0].cdunit;
        $ajaxunit
        
        var whse = pnum[0].cdwhse;
        $ajaxwhse
        
        //change status
        var status = pnum[0].status;
        if(status == '-1') 
            $('#saveBtn').linkbutton('disable');
        else $('#saveBtn').linkbutton('enable');
        $('#oldstatus').val(status);
        $ajaxstatus 
            
        var cdvend = pnum[0].cdvend;
        $ajaxvendor
        
        $('#InvpurchHdr_purch_num').val(pnum[0].purch_num);
        $('#InvpurchHdr_refnum').val(pnum[0].refnum);
        $('#InvpurchHdr_dscrp').val(pnum[0].dscrp);
        
        var dtl = ret.dtl;  
        $('#trns').html('Update..!');
        
        var len = dtl.length;
        dataJson =  [];
        for (var i = 0; i < len; i++)
        {
          dataJson.push({'lnum': dtl[i].lnum, 'cditem': dtl[i].cditem, 'nmitem': dtl[i].nmitem, 'lnitem': dtl[i].lnitem, 'qtyitem': dtl[i].qtyitem, 'uom':dtl[i].uom, 'pprise':dtl[i].pprise, 'markup':dtl[i].markup, 'sprise':dtl[i].sprise });
        }
        $('#dg').mdmegrid('loadData',dataJson);
        $('#StatusBar').jnotifyAddMessage({
                text: 'PO Number was found ..',
                permanent: false,
                showIcon: true,
                type: 'message',
                disappearTime: '500'
            });
    }else if(ret.type == 'E'){  
        $('#StatusBar').jnotifyAddMessage({
                text: ''+ret.message+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });
        //var kosong = [];
        //$('#dg').mdmegrid('loadData',kosong);
        setTimeout(reloadExec, 3000);
    }else {
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });
        //var kosong = [];
        //$('#dg').mdmegrid('loadData',kosong);
        setTimeout(reloadExec, 3000);
        }
        
    return;
}

function reloadExec(){
    var href = 'index.php?r=inv/purch';
    window.open(href,'_self')
}
             
function failedCari(r,sender){
    //change and lock unit, whse 
    alert('tidak ketemu');
    return;      
}

function suksesCUnit(r,sender){
    $('#InvpurchHdr_cdunit').html(r);
}

function suksesCWhse(r,sender){
    $('#InvpurchHdr_cdwhse').html(r);
}
        
function suksesCStatus(r,sender){
    $('#InvpurchHdr_status').html(r);
}
        
function suksesCVendor(r,sender){
    $('#InvpurchHdr_cdvend').html(r);
}
        
function failedCVendor(r,sender){
    alert(r);
}
 
function suksesUpdate(r,sender){         
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
            type: 'info'
        });
    }else if(ret.type == 'E'){
        var msg = ret.message;   
        $('#StatusBar').jnotifyAddMessage({
                text: ''+msg+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });
        return;
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
        
function failedUpdate(r,sender){
    $('#StatusBar').jnotifyAddMessage({
            text: 'Update failed..!',
            permanent: false,
            showIcon: true,
            type: 'error'
        });
    return;
}
        
var SelIndex = -1;   
$('#saveBtn').linkbutton('enable');
$('#trns').html('New..!');        
        
$('#srcBtn').click(function(){
    //Cari data 
    var pnum = $('#InvpurchHdr_purch_num').val();
    $ajaxcari
        
    //$('#dlg').dialog('open');
});
        
$('#newBtn').click(function(){
    var href = 'index.php?r=inv/purch';
    window.open(href,'_self');
    return true;
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#invpurch-form').serializeArray();
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
    var row =  $('#dg').mdmegrid('getSelected');
    if (row){
        var index =  $('#dg').mdmegrid('getRowIndex', row);
        $('#dg').mdmegrid('deleteRow', index);
        $('#dg').mdmegrid('acceptChanges');
        
        $('#cditem').val('');
        $('#nmitem').val('');
        $('#qtyitem').val('');
        $('#uom').val('');
        $('#pprise').val('');
        $('#markup').val('');
        $('#sprise').val('');
        $('#lnitem').val('');
        $('#dg').mdmegrid('unselectAll');
    }
    SelIndex = -1;
});
   
function clickRow(index,row){
    var oldstatus = $('#oldstatus').val();
    if(oldstatus == 2){
        $('#StatusBar').jnotifyAddMessage({
                text: 'Posted record can\'t be updated',
                permanent: false,
                showIcon: true,
                type: 'error',
                disappearTime: '1000'
            });
        $('#cditem').val('');
        $('#nmitem').val('');
        $('#qtyitem').val('');
        $('#uom').val('');
        $('#pprise').val('');
        $('#markup').val('');
        $('#sprise').val('');
        $('#lnitem').val('');
        $('#dg').mdmegrid('unselectAll');
        return false
    }
    if(typeof row['lnum'] != 'undefined') $('#lnum').val(row['lnum']);
        else  $('#lnum').val('');
        
    $('#lnitem').val(row['lnitem']);
    $('#delBtn').linkbutton('enable');
    //$('#saveBtn').linkbutton('enable');
    SelIndex = index;
}
             
function sukses(r,sender){        
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){  
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: true,
            showIcon: true,
            type: 'success'
        });
        $('#cancelBtn').click();
        $('#InvpurchHdr_purch_num').val(ret.val);
    }else if(ret.type == 'W'){
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: false,
            showIcon: true,
            type: 'info'
        });
    }else if(ret.type == 'E'){
        var msg = ret.message;   
        var msgtodispaly = '';
        if(typeof msg.cdunit != 'undefined'){ msgtodispaly = msg.cdunit; }
        else if(typeof msg.cdwhse != 'undefined'){ msgtodispaly = msg.cdwhse;}
        else if(typeof msg.refnum != 'undefined'){ msgtodispaly = msg.refnum;}
        else if(typeof msg.date_gr != 'undefined'){ msgtodispaly = msg.date_gr;}
        else if(typeof msg.id_periode != 'undefined'){ msgtodispaly = msg.id_periode;}
        else { msgtodispaly = ret.message; }
        if(msgtodispaly != ''){
            $('#StatusBar').jnotifyAddMessage({
                text: ''+msgtodispaly+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });
        }
        return;
    }else{
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'info'
            });
    }  
    return;
}        
      
function failed(r,sender){
    $('#StatusBar').jnotifyAddMessage({
        text: 'Failed on '+ sender +' PO',
        permanent: false,
        showIcon: true,
        type: 'error'
    });
}   
       
function chgCode(event,item){
      $('#cditem').val(item[2]);
      $('#nmitem').val(item[3]);
      $('#lnitem').val(item[1]);
      $('#uom').val(item[4]);
      $('#qtyitem').focus();
}
 
$('#qtyitem').keydown(function(event){
     if (event.keyCode == 13){ 
         $('#pprise').focus();
     }
 });
 
$('#pprise').keydown(function(event){
     if (event.keyCode == 13){ 
         $('#markup').focus();
     }
 });
    
$('#sprise').keyup(function(event){
     if (event.keyCode == 13){
        $('#ok').click();
     }else{
        var sprise = $('#sprise').val();
        var pprise = $('#pprise').val();
        if(sprise == '' || pprise == '') 
            return;

        pprise = pprise.replace(/,/g, '');
        pprise = parseFloat(pprise); 
        
        sprise = sprise.replace(/,/g, '');
        sprise = parseFloat(sprise); 

        var markup = (sprise-pprise)/sprise*100;
        $('#markup').val(parseInt(markup));
     }
 });
 
$('#markup').keyup(function(event){
     if (event.keyCode == 13){
        $('#ok').click();
     }else{
        var markup = $('#markup').val();
        var pprise = $('#pprise').val();
        if(markup == '' || pprise == '') 
            return;

        pprise = pprise.replace(/,/g, '');
        pprise = parseFloat(pprise);  
        
        var sprise =  pprise /((100 - markup)/100);
        $('#sprise').val(parseInt(sprise));
     }
 });
        
var dataJson = [];
$('#ok').click(function(){ 
    var oldstatus = $('#oldstatus').val();
    if(oldstatus == 2) return false
        
    var cditem = $('#cditem').val();
    var nmitem = $('#nmitem').val();
    var qtyitem = $('#qtyitem').val();
    var uom = $('#uom').val();
    var pprise = $('#pprise').val();
    var markup = $('#markup').val();
    var sprise = $('#sprise').val();
    var lnitem = $('#lnitem').val();
    var lnum = $('#lnum').val();
    
    if(nmitem == '' || markup == '' || pprise == '' || sprise == '' || uom == '') return;
    
    var row =  $('#dg').mdmegrid('getSelected');
    if (row){
        var index =  $('#dg').mdmegrid('getRowIndex', row);
        dataJson[index].cditem = cditem;
        dataJson[index].nmitem = nmitem;
        dataJson[index].qtyitem = qtyitem;
        dataJson[index].uom = uom;
        dataJson[index].pprise = pprise;
        dataJson[index].markup = markup;
        dataJson[index].sprise = sprise;
        dataJson[index].lnitem = lnitem; 
        //if(lnum != '') dataJson[index].lnum = lnum; 
    }else
        dataJson.push({'cditem':cditem, 'nmitem': nmitem, 'lnitem': lnitem, 'qtyitem': qtyitem, 'uom':uom, 'pprise':pprise, 'markup':markup, 'sprise':sprise });
            
    $('#dg').mdmegrid('loadData',dataJson);
    $('#cditem').val('');
    $('#nmitem').val('');
    $('#qtyitem').val('');
    $('#uom').val('');
    $('#pprise').val('');
    $('#markup').val('');
    $('#sprise').val('');
    $('#lnitem').val('');
    $('#lnum').val('');
        
    $('#cditem').focus();
}); 
      
$('#pprise').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
$('#markup').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});             
");
?>
<?php
if (isset(Yii::app()->user->mmenu))
    Yii::app()->user->mmenu = "purc";

$judul = "Pembelian Barang";
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
            'id' => 'invpurch-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'purch_num'); ?></td>
                <td>
                    <?php
                    echo $form->textField($model, 'purch_num', array('size' => 16));
                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => '',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true')
                    ));
                    ?></td>
                <td><?php echo $form->labelEx($model, 'refnum'); ?></td>
                <td><?php echo $form->textField($model, 'refnum', array('size' => 12)); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td> 
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::activeDropDownList($model, 'cdunit', $listunit, array('width' => '700px',
                        'prompt' => '--Select Unit--',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('actPOWhse'),
                            'update' => '#InvpurchHdr_cdwhse',
                        ),
                    ));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse', array(), array('prompt' => '-- Warehose --'));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdvend'); ?></td>
                <td>
                    <?php
                    //echo $form->textField($model, 'status'); 
                    $listnyo = CHtml::listData(Mdvendor::model()->FindAll('cdvendcat=:cdvendcat', array(':cdvendcat' => '10')), 'cdvend', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'cdvend', $listnyo);
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'status'); ?></td>
                <td>
                    <?php
                    //echo $form->textField($model, 'status'); 
                    $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv', array(':groupv' => 'purch_status')), 'cdlookup', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'status', $listnyo);
                    echo CHtml::hiddenField('InvpurchHdr[oldstatus]', '0', array('id' => 'oldstatus', 'size' => '2'));
                    ?>
                </td>
                </td>
            </tr>
            <tr >                
                <td style="border-bottom:0px; height: 40px;">
                    <?php echo $form->labelEx($model, 'dscrp'); ?>
                </td>
                <td colspan="3" style="border-bottom: none; height: 40px;">
                    <?php
                    //echo $form->textField($model, 'dscrp');
                    echo $form->textArea($model, 'dscrp', array('cols' => 32, 'rows' => '2',
                        'maxlength' => 64));
                    ?>
                </td>
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <br/>
        <div id="entribar">
            <table border="0" width="100%" class="span-15">
                <tr  style="vertical-align:central;">
                    <td style="border-bottom:0px;">
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
                    <td style="border-bottom:0px;">
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
                            'htmlOptions' => array('size' => '21', 'id' => 'nmitem', 'maxlength' => 64),
                            'methodChain' => ".result(chgCode)",
                        ));
                        ?>
                    </td>
                    <td style="border-bottom:0px;">
                        <?php
                        echo CHtml::textField('qtyitem', '', array('size' => 5));
                        echo CHtml::hiddenField('lnitem');
                        echo CHtml::hiddenField('lnum');
                        ?>
                    </td>
                    <td style="border-bottom:0px;">
                        <?php
                        $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                        //print_r($listnyo);
                        echo CHtml::dropDownList('uom', '', $listnyo, array('prompt' => '-Uom-',));
                        ?>
                    </td>
                    <td style="border-bottom:0px;">
                        <?php
                        echo CHtml::textField('pprise', '', array('size' => '8', 'id' => 'pprise', 'maxlength' => 10));
                        ?>
                    </td>
                    <td style="border-bottom:0px;">
                        <?php echo CHtml::textField('markup', '', array('size' => 4, 'maxlength' => 3)); ?>
                    </td>
                    <td style="border-bottom:0px;">
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
                        echo CHtml::textField('sprise', '', array('size' => 6, 'maxlength' => 10));
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
                    'htmlOptions' => array('width' => 200),
                    'selector' => '#nmitem'),
                array('field' => 'qtyitem', 'title' => 'Qty',
                    'htmlOptions' => array('width' => 60),
                    'selector' => '#qtyitem'),
                array('field' => 'uom', 'title' => 'Uom',
                    'htmlOptions' => array('width' => 70),
                    'selector' => '#uom'),
                array('field' => 'pprise', 'title' => 'Purch Price',
                    'htmlOptions' => array('width' => 85),
                    'selector' => '#pprise'),
                array('field' => 'markup', 'title' => 'MarkUp',
                    'htmlOptions' => array('width' => 60),
                    'selector' => '#markup'),
                array('field' => 'sprise', 'title' => 'Sales Price',
                    'htmlOptions' => array('width' => 85),
                    'selector' => '#sprise'),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:707px;height:300px",
            )
        ));

//        $this->beginWidget('mdmEui.MdmDialog', array(
//            'htmlOptions' => array('id' => 'dlg'),
//            'options' => array(
//                'title' => 'Test Dialogue',
//                'autoOpen'=>false,
//                'modal'=>true,
//                'width'=>'auto',
//                'height'=>'auto',
//                )));
//        
//        echo $this->renderPartial('_frmGR');
//        $this->endWidget('mdmEui.MdmDialog');
        ?>
    </div><!-- form -->
</div>