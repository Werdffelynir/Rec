<?php

/**
 * @var \app\Base $this;
 */
?><!DOCTYPE <?= $this->doctype?>>
<html lang="<?= $this->lang?>">
<head>

    <meta charset="<?= $this->charset?>">
    <title><?= $this->title?></title>

    <?= $this->head?>
</head>
<body <?= $this->bodyAttr?>>

<?$this->out('layout')?>

<?= $this->body?>
</body>
</html>