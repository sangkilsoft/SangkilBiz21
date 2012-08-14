<?php
$this->breadcrumbs=array(
	'Fico Ncoas'=>array('index'),
	$model->id_coa=>array('view','id'=>$model->id_coa),
	'Update',
);

$this->menu=array(
	array('label'=>'List FicoNcoa', 'url'=>array('index')),
	array('label'=>'Create FicoNcoa', 'url'=>array('create')),
	array('label'=>'View FicoNcoa', 'url'=>array('view', 'id'=>$model->id_coa)),
	array('label'=>'Manage FicoNcoa', 'url'=>array('admin')),
);
?>

<h1>Update FicoNcoa <?php echo $model->id_coa; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>