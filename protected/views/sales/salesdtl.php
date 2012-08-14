<?php
$findSales = CHtml::ajax(array(
            'url' => array('findSales'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r);}',
            'error' => 'js:function(r){failed(r);}'
        ));

Yii::app()->clientScript->registerScript('form', "  
$('#trns').html(''); 
$('#delBtn').linkbutton('disable'); 
$('#printBtn').linkbutton('disable'); 
$('#cancelBtn').linkbutton('disable'); 
$('#saveBtn').linkbutton('disable'); 
  
$('#newBtn').click(function(){
    var href = 'index.php?r=sales/salesdtl';
    window.open(href,'_self');
    return false;
});
   
$('#srcBtn').click(function(){
    $('#dg').mdmegrid('loading');
    var data = $('#saleshdr_form').serializeArray();
    $findSales
});
     
function sukses(r){
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){           
        $('#dg').mdmegrid('loadData',ret); 
        $('#printBtn').linkbutton('enable');
    }else if(ret.type == 'W'){
        alert(ret.message);
    }else if(ret.type == 'E'){
        alert(ret.message);
    }
    $('#dg').mdmegrid('loaded');    
        
//    var merges = [{  
//            index:2,  
//            rowspan:2  
//        },{  
//            index:5,  
//            rowspan:2  
//        }]; 
//        
//    for(var i=0; i<merges.length; i++)  
//        $('#dg').mdmegrid('mergeCells',{  
//            index:merges[i].index,  
//            field:'cditem',  
//            rowspan:merges[i].rowspan  
//        });  
        
    return false;  
}
  
function failed(r){
    alert('failed on');
    $('#dg').mdmegrid('loaded');    
}   
      
function clickRow(index,row){ 
    }
         
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
     
    //showTotal();        
}); 
      
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
$judul = "Detail Penjualan";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'saleshdr_form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td>
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
                <td><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse', array(), array('prompt' => '- From Warehose -'));
                    ?>
                </td>
            </tr>
            <tr align="right">
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
                    to
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'SalesHdr[date_sales2]',
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
                <td>Record Limit</td>
                <td>
                    <?php
                    $listnyo = array('10' => '10', '20' => '20', '50' => '50', '100' => '100', '1000' => '1000');
                    echo CHtml::dropDownList('SalesHdr[limit]', '', $listnyo, array('id' => 'SalesHdr_limit')
                    );

                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => 'Find',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true', 'disabled' => 'disabled')
                    ));
                    ?></td>
            </tr>            
        </table>
        <?php $this->endWidget(); ?>
        <br/>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            //'dataUrl' => array('dataItems'),
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'singleSelect' => true,
                'fitColumns' => true,
                'showFooter'=> true,
            ),
            'columns' => array(
                array('field' => 'cditem', 'title' => 'Code',
                    'htmlOptions' => array('width' => 105),
                    'selector' => '#cditem'),
                array('field' => 'itemdesc', 'title' => 'Description',
                    'htmlOptions' => array('width' => 220),
                    'selector' => '#nmitem'),
                array('field' => 'qty', 'title' => 'Qty',
                    'htmlOptions' => array('width' => 45,'align'=>'right'),
                    'selector' => '#qtyitem'),
                array('field' => 'uom', 'title' => 'Uom',
                    'htmlOptions' => array('width' => 45,'align'=>'center'),
                    'selector' => '#uom'),
                array('field' => 'uomprice', 'title' => 'Sales Price',
                    'htmlOptions' => array('width' => 90,'align'=>'right'),
                    'selector' => '#sprise'),
                array('field' => 'uomdiskon', 'title' => 'Disc(%)',
                    'htmlOptions' => array('width' => 55,'align'=>'right'),
                    'selector' => '#disk'),
                array('field' => 'stotal', 'title' => 'Sub Total',
                    'htmlOptions' => array('width' => 90,'align'=>'right'),
                    'selector' => '#stotal'),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:705px;height:360px",
            )
        ));
        ?>
    </div><!-- form -->
</div>
