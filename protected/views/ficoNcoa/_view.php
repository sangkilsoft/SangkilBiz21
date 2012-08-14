<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_coa')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_coa), array('view', 'id'=>$data->id_coa)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cdfiacc')); ?>:</b>
	<?php echo CHtml::encode($data->cdfiacc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dscrp')); ?>:</b>
	<?php echo CHtml::encode($data->dscrp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dk')); ?>:</b>
	<?php echo CHtml::encode($data->dk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('level')); ?>:</b>
	<?php echo CHtml::encode($data->level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_by')); ?>:</b>
	<?php echo CHtml::encode($data->update_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('create_by')); ?>:</b>
	<?php echo CHtml::encode($data->create_by); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id_coa')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id_coa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('begining_balance')); ?>:</b>
	<?php echo CHtml::encode($data->begining_balance); ?>
	<br />

	*/ ?>

</div>