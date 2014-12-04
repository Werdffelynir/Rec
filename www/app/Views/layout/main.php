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

        <?$this->point('mainMenu')?>

        <?$this->point()?>

        <?$this->point('mainFooter')?>

    </div>

<?= $this->body?>
</body>
</html>