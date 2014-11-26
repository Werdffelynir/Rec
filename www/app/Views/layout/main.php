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

<?$this->out('mainMenu')?>

<?$this->out()?>

<?$this->out('mainFooter')?>

<?= $this->body?>
</body>
</html>