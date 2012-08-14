<?php
$this->pageTitle = Yii::app()->name . ' - Error ' . $code;
//$this->breadcrumbs=array(
//	'Error',
//);
?>

<div class="error">
    <?php
    echo "<div class=\"span-15\">";
    echo "<br/>";
    echo "&nbsp;&nbsp;&nbsp;".CHtml::encode($message);
    echo "<br/><br/>";
    echo "</div>";
    ?>
</div>