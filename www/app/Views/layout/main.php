<?php

use rec\Rec;
/**
 * @var \app\Base $this;
 */
?><!DOCTYPE <?= $this->doctype?>>
<html lang="<?= $this->lang?>">
<head>
    <meta charset="<?= $this->charset?>">
    <title><?=$this->title?></title>

    <link type="text/css" rel="stylesheet" href="<?= Rec::$url ?>public/css/main.css"/>
    <script type="text/javascript" src="<?= Rec::$url ?>public/js/jquery.js"></script>

    <?= $this->head?>
</head>
<body <?= $this->bodyAttr?>>

<div class="top-menu full">
    <div class="page full menu">
        <a href="<?= Rec::$url ?>">Rec framework</a>
        <a href="<?= Rec::$url ?>docs">Documentation</a>
        <a href="<?= Rec::$url ?>download">Download</a>
        <?php if (!$this->auth): ?>
            <a href="<?= Rec::$url ?>login">Login</a>
        <?php else: ?>
            <a href="<?= Rec::$url ?>logout">Logout</a>
            <a href="<?= Rec::$url ?>admin">Admin panel</a>
        <?php endif; ?>
    </div>
</div>

<div class="page full clear">

    <?php $this->out('out'); ?>

    <div class="footer">
        Copyright © - 2014 SunLight, Inc. OL Werdffelynir. All rights reserved. <br>
        Was compiled per: <?php echo round(microtime(true) - START_TIMER, 4); ?> sec.
    </div>

</div>

<?= $this->body?>
</body>
</html>