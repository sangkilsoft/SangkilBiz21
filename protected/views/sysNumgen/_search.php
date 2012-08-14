<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cdnumgen'); ?>
		<?php echo $form->textField($model,'cdnumgen',array('size'=>13,'maxlength'=>13)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prefix'); ?>
		<?php echo $form->textField($model,'prefix',array('size'=>8,'maxlength'=>8)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pattern'); ?>
		<?php echo $form->textField($model,'pattern'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'startnum'); ?>
		<?php echo $form->textField($model,'startnum',array('size'=>13,'maxlength'=>13)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'year'); ?>
		<?php echo $form->textField($model,'year',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_value'); ?>
		<?php echo $form->textField($model,'last_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dscrp'); ?>
		<?php echo $form->textField($model,'dscrp',array('size'=>32,'maxlength'=>32)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_by'); ?>
		<?php echo $form->textField($model,'update_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_date'); ?>
		<?php echo $form->textField($model,'create_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_date'); ?>
		<?php echo $form->textField($model,'update_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_by'); ?>
		<?php echo $form->textField($model,'create_by'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->