
<?php
$ajaxcari = CHtml::ajax(array(
            'url' => array('fico/findHutang'),
            'data' => array('pnum' => 'js:pnum'),
            'type' => 'POST',
            'success' => 'js:function(r){adaHutang(r,\'create\');}',
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


$ajaxSave = CHtml::ajax(array(
            'url' => array('bayarHutang'),
            'data' => array('purch_num' => 'js:purch_num', 'cdvend' => 'js:cdvend', 'jml_bayar' => 'js:jml_bayar'),
            'type' => 'POST',
            'success' => 'js:function(r){sBayar(r,\'create\');}',
            'error' => 'js:function(r){fBayar(r,\'create\');}'
        ));

Yii::app()->clientScript->registerScript('search', "        
    $('#delBtn').linkbutton('disable'); 
    $('#printBtn').linkbutton('disable'); 
    $('#cancelBtn').linkbutton('disable'); 
    $('#saveBtn').linkbutton('disable'); 
        
    $('#atree').tree({
        onClick:function(node){
                var isChild = $('#atree').tree('isLeaf', node.target);                
                if(!isChild) {
                    //goVendor(node.id);
                    $(this).tree('toggle', node.target);      
                }
                else goDetail(node.id);
        }
    });
    
    $('#jmlbayar').keyup(function(){ 
        var jbayar = $('#jmlbayar').val();
        jbayar = jbayar.replace(/,/g, '');
        jbayar = parseFloat(jbayar); 
        
        if(jbayar > 0) 
            $('#saveBtn').linkbutton('enable'); 
        else $('#saveBtn').linkbutton('disable'); 
    });
       
    $('#saveBtn').click(function(){ 
        var bisa = $('#saveBtn').linkbutton('options');
        
        var purch_num = $('#purch_num').val();
        var jml_bayar = $('#jmlbayar').val();
        var cdvend = $('#cdvend').val();
        
        jml_bayar = jml_bayar.replace(/,/g, '');
        jml_bayar = parseFloat(jml_bayar); 
        
        if(bisa.disabled) return false;
        if (!confirm('Are you sure?')) return false;
        else $ajaxSave
        
        return;
    });

    function goDetail(nopo){        
        $('#dgbayar').mdmegrid('loading');
        var pnum = $.trim(nopo);
        $ajaxcari
        $('#dtlhutang').removeAttr('style');
        $('#hdrhutang').attr('style','display:none');
        $('#pohutang').removeAttr('style');        
        $('#dgbayar').mdmegrid('loaded');
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

    function adaHutang(r,sender){
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
            $('#dgbayar').mdmegrid('loadData',[]);
        }else if(ret.type=='S'){ 
            $('#dgbayar').mdmegrid('loadData',ret);
        }
    }

    function failedCari(r,sender){
        return false;
    }
        
    function sBayar(r,sender){
        var ret = JSON.parse(r);
        if(ret.type='S'){
            var purch_num = $('#purch_num').val();
            goDetail(purch_num);
        }
    }

    function fBayar(r,sender){
        alert('gagal');
    }
");
?>
<?php
if (isset(Yii::app()->user->mmenu))
    Yii::app()->user->mmenu = "purc";

$judul = "Detail Hutang";
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
            <div id="pohutang">
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
                        <td width="23%">Sudah bayar</td>
                        <td><?php echo CHtml::textField('total_bayar', '', array('id' => 'total_bayar', 'size' => 12)) ?></td>
                    </tr>
                    <tr>
                        <td width="23%" style="border-bottom: none;">Tgl Hutang</td>
                        <td style="border-bottom: none;"><?php echo CHtml::textField('date_post', '', array('id' => 'date_post', 'size' => 12)) ?></td>
                        <td width="23%" style="border-bottom: none;">Sisa Hutang</td>
                        <td style="border-bottom: none;"><?php echo CHtml::textField('sisa', '', array('id' => 'sisa', 'size' => 12)) ?></td>
                    </tr>
                </table>
                <br/>
                <?php
                $this->beginWidget('CActiveForm', array(
                    'id' => 'fico-bayar-form',
                    'enableAjaxValidation' => false,
                ));
                ?>
                <div style="padding-top: .5em; padding-left: .5em; width: 33.45em;" class="span-15">
                    Dibayar (Rp)
                    <?php
                    $this->widget('ext.moneymask.MMask', array(
                        'element' => '#jmlbayar',
                        'id' => 'masksprise',
                        'currency' => 'PHP',
                        'config' => array(
                            'showSymbol' => false,
                            'defaultZero' => false,
                            'precision' => 0,
                        )
                    ));
                    echo CHtml::textField('jmlbayar', '', array('id' => 'jmlbayar', 'size' => 15, 'maxlength' => 14, 'style' => 'text-align: right;'));
                    ?>
                </div>                
                <?php $this->endWidget(); ?>
                <br/>
                <?php
                $this->widget('mdmEui.grid.MdmEGrid', array(
                    'id' => 'dgbayar',
                    'options' => array(
                        'pagination' => false,
                        'rownumbers' => true,
                        'singleSelect' => true,
                        'fitColumns' => true,
                    ),
                    'htmlOptions' => array(
                        //'rownumbers' => "true",
                        'fitColumns' => "true",
                        'style' => "width:517px;height:240px;",
                    ),
                    'columns' => array(
                        array('field' => 'create_date', 'title' => 'Tgl Bayar',
                            'htmlOptions' => array('width' => 100),),
                        array('field' => 'cdfigl', 'title' => 'GL Number',
                            'htmlOptions' => array('width' => 180),),
                        array('field' => 'jml_bayar', 'title' => 'jml_bayar',
                            'htmlOptions' => array('width' => 200),),
                    ),
                ));
                ?>
            </div>
        </div>
    </div><!-- form -->
</div>