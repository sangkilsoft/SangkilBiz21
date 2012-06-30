<?php
$this->breadcrumbs=array(
	'Sys Units'=>array('index'),
	$model->cdunit,
);

$this->menu=array(
	array('label'=>'List SysUnit', 'url'=>array('index')),
	array('label'=>'Create SysUnit', 'url'=>array('create')),
	array('label'=>'Update SysUnit', 'url'=>array('update', 'id'=>$model->cdunit)),
	array('label'=>'Delete SysUnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cdunit),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysUnit', 'url'=>array('admin')),
);
?>

<h1>View SysUnit #<?php echo $model->cdunit; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cdunit',
		'cdorg',
		'dscrp',
		'update_by',
		'update_date',
		'create_by',
		'create_date',
	),
)); ?>
