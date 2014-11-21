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
    <script type="text/javascript" src="/public/js/jquery.js"></script>
    <script type="text/javascript" src="/public/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/public/js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="/public/js/main.js"></script>

    <?= $this->head?>
</head>
<body <?= $this->bodyAttr?>>

<div class="page">

    <div class="header box grid clear">
        <div class="grid-3 first"><a class="logo" href="/"> <h1>SNIPPETS NOTES</h1> </a></div>
        <div class="grid-6"> <div class="box search"><input name="search" type="text"/> SEARCH</div> </div>
        <div class="grid-3">
            <div class="right_box">
                <?php if($this->auth): ?>
                    <a class="right_title simple_btn" href="/create">Create New</a>
                    <a class="right_title simple_btn" href="/logout">Logout</a>
                <?php else: ?>
                    <a class="right_title simple_btn" href="/register">Register</a>
                    <div class="right_title simple_btn on_btn_login">Login</div>
                    <div class="login_box box">
                        <form action="/login" method="post">
                            <input name="login" type="text" value="werdffelynir@gmail.com" placeholder="email@address" autocomplete="off" />
                            <input name="password" type="password" value="werd000666" placeholder="password" autocomplete="off"/>
                            <input type="submit" class="simple_btn"  value="Login" />
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="cat_menu box grid">
        <?php $this->viewCategory(); ?>
    </div>

    <div class="content_box grid clear">
        <?php $this->out('default'); ?>
    </div>

    <div class="footer box grid">
        Copyright © - 2014 SunLight, Inc. OL Werdffelynir. All rights reserved. <br>
        Was compiled per: <?php echo round(microtime(true) - START_TIMER, 4); ?> sec.
    </div>
</div>

<?= $this->body?>
</body>
</html>