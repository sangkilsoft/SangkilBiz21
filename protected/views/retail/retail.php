<?php
$ajaxSave = CHtml::ajax(array(
            'url' => array('createRetail'),
            'data' => array('data' => 'js:data', 'datadtl' => 'js:datadtl'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

$ajaxBarang = CHtml::ajax(array(
            'url' => array('sales/directItem'),
            'data' => array('itemkey' => 'js:itemkey'),
            'type' => 'POST',
            'success' => 'js:function(r){sitem(r,\'create\');}',
            'error' => 'js:function(r){failed(r,\'create\');}'
        ));

Yii::app()->clientScript->registerScript('form', "  
$('#trns').html('New..!'); 
$('#delBtn').linkbutton('disable'); 
$('#printBtn').linkbutton('disable'); 
$('#cancelBtn').linkbutton('disable'); 
  
$('#newBtn').click(function(){
    var href = 'index.php?r=retail/retail';
    window.open(href,'_self');
    return false;
});

$('#saveBtn').click(function(){
    var bisa = $('#saveBtn').linkbutton('options');
    var tipe = $('#trns').html();        
    if(bisa.disabled) return false;
    if (!confirm('Are you sure?')) return false;
    else{
        var data = $('#SalesHdr_form').serializeArray();
        var datadtl = $('#dg').mdmegrid('getData');
        if(tipe == 'New..!') 
            $ajaxSave
        return false;
    }
});
        
function sukses(r,source){
    var ret = JSON.parse(r);
    if(ret.type == 'S'){  
        $('#StatusBar').jnotifyAddMessage({
            text: ''+ret.message+'',
            permanent: true,
            showIcon: true,
            type: 'success'
        });
        dataJson = [];
        $('#dg').mdmegrid('loadData',dataJson);
        $('#saveBtn').linkbutton('disable');
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
    }
}
        
function sitem(r,source){
      var item = r.split('|');
      chgCode(null,item);
        
//      $('#cditem').val(item[2]);
//      $('#nmitem').val(item[3]);
//      $('#lnitem').val(item[1]);
//      $('#uom').val(item[4]);
//      $('#sprise').val(item[5]);
//      $('#disk').val('0');
//      $('#qtyitem').val('1');
//      $('#qtyitem').select();        
}
        
function failed(r,source){
      alert('failed on '+source);
}   
        
function chgCode(event,item){
      $('#cditem').val(item[2]);
      $('#nmitem').val(item[3]);
      $('#lnitem').val(item[1]);
      $('#uom').val(item[4]);
      $('#sprise').val(item[5]);
      $('#disk').val('0');
      $('#qtyitem').val('1');
      $('#qtyitem').select();        
}
        
function clickRow(index,row){ 
    $('#delBtn').linkbutton('enable');
    $('#saveBtn').linkbutton('enable');
        
    if(typeof row['lnum'] != 'undefined') $('#lnum').val(row['lnum']);
    else  $('#lnum').val('');
      
    if(typeof row['lnitem'] != 'undefined') $('#lnitem').val(row['lnitem']);
    else  $('#lnitem').val('');
}
   
$('#delBtn').click(function(){
    var row = $('#dg').datagrid('getSelected');
    if (row){         
        $('#cditem').val('');
        $('#nmitem').val('');
        $('#qtyitem').val('');
        $('#uom').val('');
        $('#stotal').val('');
        $('#disk').val('');
        $('#sprise').val('');
        $('#lnitem').val('');
        $('#cditem').focus();    
        
        var index = $('#dg').datagrid('getRowIndex', row);
        $('#dg').datagrid('deleteRow', index);
        $('#dg').mdmegrid('acceptChanges');
        $('#dg').mdmegrid('unselectAll');
    } 
    showTotal();
});
      
$('#qtyitem').keyup(function(event){
    if (event.keyCode == 13) 
        return;
        
    hitTotal(event);
 });
 
$('#qtyitem').keydown(function(event){
     if (event.keyCode == 13){
        hitTotal(event);
        $('#ok').click();
     }
 });
 
$('#disk').keyup(function(event){
    if (event.keyCode == 13) 
        return;
        
    hitTotal(event);
 });
 
$('#disk').keydown(function(event){
     if (event.keyCode == 13){
        $('#ok').click();
     }
 });
 
function hitTotal(event){
    var qtyitem = $('#qtyitem').val();
    var sprise = $('#sprise').val();
    var disk = $('#disk').val();
        
    if(qtyitem == '' || sprise == '') 
        return;
        
    sprise = sprise.replace(/,/g, '');
    sprise = parseFloat(sprise); 
    
    sprise = sprise - ((sprise*disk)/100);
    
    qtyitem = qtyitem.replace(/,/g, '');
    qtyitem = parseFloat(qtyitem); 

    var stotal =  sprise * qtyitem;

    $('#stotal').val(money_format(stotal));
}
        
$('#cditem').keydown(function(event){
    if (event.keyCode == 13){
        var itemkey = $('#cditem').val();
        $ajaxBarang
     }
 });
        
function showTotal(){            
    var jml = 0;        
    var i = 0;
    var tempSTotal = 0;
    for ( property in dataJson )
    {
        if(dataJson.hasOwnProperty(property))
        {
            tempSTotal = dataJson[i].stotal;
            tempSTotal = tempSTotal.replace(/,/g, '');
            jml = jml + parseFloat(tempSTotal);
            i++;
        }
    }

    var rupiah = jml;
    rupiah = money_format(rupiah);
    $('#total').html('Rp'+rupiah+',-');
}
        
var dataJson = [];
$('#ok').click(function(){ 
    var cditem = $('#cditem').val();
    var nmitem = $('#nmitem').val();
    var qtyitem = $('#qtyitem').val();
    var uom = $('#uom').val();
    var disk = $('#disk').val();
    var sprise = $('#sprise').val();
    var lnitem = $('#lnitem').val(); 
    var stotal = $('#stotal').val();
        stotal = stotal.replace(/,/g, '');
        stotal = parseFloat(stotal); 
        stotal = money_format(stotal);
    var lnum = $('#lnum').val(); 
   
    var row = $('#dg').datagrid('getSelected');
    if (row){  
        var index = $('#dg').datagrid('getRowIndex', row);
        var data = {'lnum':lnum, 'cditem':cditem, 'nmitem': nmitem, 'lnitem': lnitem, 'qtyitem': qtyitem, 'uom':uom, 'sprise':sprise, 'disk':disk, 'stotal':stotal };
        $('#dg').datagrid('updateRow',{index: index,row: data});
        $('#dg').mdmegrid('unselectAll');
    }else{
        if(nmitem == '' || sprise == '' || uom == '' || qtyitem == '' ) return;
        dataJson.push({'cditem':cditem, 'nmitem': nmitem, 'lnitem': lnitem, 'qtyitem': qtyitem, 'uom':uom, 'sprise':sprise, 'disk':disk, 'stotal':stotal });
        $('#dg').mdmegrid('loadData',dataJson);
    }
        
    $('#cditem').val('');
    $('#nmitem').val('');
    $('#qtyitem').val('');
    $('#uom').val('');
    $('#stotal').val('');
    $('#disk').val('');
    $('#sprise').val('');
    $('#lnitem').val('');
    $('#cditem').focus();    
    $('#saveBtn').linkbutton('enable'); 
     
    showTotal();        
}); 
      
$('#sprise').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
$('#markup').maskMoney({'showSymbol':false,'defaultZero':false,'precision':0,'symbol':'\u20b1'});
   
");
?>
<script type="text/javascript">
    function money_format(number)
    {
        if (isNaN(number)) return "";
        var str = new String(number);
        var result = "" ,len = str.length;           
        for(var i=len-1;i>=0;i--)
        {           
            if ((i+1)%3 == 0 && i+1!= len) result += ",";
            result += str.charAt(len-1-i);
        }       
        return result;
    }
</script>
<?php
$judul = "Penjualan Retail";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $this->widget('ext.jnotify.JNotify', array(
            'statusBarId' => 'StatusBar',
            'notificationId' => 'Notification',
            'notificationHSpace' => '30px',
            'notificationWidth' => '280px',
            'notificationShowAt' => 'topRight',
        ));

        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'SalesHdr_form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr align="right">
                <td>
                    <?php
                    echo $form->labelEx($model, 'sal_num');
                    echo $form->error($model, 'sal_num');
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->textField($model, 'sal_num');
                    ?>
                </td>
                <td>
                    <?php
                    echo $form->labelEx($model, 'date_sales');
                    echo $form->error($model, 'date_sales');
                    ?>
                </td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'SalesHdr[date_sales]',
                        'model' => $model,
                        'attribute' => 'date_sales',
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
                <td rowspan="2" style="padding-left: 10px; width: 10px; border-bottom: none;"  >
                    <div id="total" style='-moz-border-radius:5px; font-size: 52px; padding-left: 10px; padding-right: 10px; text-align: right; background-color: #AA2808; color:white;'>Rp0,-</div>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td style="border-bottom: none;">
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::DropDownList('SalesHdr[cdunit]', '', $listunit, array('width' => '700px',
                        'id' => 'SalesHdr_cdunit',
                        'prompt' => '--Select Unit--',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('sales/actStoWhse'),
                            'update' => '#SalesHdr_cdwhse',
                        ),
                    ));
                    ?>
                </td>
                <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td style="border-bottom: none;">
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse', array(), array('prompt' => '- From Warehose -'));
                    ?>
                </td>
            </tr>
        </table>
        <?php 
        $this->endWidget(); 
        echo "<br/>";
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'entribar_form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <div id="entribar">
            <table border="0" width="100%" class="span-15">
                <tr>
                    <td>
                        <?php
                        /*
                          echo CHtml::textField('cditem', '', array('size' => 12));
                          $this->widget('CAutoComplete', array(
                          'name' => 'cditem',
                          'cacheLength' => 0,
                          'url' => array('sales/autoItem'),
                          'max' => 30,
                          'minChars' => 2,
                          'delay' => 100,
                          'matchCase' => false,
                          'htmlOptions' => array('size' => 11, 'id' => 'cditem', 'maxlength' => 13),
                          'methodChain' => ".result(chgCode)",
                          ));
                         */
                        echo CHtml::textField('cditem', '', array('size' => 11, 'id' => 'cditem', 'maxlength' => 13))
                        ?>
                    </td>
                    <td>
                        <?php
                        //echo CHtml::textField('nmitem', '', array('size' => 21)); 
                        $this->widget('CAutoComplete', array(
                            'name' => 'nmitem',
                            'cacheLength' => 0,
                            'url' => array('sales/autoItem'),
                            'max' => 30,
                            'minChars' => 2,
                            'delay' => 100,
                            'matchCase' => false,
                            'htmlOptions' => array('size' => 31, 'id' => 'nmitem', 'maxlength' => 64),
                            'methodChain' => ".result(chgCode)",
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::textField('qtyitem', '', array('size' => '7'));
                        echo CHtml::hiddenField('lnitem');
                        echo CHtml::hiddenField('lnum');
                        ?>
                    </td>
                    <td>
                        <?php
                        $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                        //print_r($listnyo);
                        echo CHtml::dropDownList('uom', '', $listnyo, array('prompt' => '-- Uom --'));
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
                        echo CHtml::textField('sprise', '', array('size' => 11, 'maxlength' => 10, 'readonly' => 'true'));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo CHtml::textField('disk', '0', array('size' => 6, 'maxlength' => 4));
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
                        echo CHtml::textField('stotal', '', array('size' => 12, 'maxlength' => 10, 'readonly' => 'true'));
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
        $this->endWidget(); 
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            //'dataUrl' => array('dataItems'),
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'singleSelect' => true,
                'fitColumns' => false,
            ),
            'columns' => array(
                array('field' => 'cditem', 'title' => 'Code',
                    'htmlOptions' => array('width' => 95),
                    'selector' => '#cditem'),
                array('field' => 'nmitem', 'title' => 'Description',
                    'htmlOptions' => array('width' => 300),
                    'selector' => '#nmitem'),
                array('field' => 'qtyitem', 'title' => 'Qty',
                    'htmlOptions' => array('width' => 75),
                    'selector' => '#qtyitem'),
                array('field' => 'uom', 'title' => 'Uom',
                    'htmlOptions' => array('width' => 100),
                    'selector' => '#uom'),
                array('field' => 'sprise', 'title' => 'Sales Price (@Rp)',
                    'htmlOptions' => array('width' => 120),
                    'selector' => '#sprise'),
                array('field' => 'disk', 'title' => 'Disc (%)',
                    'htmlOptions' => array('width' => 75),
                    'selector' => '#disk'),
                array('field' => 'stotal', 'title' => 'Sub Total (Rp)',
                    'htmlOptions' => array('width' => 120),
                    'selector' => '#stotal'),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:950px;height:340px",
            )
        ));
        ?>
    </div><!-- form -->
</div>
