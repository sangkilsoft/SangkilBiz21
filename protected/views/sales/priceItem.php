<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mditem-price-priceItem-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cditem'); ?>
		<?php echo $form->textField($model,'cditem'); ?>
		<?php echo $form->error($model,'cditem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lnitem'); ?>
		<?php echo $form->textField($model,'lnitem'); ?>
		<?php echo $form->error($model,'lnitem'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cduom'); ?>
		<?php echo $form->textField($model,'cduom'); ?>
		<?php echo $form->error($model,'cduom'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cdpcat'); ?>
		<?php echo $form->textField($model,'cdpcat'); ?>
		<?php echo $form->error($model,'cdpcat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price_comp'); ?>
		<?php echo $form->textField($model,'price_comp'); ?>
		<?php echo $form->error($model,'price_comp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prsn_price'); ?>
		<?php echo $form->textField($model,'prsn_price'); ?>
		<?php echo $form->error($model,'prsn_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'val_price'); ?>
		<?php echo $form->textField($model,'val_price'); ?>
		<?php echo $form->error($model,'val_price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->