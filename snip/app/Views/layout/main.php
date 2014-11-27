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

<?$this->out('header')?>

<?$this->out('menu')?>

<?$this->out()?>

<?$this->out('footer')?>

<?= $this->body?>
</body>
</html>