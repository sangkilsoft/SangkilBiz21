<?php
$this->breadcrumbs=array(
	'Fico Ncoas',
);

$this->menu=array(
	array('label'=>'Create FicoNcoa', 'url'=>array('create')),
	array('label'=>'Manage FicoNcoa', 'url'=>array('admin')),
);
?>

<h1>Fico Ncoas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
