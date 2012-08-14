<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_coa'); ?>
		<?php echo $form->textField($model,'id_coa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cdfiacc'); ?>
		<?php echo $form->textField($model,'cdfiacc',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dscrp'); ?>
		<?php echo $form->textField($model,'dscrp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dk'); ?>
		<?php echo $form->textField($model,'dk',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'level'); ?>
		<?php echo $form->textField($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_date'); ?>
		<?php echo $form->textField($model,'update_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'parent_id_coa'); ?>
		<?php echo $form->textField($model,'parent_id_coa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'begining_balance'); ?>
		<?php echo $form->textField($model,'begining_balance'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->