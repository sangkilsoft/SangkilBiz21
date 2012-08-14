<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fico-ncoa-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cdfiacc'); ?>
		<?php echo $form->textField($model,'cdfiacc',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'cdfiacc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dscrp'); ?>
		<?php echo $form->textField($model,'dscrp'); ?>
		<?php echo $form->error($model,'dscrp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dk'); ?>
		<?php echo $form->textField($model,'dk',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'dk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
		<?php echo $form->error($model,'level'); ?>
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

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id_coa'); ?>
		<?php echo $form->textField($model,'parent_id_coa'); ?>
		<?php echo $form->error($model,'parent_id_coa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'begining_balance'); ?>
		<?php echo $form->textField($model,'begining_balance'); ?>
		<?php echo $form->error($model,'begining_balance'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->