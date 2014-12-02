<?php


$bindData = [
    'content'=>'
<div class="content_right box grid_3">
    <div class="edit grid clear">
        <div class="line_menu">
            <a href="#"> Create new snippet </a>
        </div>
        <div class="edit_line">
            <input type="text" value="" placeholder="Title"/>
        </div>
        <div class="edit_line">
            <span class="round_btn">Category</span> JavaScript
        </div>
        <div class="edit_line">
            <span class="round_btn">SubCategory</span> Processing Array JS
        </div>
        <div class="edit_line">
            <input type="checkbox" /> Locked snippet
        </div>
        <div class="edit_line">
            <input type="submit" value="Save snippet" />
        </div>
    </div>
</div>
    ',
];
$data = (!empty($data))?$data:$bindData;
?>

<div class="content_left box grid_9 first">
    <div class="edit">
        <div class="line_menu">
            <a href="#"> Create new snippet </a>
        </div>
        <div class="edit_line">
            <textarea name="" id="" cols="30" rows="10"><?=htmlspecialchars_decode($data['content'])?></textarea>
        </div>

    </div>
</div>

<div class="content_right box grid_3">
    <div class="edit grid clear">
        <div class="line_menu">
            <a href="#"> Snippet settings </a>
        </div>
        <div class="edit_line">
            <input type="text" value="" placeholder="Title"/>
        </div>
        <div class="edit_line">
            <span class="round_btn">Category</span> JavaScript
        </div>
        <div class="edit_line">
            <span class="round_btn">SubCategory</span> Processing Array JS
        </div>
        <div class="edit_line">
            <input type="checkbox" /> Locked snippet
        </div>
        <div class="edit_line">
            <input type="submit" value="Save snippet" />
        </div>
    </div>
</div>