<?php $this->beginContent('//layouts/main'); ?>
<div id="StatusBar" ></div>
<div class="title">
    <?php
    $judul = explode("-", $this->pageTitle);
    echo CHtml::link(trim($judul[1]), "");
    ?>
</div>
<?php echo $content; ?>
<?php $this->endContent(); ?>