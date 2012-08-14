<?php
$this->breadcrumbs=array(
	'Sys Numgens'=>array('index'),
	$model->cdnumgen=>array('view','id'=>$model->cdnumgen),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysNumgen', 'url'=>array('index')),
	array('label'=>'Create SysNumgen', 'url'=>array('create')),
	array('label'=>'View SysNumgen', 'url'=>array('view', 'id'=>$model->cdnumgen)),
	array('label'=>'Manage SysNumgen', 'url'=>array('admin')),
);
?>

<h1>Update SysNumgen <?php echo $model->cdnumgen; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>