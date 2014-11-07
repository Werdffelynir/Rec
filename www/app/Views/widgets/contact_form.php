<?php

/**
 * @var $subject
 * @var $email
 */
?>
<div class="contact_form">
    <h3>Contact Form</h3>
    <form name="contact" action="" method="post">
        <div> <input name="name" type="text" value="<?=$subject?>"/> Your Name</div>
        <div> <input name="email" type="text" value="<?=$email?>"/> Your Email</div>
        <div> <textarea name="text" id="" cols="70" rows="4"></textarea></div>
        <div> <input type="submit" value="Send Massages"/></div>
    </form>
</div>

<script>
    $('[name=contact]').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '/ajax/contact',
            data: $(this).serialize(),
            type: 'post',
            success: function(data){
                console.log(data);
            }
        });
    });
</script>