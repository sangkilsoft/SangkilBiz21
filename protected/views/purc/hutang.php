<?php
Yii::app()->clientScript->registerScript('search', "        
    $('#dgtree').treegrid({
        onClickRow:function(node){
                var level = $('#dgtree').treegrid('getLevel');  
                var parent = $('#dgtree').treegrid('getSelected');
                //alert(parent['id']);
        }
    });        
");
?>
<?php
if (isset(Yii::app()->user->mmenu))
    Yii::app()->user->mmenu = "purc";

$judul = "Hutang Supplier";
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
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fico-hutang-form',
            'enableAjaxValidation' => false,
                ));

        $this->widget('mdmEui.grid.MjbTreegrid', array(
            'id' => 'dgtree',
            'dataUrl' => array('fico/hutangTree'),
            'options' => array(
                'pagination' => false,
                'onSelect' => 'js:function(index,row){clickRow();}',
                //'pageSize' => 30,
                //'singleSelect' => true,
                'idField' => 'id',
                'treeField' => 'txt',
                'showFooter' => false,
            ),
            'columns' => array(
                array('field' => 'txt', 'title' => 'Invoices/Vendor', 'htmlOptions' => array('width' => '360')),
                array('field' => 'total_hutang', 'title' => 'Total Hutang', 'htmlOptions' => array('width' => '100', 'align' => 'right')),
                array('field' => 'total_bayar', 'title' => 'Bayar', 'htmlOptions' => array('width' => '100', 'align' => 'right')),
                array('field' => 'sisa', 'title' => 'Sisa Hutang', 'htmlOptions' => array('width' => '100', 'align' => 'right')),
            ),
            'htmlOptions' => array(
                'rownumbers' => "false",
                'fitColumns' => "true",
            //'style' => "height:465px",
            )
        ));

        echo '<br>';

        $this->widget('mdmEui.grid.MdmEGrid', array(
            'id' => 'dgbayar',
            'options' => array(
                'pagination' => false,
                'rownumbers' => true,
                'singleSelect' => true,
                'showFooter' => true
            ),
            'htmlOptions' => array(
                'rownumbers' => "true",
                'fitColumns' => "true",
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
        $this->endWidget();
        ?>
    </div><!-- form -->
</div>