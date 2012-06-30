<?php

$findSto = CHtml::ajax(array(
            'url' => array('findSto'),
            'data' => array('data' => 'js:data','type'=>'rpt'),
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r);}',
            'error' => 'js:function(r){failed(r);}'
        ));

$findStodtl = CHtml::ajax(array(
            'url' => array('findStoDtl'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){suksesDtl(r);}',
            'error' => 'js:function(r){failed(r);}'
        ));

Yii::app()->clientScript->registerScript('form', "  
$('#delBtn').linkbutton('disable');
$('#saveBtn').linkbutton('disable');
$('#delBtn').linkbutton('disable');
$('#newBtn').linkbutton('disable');
$('#cancelBtn').linkbutton('disable'); 
$('#srcBtn').linkbutton('enable');
        
var SelIndex = -1; 
            
$('#newBtn').click(function(){
    var href = 'index.php?r=inv/storeport';
    window.open(href,'_self');
    return true;
});

$('#srcBtn').click(function(){
    //$('#dg').mdmegrid('loading');
    var data = $('#invsto-form').serializeArray();
    $findSto
});        
   
function sukses(r,sender){
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){     
        $('#dg').mdmegrid('loadData',ret); 
        $('#printBtn').linkbutton('enable');
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
      
function failed(r,sender){
    alert('Failed on '+ sender);
}  
  
function clickRow(index,row){
    var data = row;
    $findStodtl
    return;
} 

function suksesDtl(r,sender){
    var ret = JSON.parse(r);        
    if(ret.type == 'S'){     
        $('#dgdtl').mdmegrid('loadData',ret); 
        $('#printBtn').linkbutton('enable');
    }else if(ret.type == 'E'){
        var msg = ret.message;     
        $('#StatusBar').jnotifyAddMessage({
                text: ''+msg+'',
                permanent: false,
                showIcon: true,
                type: 'error'
            });        
        $('#dgdtl').mdmegrid('loadData',ret); 
    }
    return true;
}   
");
?>
<?php
if (!Yii::app()->user->isGuest)
    Yii::app()->user->mmenu = "inv";

$judul = "Daftar Transfer";
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
            'id' => 'invsto-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table border="0" width="100%">
            <tr>
                <td><?php echo 'Unit tujuan'; ?></td>
                <td>
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::DropDownList('InvtrfHdr[cdunit]', '', $listunit, array('width' => '700px',
                        'id' => 'InvtrfHdr_cdunit',
                        'prompt' => '--Select Unit--',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('actStoWhse'),
                            'update' => '#InvtrfHdr_cdwhse2',
                        ),
                    ));
                    ?>
                </td>
                <td><?php echo 'Gudang tujuan'; ?></td>
                <td>
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse2', array(), array('prompt' => '- Warehose -'));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo 'Status'; ?></td>
                <td>
                    <?php
                    //echo $form->textField($model, 'refnum', array('size' => 12)); 
                    $listnyo = CHtml::listData(Vlookup::model()->FindAll('groupv=:groupv', array(':groupv' => 'transf_status')), 'cdlookup', 'dscrp');
                    echo CHtml::activeDropDownList($model, 'status', $listnyo, array('prompt' => '-- All --'));
                    ?>
                </td>
                <td><?php echo 'Tanggal'; ?></td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'InvtrfHdr[date_trf]',
                        'value' => '01-' . date('m-Y'),
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;'
                        ),
                    ));
                    echo " to ";
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'InvtrfHdr[date_to]',
                        'value' => date('d-m-Y'),
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
                <td><?php echo 'Record Limit'; ?></td>
                <td><?php
                    $listnyo = array('10' => '10', '20' => '20', '50' => '50', '100' => '100', '1000' => '1000');
                    echo CHtml::dropDownList('InvtrfHdr[limit]', '', $listnyo, array('id' => 'InvtrfHdr_limit')
                    );

                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => 'Find',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true', 'disabled' => 'disabled')
                    ));
                    ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <?php $this->endWidget(); ?>
        <br/>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dg',
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'singleSelect' => true,
                'fitColumns' => false,
            ),
            'columns' => array(
                array('field' => 'trf_num', 'title' => 'No.Transfer',
                    'htmlOptions' => array('width' => 100),),
                array('field' => 'date_trf', 'title' => 'Tgl',
                    'htmlOptions' => array('width' => 120),),
                array('field' => 'dscrp', 'title' => 'Deskripsi',
                    'htmlOptions' => array('width' => 340),),
                array('field' => 'statusd', 'title' => 'Status',
                    'htmlOptions' => array('width' => 80),),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:705px;height:150px",
            )
        ));
        ?>
        <br/>
        <?php
        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dgdtl',
            'dataUrl' => array('dataItems'),
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'onSelect' => 'js:function(index,row){clickRow(index,row);}',
                'singleSelect' => true,
                'fitColumns' => false,
            ),
            //{"type":"S","total":1,"rows":[{"cditem":"1000000000002","lnitem":10,"dscrp":"Kaos oblong v-neck","qtyitem":"12","uom":"ea","sprise":"110000","lnum":1,"subtotal":1320000}]}
            'columns' => array(
                array('field' => 'cditem', 'title' => 'Code Item',
                    'htmlOptions' => array('width' => 100)),
                array('field' => 'cditemsa', 'title' => 'Deskripsi',
                    'htmlOptions' => array('width' => 240)),
                array('field' => 'qtytrf', 'title' => 'Qty',
                    'htmlOptions' => array('width' => 60)),
                array('field' => 'cduom', 'title' => 'Uom',
                    'htmlOptions' => array('width' => 60)),
                array('field' => 'uomprice', 'title' => 'Harga',
                    'htmlOptions' => array('width' => 75)),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "true",
                'style' => "width:705px;height:200px",
            )
        ));
        ?>
    </div><!-- form -->
</div>