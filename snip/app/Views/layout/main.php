<?php

/**
 * @var \app\BaseController $this;
 */
?><!DOCTYPE <?= $this->doctype?>>
<html lang="<?= $this->lang?>">
<head>

    <meta charset="<?= $this->charset?>">
    <title><?= $this->title?></title>

    <?= $this->head?>
</head>
<body <?= $this->bodyAttr?>>

    <div class="page">

        <div class="header grid clear">
            <?$this->pointOut('header',true)?>
        </div>

        <div class="menu grid clear">
            <?$this->pointOut('menu',true)?>
        </div>

        <div class="content grid clear">
            <?$this->point()?>
        </div>

        <div class="footer grid clear">
            <?$this->pointOut('footer',true)?>
        </div>

    </div>

<?= $this->body?>
</body>
</html>