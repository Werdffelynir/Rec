<?php

/**
 * @var $auth
 * @var array $userData
 */

?>

<div class="sidebar_box">
<?if($auth):?>

    <div class="user_info_box grid clear">
        <div class="user_info_r grid-8 first">
            <ul>
                <li class="fullname">
                    <a href="#"><span class="color_light"> <?=$userData['name']?> </span></a>
                </li>
                <li>Reputation: <span class="color_light"> <?=$userData['plus']?> </span></li>
                <li>Profession: <span class="color_light"> <?=$userData['profession']?> </span></li>
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
        <div class="user_info_l grid-4">
            <div class="user_ava">
                <img src="public/upload/user_ava/<?=$userData['ava']?>" alt=""/>
            </div>
        </div>
    </div>

<?else:?>

    <div class="box grid clear">
        <h2>Not register</h2>
    </div>

<?endif;?>
</div>