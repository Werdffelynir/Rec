<?php

use \rec\Rec;

/**
 * @var array $treeRecords;
 * @var array $userData;
 * @var $auth;
 * @var $link;
 * @var $type;
 */

function actMenu($link){
    $arg = Rec::urlArg();
    if(isset($arg) AND $link == $arg)
        echo "activeMenu";
}

$linkTo = ($type=='private')?'/space':'';
?>

<div class="box panel">
    <div class="box_menu">
        <a href="/cat/<?=$link?>" class="<?=($type=='public')?'box_menu_active':''?>" >Public</a>
        <?php if($auth):?>
            <a href="/space/cat/<?=$link?>" class="<?=($type=='private')?'box_menu_active':''?>" >Private</a>
            <a href="/space/fav" class="">Favorites</a>
        <?php else:?>
            <a href="#" class="box_menu_disabled">Private</a>
            <a href="#" class="box_menu_disabled">Favorites</a>
        <?php endif;?>
    </div>
</div>

<ul class="left_menu" id="left_ul">
    <?php foreach($treeRecords as $sKey=>$sItems): ?>

    <li><a class="collapsed" href="#"> <?=$sKey;?> </a>
        <ul style="/*display: none;*/">

            <?php foreach($sItems as $sItem): ?>
                <li class="<?actMenu($sItem->link)?>">
                    <?= '<a href="'.$linkTo.'/snippet/'.$sItem->link.'"> '.$sItem->title.' </a>';?>
                </li>
            <?php endforeach; ?>

        </ul>
    </li>

<?php endforeach;?>
</ul>


