<?php
$this->breadcrumbs=array(
	'Sys Units'=>array('index'),
	$model->cdunit=>array('view','id'=>$model->cdunit),
	'Update',
);

$this->menu=array(
	array('label'=>'List SysUnit', 'url'=>array('index')),
	array('label'=>'Create SysUnit', 'url'=>array('create')),
	array('label'=>'View SysUnit', 'url'=>array('view', 'id'=>$model->cdunit)),
	array('label'=>'Manage SysUnit', 'url'=>array('admin')),
);
?>

<h1>Update SysUnit <?php echo $model->cdunit; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>