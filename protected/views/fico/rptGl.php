<?php
$findGL = CHtml::ajax(array(
            'url' => array('findGL'),
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
 
$('#printBtn').click(function(){
    var href = 'index.php?r=rpt/GLDtl';
    window.open(href,'_newtab');
    return true;
});
        
$('#srcBtn').click(function(){
    $('#dg').mdmegrid('loading');
    var data = $('#rptGl-form').serializeArray();          
    $findGL        
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
    alert(r);
    $('#dg').mdmegrid('loaded');    
    return true;
} 
        
");
?>
<?php
$judul = "Journal Detail";
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
//{"idgldtl":"69","cdfigl":"GL11000011","cdfiacc":"1003","debit":"220000","kredit":"0","create_by":1,"create_date":"2012-05-05 16:54:34.967774","gl_date":"2012-05-05","dscrp":"Sales Retail"}

$this->widget('mdmEui.grid.MdmEGrid', array(
    'id' => 'dg',
    'options' => array(
        'pagination' => false,
        'rownumbers' => false,
        'onSelect' => 'js:function(index,row){clickRow(index,row);}',
        'singleSelect' => true,
        'fitColumns' => true,
    ),
    'columns' => array(
//                array('field' => 'cdunit', 'title' => 'Unit',
//                    'htmlOptions' => array('width' => 60)),
        array('field' => 'gl_date', 'title' => 'Tgl',
            'htmlOptions' => array('width' => 100, 'align' => 'center')),
        array('field' => 'cdfigl', 'title' => 'Kode',
            'htmlOptions' => array('width' => 120, 'align' => 'left')),
        array('field' => 'dscrp', 'title' => 'Deskripsi',
            'htmlOptions' => array('width' => 280)),
//                array('field' => 'cdfiacc', 'title' => 'Acc Num',
//                    'htmlOptions' => array('width' => 120)),
//                array('field' => 'coadscrp', 'title' => 'Acc Desc',
//                    'htmlOptions' => array('width' => 280)),
        array('field' => 'debit', 'title' => 'Debit',
            'htmlOptions' => array('width' => 150, 'align' => 'right')),
        array('field' => 'kredit', 'title' => 'Kredit',
            'htmlOptions' => array('width' => 150, 'align' => 'right')),
    ),
    'htmlOptions' => array(
        'style' => "width:705px;height:350px",
    )
));
?>
    </div><!-- form -->
</div>