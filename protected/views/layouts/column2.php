<?php $this->beginContent('//layouts/main'); ?>
<div id="sidebar">
    <ul>
        <?php
        if (!Yii::app()->user->isGuest) {
            $this->widget('UserMenu', array('title' => Yii::app()->user->unit));
        }

        $this->widget('AddOn');
        ?>
    </ul>
</div><!-- sidebar -->
<div >
    <div id="content">
        <div id="StatusBar" ></div>
        <div class="title">
            <?php
            $judul = explode("-", $this->pageTitle);
            echo CHtml::link(trim($judul[1]), "");
            ?>
        </div>
        <?php echo $content; ?>
    </div><!-- content -->
</div>
<?php $this->endContent(); ?>