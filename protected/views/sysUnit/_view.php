<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdunit')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->cdunit), array('view', 'id'=>$data->cdunit)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdorg')); ?>:</b>
	<?php echo CHtml::encode($data->cdorg); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dscrp')); ?>:</b>
	<?php echo CHtml::encode($data->dscrp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_by')); ?>:</b>
	<?php echo CHtml::encode($data->update_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_by')); ?>:</b>
	<?php echo CHtml::encode($data->create_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />


</div>