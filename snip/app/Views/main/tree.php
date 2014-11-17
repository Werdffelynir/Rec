<?php

use \rec\Rec;

function actMenu($link){
    $arg2 = Rec::$args['s_link'];
    if(isset($arg2) AND $link ==$arg2)
        return  "activeMenu";
}
/**
 * @var array $treeRecords;
 */
?>
<ul class="left_menu" id="left_ul">
<?php foreach($treeRecords as $sKey=>$sItems): ?>

    <li><a class="collapsed" href="#"> <?=$sKey;?> </a>
        <ul style="/*display: none;*/">

            <?php foreach($sItems as $sItem): ?>
                <li class="<?= actMenu($sItem->link)?>">
                    <?= '<a href="/snippet/'.$sItem->link.'"> '.$sItem->title.' </a>';?>
                </li>
            <?php endforeach; ?>

        </ul>
    </li>

<?php endforeach;?>
</ul>


