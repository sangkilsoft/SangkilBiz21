<?php
$this->breadcrumbs=array(
	'Fico Ncoas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FicoNcoa', 'url'=>array('index')),
	array('label'=>'Manage FicoNcoa', 'url'=>array('admin')),
);
?>

<h1>Create FicoNcoa</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>