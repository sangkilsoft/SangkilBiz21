<?php
$this->breadcrumbs=array(
	'Sys Units',
);

$this->menu=array(
	array('label'=>'Create SysUnit', 'url'=>array('create')),
	array('label'=>'Manage SysUnit', 'url'=>array('admin')),
);
?>

<h1>Sys Units</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
