<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fico-bayar-rkphutang-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cdvend'); ?>
		<?php echo $form->textField($model,'cdvend'); ?>
		<?php echo $form->error($model,'cdvend'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'purch_num'); ?>
		<?php echo $form->textField($model,'purch_num'); ?>
		<?php echo $form->error($model,'purch_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lnum'); ?>
		<?php echo $form->textField($model,'lnum'); ?>
		<?php echo $form->error($model,'lnum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cdfigl'); ?>
		<?php echo $form->textField($model,'cdfigl'); ?>
		<?php echo $form->error($model,'cdfigl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by'); ?>
		<?php echo $form->error($model,'update_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by'); ?>
		<?php echo $form->error($model,'create_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jml_bayar'); ?>
		<?php echo $form->textField($model,'jml_bayar'); ?>
		<?php echo $form->error($model,'jml_bayar'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_date'); ?>
		<?php echo $form->textField($model,'update_date'); ?>
		<?php echo $form->error($model,'update_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
		<?php echo $form->error($model,'create_date'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->