<?php

use \rec\Rec;

/**
 * @var array $treeRecords;
 * @var array $userData;
 * @var $auth;
 */

function actMenu($link){
    $arg = Rec::urlArg();
    if(isset($arg) AND $link == $arg)
        echo "activeMenu";
}

?>

<div class="box panel">
    <div class="box_menu">
        <a href="#">Public</a>
        <a href="#" <?=(!$auth)?'class="box_menu_disabled"':''?>>Private</a>
        <a href="#" <?=(!$auth)?'class="box_menu_disabled"':''?>>Favorites</a>
    </div>
</div>

<ul class="left_menu" id="left_ul">
<?php foreach($treeRecords as $sKey=>$sItems): ?>

    <li><a class="collapsed" href="#"> <?=$sKey;?> </a>
        <ul style="/*display: none;*/">

            <?php foreach($sItems as $sItem): ?>
                <li class="<?actMenu($sItem->link)?>">
                    <?= '<a href="/snippet/'.$sItem->link.'"> '.$sItem->title.' </a>';?>
                </li>
            <?php endforeach; ?>

        </ul>
    </li>

<?php endforeach;?>
</ul>


