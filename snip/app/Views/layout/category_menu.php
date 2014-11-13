<?php

/**
 * @var $categories
 */
?>
<ul>
    <?php foreach ($categories as $c): ?>
    <li>
        <a href="/category/<?= $c['link']?>"><?= $c['title']?></a></li>
    <li>
    <?php endforeach; ?>
</ul>