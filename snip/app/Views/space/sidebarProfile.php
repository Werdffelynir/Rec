<?php

/**
 * @var array $userData
 * @var $status
 */

?>
<div class="sidebar_box">
    <div class="user_info_box grid clear">
        <div class="user_info_r grid_8 first">
            <ul>
                <li class="fullname">
                    <a href="#"><span class="color_light"> <?=$userData['name']?> </span></a>
                </li>
                <li>Reputation: <span class="color_light"> <b><?=($userData['plus']>0) ? '+'.$userData['plus']:'0'?></b> </span></li>
                <li>Specialize: <span class="color_light"> <?=$userData['profession']?> </span></li>
                <li>Status: <span class="color_light"><?=$status?></span> </li>
                <li>Programming Languages: <br>
                    <?php
                    $lang_words = explode(',',$userData['code_lang']);
                    if(is_array($lang_words))
                        foreach($lang_words as $lw)
                            echo '<a href="#"><span class="color_light">'.trim($lw).'</span></a>&nbsp;';
                    ?>
                </li>
            </ul>
        </div>
        <div class="user_info_l grid_4">
            <div class="user_ava">
                <img src="<?=\rec\Rec::$url?>public/upload/user_ava/<?=$userData['ava']?>" alt=""/>
            </div>
        </div>
    </div>

    <!--<div class="simple_btn big_btn">Create new article to blog (will soon)</div>-->

    <div class="edit_space">
        <a class="simple_btn space_btn" href="/space/profile">Edit profile</a>
        <hr/>
        <a class="simple_btn big_btn" href="/space/create">Create new snippet</a>
        <a class="simple_btn space_btn" href="/space/create">Edit Snippets</a>
        <a class="simple_btn space_btn" href="/space/create">Edit Category</a>
    </div>
</div>
<script type="application/javascript">

</script>