<?php

/**
 * @var $subject
 * @var $email
 */
?>
<div class="contact_form">
    <h3>Contact Form</h3>
    <form action="">
        <div> <input name="name" type="text" value="<?=$subject?>"/> Your Name</div>
        <div> <input name="email" type="text" value="<?=$email?>"/> Your Email</div>
        <div> <textarea name="text" id="" cols="70" rows="4"></textarea></div>
        <div> <input type="submit" value="Send Massages"/></div>
    </form>
</div>