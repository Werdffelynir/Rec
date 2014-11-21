<?php

/**
 * @var $auth
 * @var $authData
 * @var $categories
 * @var $categoriesUsers
 */

// if($auth)
?>

<div class="grid clear">

    <div class="grid-11 first">

        <div class="public_snippets_box">
            <ul>
                <?php foreach ($categories as $c): ?>
                <li>
                    <a href="/cat/<?= $c['link']?>"><?= $c['title']?></a></li>
                <li>
                    <?php endforeach; ?>
                <li><li>
            </ul>
        </div>

        <div class="private_snippets_box" style="display: none;">
            <ul>
                <?php foreach ($categoriesUsers as $cu): ?>
                <li>
                    <a href="/cat/<?= $cu['link']?>"><?= $cu['title']?> pr</a></li>
                <li>
                    <?php endforeach; ?>
                <li><li>
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
    var isAuth = '<?=$auth?>';
    var isPublic = true;
    var toggleCat = $('.toggle_cat');
    var publicBox = $('.public_snippets_box');
    var privateBox = $('.private_snippets_box');

    toggleCat.click(function(){
        if(parseInt(isAuth)>=1){

            if(isPublic){
                publicBox.css('display','none');
                privateBox.css('display','block');
                toggleCat.text('Public');
                isPublic=false;
            }else{
                publicBox.css('display','block');
                privateBox.css('display','none');
                toggleCat.text('Private');
                isPublic=true;
            }
        }
    });
</script>


