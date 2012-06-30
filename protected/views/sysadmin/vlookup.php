<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'vlookup-Vlookup-form',
        'enableAjaxValidation' => false,
            ));
    ?>
    <table border="0" width="100%">
            <tr>
                <td><?php echo $form->labelEx($model, 'cdlookup'); ?></td>
                <td><?php echo $form->textField($model, 'cdlookup'); ?></td>
            </tr>
            <tr>
                <td><?php echo $form->labelEx($model, 'dscrp'); ?></td>
                <td><?php echo $form->textField($model, 'dscrp'); ?></td>
            </tr>
            <tr>
                <td width="120px"><?php echo $form->labelEx($model, 'groupv'); ?></td>
                <td><?php echo $form->textField($model, 'groupv'); ?></td>
            </tr>
            <tr>
                <td style="border-bottom:0px; height: 50px;">&nbsp;</td>
                <td style="border-bottom:0px;"><?php echo CHtml::submitButton('Submit'); ?></td>
            </tr>
    </table>

    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('dataLookup'),
        'options' => array(
            'pagination' => false,
            'rownumbers' => true,
            'singleSelect' => true,
        ),
        'columns' => array(
            array('field' => 'groupv', 'title' => 'Group',
                'htmlOptions' => array('width' => 150, 'align' => 'left'),),
            array('field' => 'cdlookup', 'title' => 'Code',
                'htmlOptions' => array('width' => 100),),
            array('field' => 'dscrp', 'title' => 'Description',
                'htmlOptions' => array('width' => 500),),            
        ),
        'htmlOptions' => array(
            'fitColumns' => "true",
            'style' => "width:707px;height:300px;padding-top:0.2em;",
        )
    ));
    ?>
<?php $this->endWidget(); ?>

</div><!-- form -->