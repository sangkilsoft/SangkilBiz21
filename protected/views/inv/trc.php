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

$findTrc = CHtml::ajax(array(
            'url' => array('findStock'),
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
    var href = 'index.php?r=rpt';
    window.open(href,'_newtab');
    return true;
});
        
$('#srcBtn').click(function(){
    $('#dg').mdmegrid('loading');
    var data = $('#invtrc-form').serializeArray();
    $findTrc
});
              
$('#newBtn').click(function(){
    var bisa = $('#newBtn').linkbutton('options');
    if(bisa.disabled) return false;
});

$('#saveBtn').click(function(){
        var bisa = $('#saveBtn').linkbutton('options');
        var tipe = $('#trns').html();        
        if(bisa.disabled) return false;
        
        if (!confirm('Are you sure?')) return false;
	else{
            var data = $('#invtrc-form').serializeArray();
            var datadtl = $('#dg').mdmegrid('getData');
            if(tipe == 'New..!') 
                $ajaxsimpan
            else 
                $ajaxupdate

            return false;
	}
});
    
$('#cancelBtn').click(function(){   
    var bisa = $('#cancelBtn').linkbutton('options');
    if(bisa.disabled) return false;
        
    $('#dg').mdmegrid('unselectAll');
        
    $('#delBtn').linkbutton('disable');
    $('#saveBtn').linkbutton('disable');
    $('#trns').html('');
});        
   
$('#delBtn').click(function(){ 
    var bisa = $('#delBtn').linkbutton('options');
    if(bisa.disabled) return false;
        
    $('#dg').mdmegrid('deleteRow',SelIndex);
    $('#dg').mdmegrid('acceptChanges');
    $('#dg').mdmegrid('unselectAll');
    SelIndex = -1;
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
      
function failed(r,sender){
    alert('Failed on '+ sender);
    $('#dg').mdmegrid('loadData',[]);
    $('#dg').mdmegrid('loaded');    
    return false;
}  
       
function chgCode(event,item){
      $('#cditem').val(item[2]);
      $('#nmitem').val(item[3]);
      $('#InvmvStock_cduom').val(item[4]);
}
        
$('#InvmvStock_limit').change(function(){
        $('#srcBtn').click();
    });
        
");
?>
<?php
if (!Yii::app()->user->isGuest)
            Yii::app()->user->mmenu = "inv";

$judul = "Penelusuran Stok";
$this->pageTitle = Yii::app()->name . " - $judul";
$this->widget('MenuBar');
?>
<script>
    
        
</script>
<div id="content-form">
    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'invtrc-form',
            'enableAjaxValidation' => false,
                ));
        ?>
        <table width="100%">
            <tr>
                <td width="16%"><?php echo $form->labelEx($model, 'cditem'); ?></td>
                <td width="45%">
                    <?php
                    //echo $form->textField($model, 'cditem', array('size' => 13, 'maxlength' => 13));
                    $this->widget('CAutoComplete', array(
                        'name' => 'cditem',
                        'cacheLength' => 0,
                        'url' => array('autoItem'),
                        'max' => 30,
                        'minChars' => 2,
                        'delay' => 100,
                        'matchCase' => false,
                        'htmlOptions' => array('size' => '9',
                            'id' => 'cditem',
                            'maxlength' => 13,
                            'name' => 'InvmvStock[cditem]'),
                        'methodChain' => ".result(chgCode)",
                    ));
                    echo '&nbsp;';
                    $this->widget('CAutoComplete', array(
                        'name' => 'nmitem',
                        'cacheLength' => 0,
                        'url' => array('autoItem'),
                        'max' => 30,
                        'minChars' => 2,
                        'delay' => 100,
                        'matchCase' => false,
                        'htmlOptions' => array('size' => '21',
                            'id' => 'nmitem',
                            'maxlength' => 64,
                            'name' => 'InvmvStock[nmitem]'
                        ),
                        'methodChain' => ".result(chgCode)",
                    ));
                    ?></td>
                <td width="16%"><?php echo $form->labelEx($model, 'cduom'); ?></td>
                <td>
                    <?php
                    $listnyo = CHtml::listData(MditemUom::model()->FindAll(), 'cduom', 'cduom');
                    echo CHtml::dropDownList('InvmvStock[cduom]', '', $listnyo, array('prompt' => '-Uom-',
                        'id' => 'InvmvStock_cduom'));
                    ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'cdunit'); ?></td>
                <td> 
                    <?php
                    $listunit = SysComp::getActiveUnit(Yii::app()->user->Id);
                    echo CHtml::DropDownList('InvmvStock[cdunit]', '', $listunit, array('width' => '700px',
                        'id' => 'InvmvStock_cdunit',
                        'prompt' => '--Select Unit--',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('actTWhse'),
                            'update' => '#InvmvStock_cdwhse',
                        ),
                    ));
                    ?>
                </td>
                <td><?php echo $form->labelEx($model, 'cdwhse'); ?></td>
                <td>
                    <?php
                    echo CHtml::activeDropDownList($model, 'cdwhse', array(), array('prompt' => '- Warehose -'));
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tanggal:</td>
                <td>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'InvmvStock[date_fr]',
                        'value' => $skrg,
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                            'id' => 'date_fr',
                        ),
                        'htmlOptions' => array(
                            'style' => 'width:100px;'
                        ),
                    ));
                    ?>
                    s.d.
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => 'InvmvStock[date_to]',
                        'value' => $skrg,
                        // additional javascript options for the date picker plugin
                        'options' => array(
                            'showAnim' => 'fold',
                            'dateFormat' => 'dd-mm-yy',
                            'id' => 'date_to',
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
                    echo CHtml::dropDownList('InvmvStock[limit]', '', $listnyo, array('id' => 'InvmvStock_limit')
                    );

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
                'singleSelect' => true,
            ),
            'columns' => array(
                array('field' => 'dtmv', 'title' => 'Tgl',
                    'htmlOptions' => array('width' => 90)),
                array('field' => 'cditem', 'title' => 'Code',
                    'htmlOptions' => array('width' => 100)),
                array('field' => 'itemdesc', 'title' => 'Description',
                    'htmlOptions' => array('width' => 250)),
//                array('field' => 'cduom', 'title' => 'Uom',
//                    'htmlOptions' => array('width' => 50)),
                array('field' => 'qtymv', 'title' => 'Trns',
                    'htmlOptions' => array('width' => 50)),
                array('field' => 'qtynow', 'title' => 'Stock',
                    'htmlOptions' => array('width' => 50)),
                array('field' => 'refnum', 'title' => 'Ref',
                    'htmlOptions' => array('width' => 1100),),
            ),
            'htmlOptions' => array(
                //'rownumbers' => "true",
                'fitColumns' => "false",
                'style' => "width:705px;height:340px",
            )
        ));
        ?>
    </div><!-- form -->
</div>
