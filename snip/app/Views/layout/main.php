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

<?$this->out('header',true)?>

<?$this->out('menu',true)?>

<?$this->out()?>

<?$this->out('footer',true)?>

<?= $this->body?>
</body>
</html>