<?php

/**
 * @var array $allRecords;
 */
?>

<?php foreach($allRecords as $record): ?>

    <div class="box content_item">
        <div class="grid clear">
            <div class="grid-10 first"><a class="content_item_title" href="/snippet/<?=$record->link?>"> <?=$record->title?> </a></div>
            <div class="grid-2" style="text-align: right">+<?=$record->ithelp?></div>
        </div>
        <hr/>
        <div class="grid clear">
            <div class="grid-7 first content_item_path">
                  <a href="/cat/<?=$record->cat_link?>"><?=$record->cat_title?></a>
                / <a href="/cat/<?=$record->cat_link?>/<?=$record->sub_cat_link?>"><?=$record->sub_cat_title?></a>
            </div>
            <div class="grid-5 content_item_tags">
                <?php if (!empty($record->tags)): $tags = explode(',',$record->tags)?>
                    <?php foreach($tags as $tag): ?>
                        <a href="/search/<?=trim($tag)?>"><?=trim($tag)?></a>
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
    </div>

<?php endforeach;?>

<!--
<div class="box content_item">
    <div class="grid clear">
        <div class="grid-7 first"><p>Snippet title name</p></div>
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
        <div class="grid-9 first">
            <a href="">Category</a> : <a href="">Sub category</a>
                        <span class="tags">
                            <a href="">php</a>,
                            <a href="">ajax</a>,
                            <a href="">form</a>
                        </span>
        </div>
        <div class="grid-1">+5</div>
        <div class="grid-2">date: 02.15.2014</div>
    </div>
</div>


<div class="edit">
    <textarea name="" id=""></textarea>
</div>
-->