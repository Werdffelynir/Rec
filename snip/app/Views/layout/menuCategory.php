<?php

/**
 * @var $categories
 */
?>
<ul>
    <?php foreach ($categories as $c): ?>
    <li>
        <a href="/cat/<?= $c['link']?>"><?= $c['title']?></a></li>
    <li>
    <?php endforeach; ?>
</ul>