<?php
$findBB = CHtml::ajax(array(
            'url' => array('findBB'),
            'data' => 'js:data',
            'type' => 'POST',
            'success' => 'js:function(r){sukses(r);}',
            'error' => 'js:function(r){failed(r);}'
        ));

Yii::app()->clientScript->registerScript('form', "  
$('#delBtn').linkbutton('disable');
$('#saveBtn').linkbutton('disable');
$('#delBtn').linkbutton('disable');
$('#newBtn').linkbutton('disable');
$('#cancelBtn').linkbutton('disable'); 
$('#srcBtn').linkbutton('enable');  
        
$('#atree').tree({
        onClick:function(node){
            $('#dg').mdmegrid('loadData',[]);
            var isChild = $('#atree').tree('isLeaf', node.target);                
            if(!isChild) {
                goHdr();
                $(this).tree('toggle', node.target);      
            }
            else 
                goDtl(node.id,node.text);     
        }
    });

function goHdr(){
    $('#acc').val('');
}
        
function goDtl(acc,acctext){
     $('#acc').val(acctext);
     $('#acc_id').val(acc);
        
     $('#srcBtn').click();
}
        
        
$('#printBtn').click(function(){
    var href = 'index.php?r=rpt/GLDtl';
    window.open(href,'_newtab');
    return true;
});
     
$('#coa').change(function(){
    $('#srcBtn').click();        
});
        
$('#FicoGl_cdunit').change(function(){
    $('#srcBtn').click();        
});
        
$('#srcBtn').click(function(){
    $('#dg').mdmegrid('loading');
    var data = $('#rptGl-form').serializeArray();          
    $findBB        
});  
        
function sukses(r,sender){
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
    return true;
} 
        
function error(r,sender){
    $('#dg').mdmegrid('loadData',[]);
    $('#dg').mdmegrid('loaded');    
    return true;
} 
        
");
?>
<?php
$judul = "Buku Besar";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<div id="content-form">
    <div class="form">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'rptGl-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table width="100%">           
            <tr>
                <td style="border-bottom: none;"><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td style="border-bottom: none;">
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::DropDownList('FicoGl[cdunit]', '', $listunit, array('width' => '700px',
                        'id' => 'FicoGl_cdunit',
                        'prompt' => '--All Unit--',
                    ));
                    ?>
                </td>
            </tr>           
            <tr>
                <td style="border-bottom: none;">
                    <?php echo CHtml::label('Account', 'acc'); ?>
                </td>
                <td style="border-bottom: none;">
                    <?php
                    $modelacc = FicoCoa::model()->with('group')->findAll(array('order' => 'cdfiacc ASC'));
                    $datalist = CHtml::listData($modelacc, 'cdfiacc', 'dscrp', 'group.dscrp');
                    echo CHtml::dropDownList('FicoGl[acc_id]', '', $datalist, array('prompt' => '--Pilih Account--', 'id' => 'coa'
                    ));
                    ?>
                </td>
            </tr>
            <tr> 
                <td style="border-bottom: none; "><?php echo $form->labelEx($model, 'gl_date'); ?></td>
                <td style="border-bottom: none;">
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
                            'value' => '01-' . date('m-Y'),
                        ),
                    ));
                    echo " to ";
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'gl_date',
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;',
                            'value' => date('d-m-Y'),
                            'id' => 'FicoGl_gl_date2',
                            'name' => 'FicoGl[gl_date2]',
                        ),
                    ));

                    $this->widget('ext.mdmEui.MdmLinkButton', array(
                        'id' => 'srcBtn',
                        'text' => 'Find',
                        'htmlOptions' => array('iconCls' => 'icon-search', 'plain' => 'true', 'disabled' => 'disabled')
                    ));
                    ?>
                </td>
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
                'fitColumns' => true,
            ),
            'columns' => array(
//                array('field' => 'cdunit', 'title' => 'Unit',
//                    'htmlOptions' => array('width' => 60)),
                array('field' => 'gl_date', 'title' => 'Tgl',
                    'htmlOptions' => array('width' => 120, 'align' => 'center')),
                array('field' => 'cdfigl', 'title' => 'GL Number',
                    'htmlOptions' => array('width' => 130, 'align' => 'left')),
                array('field' => 'dscrp', 'title' => 'Deskripsi Transaksi',
                    'htmlOptions' => array('width' => 300)),
//                array('field' => 'cdfiacc', 'title' => 'Acc Num',
//                    'htmlOptions' => array('width' => 120)),
//                array('field' => 'coadscrp', 'title' => 'Acc Desc',
//                    'htmlOptions' => array('width' => 280)),
                array('field' => 'debit', 'title' => 'Debit',
                    'htmlOptions' => array('width' => 100, 'align' => 'right')),
                array('field' => 'kredit', 'title' => 'Kredit',
                    'htmlOptions' => array('width' => 100, 'align' => 'right')),
            ),
            'htmlOptions' => array(
                'style' => "width:707px; height:350px",
            )
        ));
        ?>
    </div><!-- form -->
</div>