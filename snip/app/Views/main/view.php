<?php


/**
 * @var object $record;
 */
?>

<div class="box content_item">
    <div class="grid clear">
        <div class="grid-6 first content_item_path">
            <p>
                <a href="/cat/<?=$record->cat_link?>"><?=$record->cat_title?></a>
                / <a href="/subcat/<?=$record->sub_cat_link?>"><?=$record->sub_cat_title?></a>
            </p>
        </div>
        <div class="grid-6">
            <div class="box_menu">
                <a href="#" style="text-align: center">+</a>
                <span style="color: #ffa615; font-weight: bold;text-align: center">
                    <?=$record->ithelp?>
                </span>
                <a href="#" style="text-align: center">-</a>
                <a href="#">favorite</a>
                <a href="/edit/<?=$record->link?>">edit</a>
                <a href="#" onclick="if(confirm('Delete this records?')){window.location='/delete/<?=$record->link?>';};">delete</a>
            </div>
        </div>
    </div>
    <hr/>
    <div class="grid clear">
        <div class="grid-10 first content_item_title">
            <p> <?=$record->title?> </p>
        </div>
        <div class="grid-2" style="text-align: right">
            <p><?=date('m.d.y',strtotime($record->datecreate))?></p>
        </div>
    </div>
</div>
<div class="content_item_tags">
    <?php if (!empty($record->tags)): $tags = explode(',',$record->tags)?>
        <?php foreach($tags as $tag): ?>
            <a href="/search/<?=trim($tag)?>"><?=trim($tag)?></a>
        <?php endforeach;?>
    <?php endif;?>&nbsp;
</div>


<?=$record->content?>


