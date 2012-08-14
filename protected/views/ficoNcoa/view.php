<?php
$this->breadcrumbs=array(
	'Fico Ncoas'=>array('index'),
	$model->id_coa,
);

$this->menu=array(
	array('label'=>'List FicoNcoa', 'url'=>array('index')),
	array('label'=>'Create FicoNcoa', 'url'=>array('create')),
	array('label'=>'Update FicoNcoa', 'url'=>array('update', 'id'=>$model->id_coa)),
	array('label'=>'Delete FicoNcoa', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_coa),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FicoNcoa', 'url'=>array('admin')),
);
?>

<h1>View FicoNcoa #<?php echo $model->id_coa; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_coa',
		'cdfiacc',
		'dscrp',
		'dk',
		'level',
		'update_by',
		'update_date',
		'create_by',
		'create_date',
		'parent_id_coa',
		'begining_balance',
	),
)); ?>
