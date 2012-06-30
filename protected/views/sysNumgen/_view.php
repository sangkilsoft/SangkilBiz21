<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdnumgen')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cdnumgen), array('view', 'id'=>$data->cdnumgen)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prefix')); ?>:</b>
	<?php echo CHtml::encode($data->prefix); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pattern')); ?>:</b>
	<?php echo CHtml::encode($data->pattern); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startnum')); ?>:</b>
	<?php echo CHtml::encode($data->startnum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_value')); ?>:</b>
	<?php echo CHtml::encode($data->last_value); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('dscrp')); ?>:</b>
	<?php echo CHtml::encode($data->dscrp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_by')); ?>:</b>
	<?php echo CHtml::encode($data->update_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_by')); ?>:</b>
	<?php echo CHtml::encode($data->create_by); ?>
	<br />

	*/ ?>

</div>