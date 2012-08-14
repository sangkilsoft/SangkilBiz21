<?php

$judul = "";
$this->pageTitle = Yii::app()->name . ' - ' . $judul;
?>
<?php

$data = array(
    array('BlackID Lubeg', 160),
    array('D\'Zhomb', 160),
    array('BlackID PA', 160),
    array('PKanbaru 1', 260),
    array('PKanbaru 2', 100),
    array('PKanbaru 3', 60),
    array('PKanbaru 4', 120)
);
$this->widget('application.extensions.highcharts.HighchartsWidget', array(
    'options' => array(
        'series' => array(
            array('type' => 'pie',
                'data' => $data
            )
        ),
        'title' => 'SALES LEVEL',
        'tooltip' => array(
            'formatter' => 'js:function(){ return "<b>"+this.point.name+"</b> :"+this.y; }'
        ),
        'plotOptions' => array('pie' => (array(
        'allowPointSelect' => true,
        'showInLegend' => true,
        'cursor' => 'pointer',
            )
            )
        ),
        'credits' => array('enabled' => false),
    )
));
?>