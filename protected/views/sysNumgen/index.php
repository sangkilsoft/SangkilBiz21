<?php
$this->breadcrumbs=array(
	'Sys Numgens',
);

$this->menu=array(
	array('label'=>'Create SysNumgen', 'url'=>array('create')),
	array('label'=>'Manage SysNumgen', 'url'=>array('admin')),
);
?>

<h1>Sys Numgens</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
