<?php 
$judul = "Welcome to <i>". CHtml::encode(Yii::app()->name)."</i>";
$this->pageTitle=Yii::app()->name. ' - '.$judul; ?>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <tt><?php echo __FILE__; ?></tt></li>
	<li>Layout file: <tt><?php echo $this->getLayoutFile('main'); ?></tt></li>
</ul>
<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>

<?php

$dd1 = date("d-m-Y", mktime(0, 0, 0, 02, 10, 2012));
$dd2 = date("d-m-Y", mktime(0, 0, 0, 02, 25, 2012));

echo dateDiff($dd1, $dd2);

function dateDiff ($d1, $d2) {
  return round(abs(strtotime($d1)-strtotime($d2))/86400);
} 
?>
