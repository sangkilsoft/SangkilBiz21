<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sys-unit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cdunit'); ?>
		<?php echo $form->textField($model,'cdunit',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'cdunit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cdorg'); ?>
		<?php echo $form->textField($model,'cdorg',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'cdorg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dscrp'); ?>
		<?php echo $form->textField($model,'dscrp',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'dscrp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by'); ?>
		<?php echo $form->error($model,'update_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_date'); ?>
		<?php echo $form->textField($model,'update_date'); ?>
		<?php echo $form->error($model,'update_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by'); ?>
		<?php echo $form->error($model,'create_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->