<ul>
    <?php
    $sub = Yii::app()->user->mmenu;
    if (!Yii::app()->user->isGuest && isset($this->_menu[$sub]) > 0) {
        foreach ($this->_menu[$sub] as $row) {
            echo "<li>";
            if (isset($row['img']))
                echo CHtml::image(Yii::app()->request->baseUrl . $row['img'], 'Ico', array("style" => "width:12px;height:13px"));
            echo "&nbsp;";
            echo CHtml::link($row['val'], array($row['url']));
            echo "</li>";
        }
    }
    ?>	
    <li>
        <?php
        echo CHtml::image(Yii::app()->request->baseUrl . '/images/ico/question-frame.png', 'Ico', array("style" => "width:12px;height:13px"));
        echo "&nbsp;";
        echo CHtml::link('About Us', array('/site/page', 'view' => 'about'));
        ?>
    </li>
    <li>
        <?php
        echo CHtml::image(Yii::app()->request->baseUrl . '/images/ico/user--minus.png', 'Ico', array("style" => "width:12px;height:13px"));
        echo "&nbsp;";
        echo CHtml::link('Logout (' . CHtml::encode(Yii::app()->user->name) . ')', array('site/logout'));
        ?>
    </li>
</ul>