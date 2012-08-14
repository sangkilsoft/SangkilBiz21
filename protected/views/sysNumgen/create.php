<?php
$this->breadcrumbs=array(
	'Sys Numgens'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SysNumgen', 'url'=>array('index')),
	array('label'=>'Manage SysNumgen', 'url'=>array('admin')),
);
?>

<h1>Create SysNumgen</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>