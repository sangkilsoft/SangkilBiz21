<?php
$ajaxsimpan = CHtml::ajax(array(
            'url' => array('createGR'),
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

$ajaxdelete = CHtml::ajax(array(
            'url' => array('deleteGroup'),
            'data' => array('del' => 'js:data'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'delete\');}',
            'error' => 'js:function(r){failed(r,\'create/update\');}'
        ));

$findGR = CHtml::ajax(array(
            'url' => array('findGR'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){grfound(r);}',
            'error' => 'js:function(r){grnone(r);}'
        ));

Yii::app()->clientScript->registerScript('form', "  
var SelIndex = -1;       
$('#srcBtn').click(function(){
     $('#dlg').dialog('open');
});

$('#srcBtn2').click(function(){
     $('#dlg2').dialog('open');
});
        
$('#newBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    if(bisa.disabled) $('#saveBtn').linkbutton('enable');
    $('#delBtn').linkbutton('disable'); 
    $('#trns').html('New..!');
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#invgr-form').serializeArray();
            var datadtl = $('#dg').mdmegrid('getData');
            if(tipe == 'New..!') 
                $ajaxsimpan
            else 
                $ajaxupdate

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
    $('#dg').mdmegrid('deleteRow',SelIndex);
    $('#dg').mdmegrid('acceptChanges');
    $('#dg').mdmegrid('unselectAll');
    SelIndex = -1;
});
   
function clickRow(index,row){  
    SelIndex = index;
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
}
       
var data = $('#invgr-hdr-gr-form').serializeArray();
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
            type: 'info',
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
                type: 'info',
            });
        }
        return;
    }else{
        $('#StatusBar').jnotifyAddMessage({
                text: ''+r+'',
                permanent: false,
                showIcon: true,
                type: 'info',
            });
    }  
    return true;
}        
      
function failed(r,sender){
    $('#StatusBar').jnotifyAddMessage({
        text: 'Failed on '+ sender,
        permanent: true,
        showIcon: true,
        type: 'error',
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

        var markplus = (sprise-pprise)/pprise * 100;
        $('#markup').val(parseInt(markplus));
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

        var markplus =  pprise * markup * 0.01;
        var sprise =  pprise + markplus;

        $('#sprise').val(parseInt(sprise));
     }
 });
        
var dataJson = [];
$('#ok').click(function(){ 
    var cditem = $('#cditem').val();
    var nmitem = $('#nmitem').val();
    var qtyitem = $('#qtyitem').val();
    var uom = $('#uom').val();
    var pprise = $('#pprise').val();
    var markup = $('#markup').val();
    var sprise = $('#sprise').val();
    var lnitem = $('#lnitem').val();    
    
    if(nmitem == '' || markup == '' || pprise == '' || sprise == '' || uom == '') return;
        
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
        
    if(SelIndex > -1) $('#delBtn').click(); 
    $('#cditem').focus();
});

$('#InvgrHdr_gr_num').keydown(function(event){
     if (event.keyCode == 13){ 
        var data = $('#invgr-form').serializeArray();
        $findGR
     }
 });
   
function grfound(r){
    var ret = JSON.parse(r);      
    var grhdr = ret.hdr;      
    var grdtl = ret.dtl;
        
    $('#InvgrHdr_gr_num').attr('readonly','true');
    $('#cancelBtn').click();
        
    $('#dg').mdmegrid('loadData',grdtl);        
    return true;
}
   
function grnone(r){
    alert('Failed on '+ r);
} 
      
$('#pprise').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
$('#markup').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
   
");
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "inv";

$judul = "Penerimaan Barang";
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
            'id' => 'invgr-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'gr_num'); ?></td>
                <td>
                    <?php
                    echo $form->textField($model, 'gr_num', array('size' => 12));
                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => '',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true')
                    ));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'refnum'); ?></td>
                <td>
                    <?php 
                    echo $form->textField($model, 'refnum'); 
                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn2',
                        'text' => '',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true')
                    ));
                    ?></td>
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
                            'url' => CController::createUrl('actGRWhse'),
                            'update' => '#InvgrHdr_cdwhse',
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
                <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td style="border-bottom: none;">
                    <?php
                    echo $form->textArea($model, 'dscrp', array('cols' => 24, 'rows' => '2',
                        'maxlength' => 64));
                    ?></td>
                <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'date_gr'); ?></td>
                <td style="border-bottom: none;">
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'date_gr',
                        'model' => $model,
                        'attribute' => 'date_gr',
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
                            'htmlOptions' => array('size' => '21', 'id' => 'nmitem', 'maxlength' => 64),
                            'methodChain' => ".result(chgCode)",
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::textField('qtyitem', '', array('size' => 5));
                        echo CHtml::hiddenField('lnitem');
                        ?>
                    </td>
                    <td>
                        <?php
                        $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                        //print_r($listnyo);
                        echo CHtml::dropDownList('uom', '', $listnyo, array('prompt' => '-Uom-',));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::textField('pprise', '', array('size' => '8', 'id' => 'pprise', 'maxlength' => 10));
                        ?>
                    </td>
                    <td>
                        <?php echo CHtml::textField('markup', '', array('size' => 4, 'maxlength' => 3)); ?>
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

        $this->beginWidget('mdmEui.MdmDialog', array(
            'htmlOptions' => array('id' => 'dlg'),
            'options' => array(
                'title' => 'Test Dialogue',
                'autoOpen' => false,
                'modal' => true,
                'width' => 'auto',
                'height' => 'auto',
                )));

        echo $this->renderPartial('_frmGR');
        $this->endWidget('mdmEui.MdmDialog');
        
        $this->beginWidget('mdmEui.MdmDialog', array(
            'htmlOptions' => array('id' => 'dlg2'),
            'options' => array(
                'title' => 'Test Dialogue',
                'autoOpen' => false,
                'modal' => true,
                'width' => 'auto',
                'height' => 'auto',
                )));

        echo $this->renderPartial('_frmRef');
        $this->endWidget('mdmEui.MdmDialog');
        ?>
    </div><!-- form -->
</div>