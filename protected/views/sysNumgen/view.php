<?php
$this->breadcrumbs=array(
	'Sys Numgens'=>array('index'),
	$model->cdnumgen,
);

$this->menu=array(
	array('label'=>'List SysNumgen', 'url'=>array('index')),
	array('label'=>'Create SysNumgen', 'url'=>array('create')),
	array('label'=>'Update SysNumgen', 'url'=>array('update', 'id'=>$model->cdnumgen)),
	array('label'=>'Delete SysNumgen', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cdnumgen),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SysNumgen', 'url'=>array('admin')),
);
?>

<h1>View SysNumgen #<?php echo $model->cdnumgen; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cdnumgen',
		'prefix',
		'pattern',
		'startnum',
		'year',
		'date',
		'last_value',
		'dscrp',
		'update_by',
		'create_date',
		'update_date',
		'create_by',
	),
)); ?>
