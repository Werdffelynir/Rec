<?php

use app\Widgets\ContactWidget;

/**
 * @var \app\Base $this;
 * @var $title;
 * @var $content;
 */
?>
<div class="content">

    <h1><?php echo $title; ?></h1>
    <hr/>

    <?php echo $content; ?>

    <div class="clear-line">&nbsp;</div>

    <?php ContactWidget::widget(['subject'=>'Test Subject','email'=>'admin@admin.com']); ?>

    <div class="clear-line">&nbsp;</div>

</div>
