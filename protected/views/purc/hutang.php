<?php
$ajaxcari = CHtml::ajax(array(
            'url' => array('fico/findHutang'),
            'data' => array('pnum' => 'js:pnum'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesCari(r,\'create\');}',
            'error' => 'js:function(r){failedCari(r,\'create\');}'
        ));

$ajaxhutang = CHtml::ajax(array(
            'url' => array('fico/allHutang'),
            'data' => array('cdvend' => 'js:cdvend'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesHutang(r,\'create\');}',
            'error' => 'js:function(r){failedHutang(r,\'create\');}'
        ));

$ajaxbayar = CHtml::ajax(array(
            'url' => array('fico/findBayar'),
            'data' => array('pnum' => 'js:po_num'),
            'type' => 'POST',
            'success' => 'js:function(r){suksesBayar(r,\'create\');}',
            'error' => 'js:function(r){failedCari(r,\'create\');}'
        ));

Yii::app()->clientScript->registerScript('search', "        
    $('#atree').tree({
        onClick:function(node){
                var isChild = $('#atree').tree('isLeaf', node.target);
                
                if(!isChild) {
                    goVendor(node.id);
                    $(this).tree('toggle', node.target);      
                }
                else goDetail(node.id);
        }
    });
    
    function goDetail(nopo){
        var pnum = $.trim(nopo);
        $ajaxcari
        $('#dtlhutang').removeAttr('style');
        $('#hdrhutang').attr('style','display:none');
        $('#pohutang').removeAttr('style');
        return;
    }
    
    function goVendor(idvendor){
       $('#hdrhutang').removeAttr('style');
       $('#dtlhutang').attr('style','display:none');
       $('#pohutang').attr('style','display:none');
       var cdvend = $.trim(idvendor); 
       $ajaxhutang
    }
        
    function suksesHutang(r,sender){
        var ret = JSON.parse(r);  
        $('#dghutang').mdmegrid('loadData',ret); 
    }
        
    function failedHutang(r,sender){
        alert('failed');
    }

    function suksesCari(r,sender){
        var ret = JSON.parse(r);
        var dtl = ret.rows[0];
        var po_num = ret.rows[0].purch_num;
        
        $('#purch_num').val(dtl.purch_num);
        $('#total_hutang').val(dtl.total_hutang);
        $('#cdvend').val(dtl.cdvend);
        $('#total_bayar').val(dtl.total_bayar);
        $('#date_post').val(dtl.date_post);
        $('#sisa').val(dtl.total_hutang - dtl.total_bayar);        
        
        $ajaxbayar
        
        return false;
    }
        
    function suksesBayar(r,sender){
        var ret = JSON.parse(r);
        if(ret.type=='E'){
            $('#StatusBar').jnotifyAddMessage({
                text: ret.message,
                permanent: false,
                showIcon: true,
                type: 'error'
            });
            $('#dgbayar').mdmegrid('loadData',[]);
        }else if(ret.type=='S'){
            var ret = JSON.parse(r);  
            $('#dgbayar').mdmegrid('loadData',ret);
        }
    }

    function failedCari(r,sender){
        return false;
    }
        
");
?>
<?php
if (isset(Yii::app()->user->mmenu))
    Yii::app()->user->mmenu = "purc";

$judul = "Pembayaran Hutang";
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
            'id' => 'fico-hutang-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <div style="float:left; width: 170px; min-height: 400px; padding-right: 10px;">
            <?php
            $this->widget('mdmEui.MjbTree', array(
                'id' => 'atree',
                'dataUrl' => 'fico/vendorPO'
            ));
            ?>
        </div>
        <div style="float:left; width: 470px; padding-left: 10px; border-left: .04em grey solid;  ">
            <div id="hdrhutang">
                <?php
                $this->widget('mdmEui.grid.MdmEGrid', array(
                    'id' => 'dghutang',
                    'options' => array(
                        'pagination' => false,
                        'rownumbers' => true,
                        'singleSelect' => true,
                        'showFooter' => true
                    ),
                    'htmlOptions' => array(
                        //'rownumbers' => "true",
                        'fitColumns' => "true",
                        'style' => "width:517px;height:140px",
                    ),
                    'columns' => array(
                        array('field' => 'purch_num', 'title' => 'purch_num',
                            'htmlOptions' => array('width' => 110)),
                        array('field' => 'total_hutang', 'title' => 'total_hutang',
                            'htmlOptions' => array('width' => 60)),
                        array('field' => 'total_bayar', 'title' => 'total_bayar',
                            'htmlOptions' => array('width' => 60)),
                        array('field' => 'sisa', 'title' => 'sisa',
                            'htmlOptions' => array('width' => 60)),
                        array('field' => 'date_post', 'title' => 'date_post',
                            'htmlOptions' => array('width' => 80),),
                    ),
                ));
                ?>
                </br>
            </div>
            <div id="pohutang" style="display: none;">
                <table width="110%">
                    <tr>
                        <td width="23%">PO Number</td>
                        <td><?php echo CHtml::textField('purch_num', '', array('id' => 'purch_num', 'size' => 12)) ?></td>
                        <td width="23%">Total Hutang</td>
                        <td><?php echo CHtml::textField('total_hutang', '', array('id' => 'total_hutang', 'size' => 12)) ?></td>
                    </tr>
                    <tr>
                        <td width="23%">Vendor</td>
                        <td><?php echo CHtml::textField('cdvend', '', array('id' => 'cdvend', 'size' => 12)) ?></td>
                        <td width="23%">Dibayar</td>
                        <td><?php echo CHtml::textField('total_bayar', '', array('id' => 'total_bayar', 'size' => 12)) ?></td>
                    </tr>
                    <tr>
                        <td width="23%">Tgl Hutang</td>
                        <td><?php echo CHtml::textField('date_post', '', array('id' => 'date_post', 'size' => 12)) ?></td>
                        <td width="23%">Sisa Hutang</td>
                        <td><?php echo CHtml::textField('sisa', '', array('id' => 'sisa', 'size' => 12)) ?></td>
                    </tr>
                </table>
                </br>
            </div>
            <div id="dtlhutang">
                <?php
                $this->widget('mdmEui.grid.MdmEGrid', array(
                    'id' => 'dgbayar',
                    'options' => array(
                        'pagination' => false,
                        'rownumbers' => true,
                        'singleSelect' => true,
                        'showFooter' => true
                    ),
                    'htmlOptions' => array(
                        //'rownumbers' => "true",
                        'fitColumns' => "true",
                        'style' => "width:517px;height:240px;",
                    ),
                    'columns' => array(
                        array('field' => 'jml_bayar', 'title' => 'jml_bayar',
                            'htmlOptions' => array('width' => 80),),
                        array('field' => 'cdfigl', 'title' => 'cdfigl',
                            'htmlOptions' => array('width' => 80),),
                        array('field' => 'create_date', 'title' => 'create_date',
                            'htmlOptions' => array('width' => 80),),
                    ),
                ));
                ?>
            </div>           

        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>