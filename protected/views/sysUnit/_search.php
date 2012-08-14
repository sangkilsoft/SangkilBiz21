<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cdunit'); ?>
		<?php echo $form->textField($model,'cdunit',array('size'=>13,'maxlength'=>13)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cdorg'); ?>
		<?php echo $form->textField($model,'cdorg',array('size'=>13,'maxlength'=>13)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dscrp'); ?>
		<?php echo $form->textField($model,'dscrp',array('size'=>60,'maxlength'=>128)); ?>
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

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->