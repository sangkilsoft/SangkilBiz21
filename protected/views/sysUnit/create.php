<?php
$this->breadcrumbs=array(
	'Sys Units'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysUnit', 'url'=>array('index')),
	array('label'=>'Manage SysUnit', 'url'=>array('admin')),
);
?>

<h1>Create SysUnit</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>