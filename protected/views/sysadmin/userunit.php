<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'userunit-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'id'); ?>
        <?php echo $form->textField($model, 'id'); ?>
        <?php echo $form->error($model, 'id'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'cdunit'); ?>
        <?php echo $form->textField($model, 'cdunit'); ?>
        <?php echo $form->error($model, 'cdunit'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'dscrp'); ?>
        <?php echo $form->textField($model, 'dscrp', array('size' => '32')); ?>
        <?php echo $form->error($model, 'dscrp'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

    <?php $this->endWidget(); ?>
    <?php
    $this->widget('mdmEui.grid.MdmEGrid', array(
        'id' => 'dg',
        'dataUrl' => array('DataUserunit'),
        'options' => array(
            'pagination' => false,
            'rownumbers' => true,
            'onSelect' => 'js:function(index,row){clickRow(index,row);}',
            'singleSelect' => true,
            'fitColumns' => false,
        ),
        'columns' => array(
            array('field' => 'nama', 'title' => 'User',
                'htmlOptions' => array('width' => 100),),
            array('field' => 'unit', 'title' => 'Unit',
                'htmlOptions' => array('width' => 200),),
            array('field' => 'dscrp', 'title' => 'dscrp',
                'htmlOptions' => array('width' => 360),),
        ),
        'htmlOptions' => array(
            //'rownumbers' => "true",
            'fitColumns' => "true",
            'style' => "width:705px;height:300px",
        )
    ));
    ?>
</div><!-- form -->