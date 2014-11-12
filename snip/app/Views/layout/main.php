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
    <script type="text/javascript" src="<?= Rec::$url ?>public/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?= Rec::$url ?>public/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?= Rec::$url ?>public/js/main.js"></script>

    <?= $this->head?>
</head>
<body <?= $this->bodyAttr?>>

<div class="page">

    <div class="header box grid clear">
        <div class="grid-3 first"><h1>SNIPPETS NOTES</h1></div>
        <div class="grid-6"> <div class="box search"><input name="search" type="text"/> SEARCH</div> </div>
        <div class="grid-3">
            <div class="right_box">
                <div class="right_title simple_btn">Login</div>
                <div class="login_box box">
                    <input name="login" type="text" placeholder="Login" autocomplete="off" />
                    <input name="password" type="text" placeholder="Password" autocomplete="off"/>
                    <div class="box_menu">
                        <a href="#">go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cat_menu box grid">
        <?php Rec::inc('layout/cat_menu');?>
    </div>

    <div class="content_box grid clear">

        <div class="content_left box grid-8 first">
            <div class="box_menu">
                <a href="#">edit</a>
                <a href="#">delete</a>
                <a href="#">cancel</a>
                <a href="#">create</a>
            </div>
            text input name="search" input name="search" input name="search"
        </div>

        <div class="content_right box grid-4">
            <?php Rec::inc('layout/sub_cat_menu');?>
        </div>

    </div>

    <div class="footer box grid"></div>
</div>

<?= $this->body?>
</body>
</html>