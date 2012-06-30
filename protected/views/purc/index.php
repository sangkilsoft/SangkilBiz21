<?php
if (isset(Yii::app()->user->mmenu))
    Yii::app()->user->mmenu = "purc";

$judul = "";
$this->pageTitle = Yii::app()->name . ' - ' . $judul;
?>
<?php

$this->Widget('ext.highcharts.HighchartsWidget', array(
    'options' => array(
        'chart' => array('defaultSeriesType' => 'bar'),
        'title' => array('text' => 'TEST CHARTING'),
        'xAxis' => array(
            'categories' => array('Ciplukan', 'Kecacil', 'Kinco')
        ),
        'yAxis' => array(
            'title' => array('text' => 'Fruit eaten')
        ),
        'series' => array(
            array('name' => 'Jack', 'data' => array(6, 10, 4)),
            array('name' => 'Jane', 'data' => array(1, 3, 10)),
            array('name' => 'John', 'data' => array(5, 7, 16)),
            array('name' => 'Jesicca', 'data' => array(3, 4, 8)),
        ),
        'credits' => array('enabled' => true),
    )
));
?>
