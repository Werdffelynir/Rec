<?php

/**
 * @var $auth
 * @var $userData
 * @var $publicCategory
 * @var $privateCategory
 */

?>

<div class="grid clear">

    <div class="grid-11 first">

        <div class="public_snippets_box">
            <ul>
                <?php foreach ($publicCategory as $pub_c): ?>
                    <li><a href="/cat/<?= $pub_c['link']?>"><?= $pub_c['title']?></a></li>
                <?php endforeach; ?>
                <li></li>
            </ul>
        </div>

        <div class="private_snippets_box" style="display: none;">
            <ul>
                <?php foreach ($privateCategory as $prv_c): ?>
                    <li><a href="/private/cat/<?= $prv_c['link']?>"><?= $prv_c['title']?></a></li>
                <?php endforeach; ?>
                <li></li>
            </ul>
        </div>

    </div>

    <?php if($auth):?>
        <div class="grid-1">
            <div class="simple_btn toggle_cat" style="width: 73px; text-align: center; color: #FFC66D;">Private</div>
        </div>
    <?php endif;?>

</div>
<script>
    var cookieToggleCategory = $.cookie('toggle_category');
    var isAuth = '<?=$auth?>';
    var toggleCat = $('.toggle_cat');
    var publicBox = $('.public_snippets_box');
    var privateBox = $('.private_snippets_box');
    var isPublic = true;

    toggleCat.click(function(){

        if(parseInt(isAuth)>=1){

            if(isPublic){
                publicBox.css('display','none');
                privateBox.css('display','block');
                toggleCat.text('Public');
                isPublic=false;
                $.cookie('toggle_category', '0', {path: '/', domain: '<?=\rec\Rec::$urlDomain?>'});
            }else{
                publicBox.css('display','block');
                privateBox.css('display','none');
                toggleCat.text('Private');
                isPublic=true;
                $.cookie('toggle_category', '1', {path: '/', domain: '<?=\rec\Rec::$urlDomain?>'});
            }
        }
    });

    if(cookieToggleCategory == '0'){
        toggleCat.trigger('click');
    }

</script>


