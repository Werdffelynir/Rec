<?php

use app\Components\Helper;
/**
 * @var
 * @var $document
 */

?>

<div class="item">
    <h2><?=$document['title']?></h2>
    <div class="post"><?= $document['text']?></div>
    <div class="post">Date published:<?= $document['time']?> </div>
</div>
