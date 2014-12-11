<?php

use rec\Rec;

?>

<div class="header_title grid_3 first">
    <h2><a href="/">Snippets Notes</a></h2>
</div>

<div class="header_search_form grid_6">
    <input name="search" type="text" value=""/><a href="#">Search</a>
</div>

<div class="header_menu grid_3">
    <a href="/panel">Admin Panel</a>
    <a href="/space">MySpace</a>
    <a href="/logout">Logout</a>
    <span class="land_menu">
        <a href="/en<?=Rec::$urlCurrent?>">En</a>|<a href="/ru<?=Rec::$urlCurrent?>">Ru</a>
    </span>
</div>
