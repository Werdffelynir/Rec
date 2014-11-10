<?php

use app\Components\Helper;
/**
 * @var
 * @var $list
 */

?>

<?foreach ($list as $item):?>
    <div class="item">
        <h2><a href="/doc/<?= $item['id']?>"><?=$item['title']?></a></h2>
        <div class="post"><?= Helper::limitWords($item['text'], 50)?></div>
        <div class="post">Date published:<?= $item['time']?>, <a href="/doc/<?= $item['id']?>">View full</a> </div>
    </div>
<?endforeach;?>