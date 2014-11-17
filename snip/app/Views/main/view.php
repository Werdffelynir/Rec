<?php

/**
 * @var object $record;
 */
//var_dump($record);
?>


<div class="box content_item">
    <div class="grid clear">
        <div class="grid-7 first content_item_path">
            <p>
                <a href="/cat/<?=$record->cat_link?>"><?=$record->cat_title?></a>
                / <a href="/cat/<?=$record->cat_link?>/<?=$record->sub_cat_link?>"><?=$record->sub_cat_title?></a>
            </p>
        </div>
        <div class="grid-5">
            <div class="box_menu">
                <a href="#">edit</a>
                <a href="#">delete</a>
                <a href="#">cancel</a>
                <a href="#">create</a>
            </div>
        </div>
    </div>
    <hr/>
    <div class="grid clear">
        <div class="grid-7 first incontent_item_tags">
            <p>
            <?php if (!empty($record->tags)): $tags = explode(',',$record->tags)?>
                <?php foreach($tags as $tag): ?>
                    <a href="/search/<?=trim($tag)?>"><?=trim($tag)?>;</a>
                <?php endforeach;?>
            <?php endif;?>
            </p>
        </div>
        <div class="grid-3" style="text-align: center">
            <span class="simple_btn">+</span>
            <?=$record->ithelp?>
            <span class="simple_btn">-</span>
        </div>
        <div class="grid-2" style="text-align: right">
            <p><?=date('m.d.y',strtotime($record->datecreate))?></p>
        </div>
    </div>
    <hr/>
    <div class="grid clear content_item_title">
        <?=$record->title?>
    </div>
</div>

<?=$record->content?>

<!--
<div class="edit">
    <textarea name="" id=""></textarea>
</div>
-->
