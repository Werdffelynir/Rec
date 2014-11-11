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

<div class="page">
    <div class="header box grid"></div>
    <div class="cat_menu box grid">
        <?php include Rec::$pathApp.'Views/layout/cat_menu.php'?>
    </div>

    <div class="content_box grid clear">

        <div class="content_left box grid-8 first">
            content_left
        </div>

        <div class="content_right box grid-4">
            <?php include Rec::$pathApp.'Views/layout/sub_cat_menu.php'?>
        </div>

    </div>

    <div class="footer box grid"></div>
</div>

<?= $this->body?>
</body>
</html>