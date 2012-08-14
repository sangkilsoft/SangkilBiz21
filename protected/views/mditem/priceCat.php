<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'mdprice-cat-priceCat-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'cdpcat'); ?>
<?php echo $form->textField($model, 'cdpcat'); ?>
<?php echo $form->error($model, 'cdpcat'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'dscrp'); ?>
<?php echo $form->textField($model, 'dscrp'); ?>
<?php echo $form->error($model, 'dscrp'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'update_by'); ?>
<?php echo $form->textField($model, 'update_by'); ?>
<?php echo $form->error($model, 'update_by'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'create_by'); ?>
<?php echo $form->textField($model, 'create_by'); ?>
<?php echo $form->error($model, 'create_by'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'update_date'); ?>
<?php echo $form->textField($model, 'update_date'); ?>
<?php echo $form->error($model, 'update_date'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'create_date'); ?>
<?php echo $form->textField($model, 'create_date'); ?>
<?php echo $form->error($model, 'create_date'); ?>
    </div>


    <div class="row buttons">
    <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->