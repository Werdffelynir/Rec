<?php

/**
 * @var array $formData
 * @var $userId
 */

?>
<form method="post" class="register_form">

        <div class="edit">

            <p>Full Name</p>
            <input name="full_name" type="text" value="" placeholder="Search tags" />

            <p>Email</p>
            <input name="email" type="text" value="" placeholder="Search tags" />

            <p>Password</p>
            <input name="password" type="text" value="" placeholder="Search tags" />

            <p>Password again</p>
            <input name="password_again" type="text" value="" placeholder="Search tags" />

            <div class="checked_user grid clear">
                <div class="checked_user_box grid-1 first"></div>
                <div class="checked_user_text grid-11">you're a human?</div>
            </div>

            <input type="submit" value="Register" class="simple_btn">
            <span class="reg_error_text"></span>
            <!--<div class="simple_btn">Register</div>-->
        </div>

</form>

<div class="register_form_after">
    <h2>Licenthia</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab alias autem blanditiis commodi deleniti deserunt esse
        fugiat hic ipsam minus nesciunt, optio praesentium ratione rem similique soluta temporibus ut vitae?</p><p>
        Aspernatur commodi consequuntur corporis ducimus earum eligendi, explicabo in ipsam iste laboriosam magni
        necessitatibus nemo nesciunt nostrum odio odit officia officiis pariatur praesentium repellendus repudiandae sequi
        tempore vitae voluptate voluptatem.</p><p>A aliquam autem corporis debitis ducimus eaque eligendi enim error
        explicabo fugiat ipsa ipsum maiores mollitia, necessitatibus nesciunt nulla odio possimus provident quis sapiente,
        sed sunt totam unde veniam voluptatibus.</p><p>Ad alias aliquam beatae blanditiis corporis, cum, deleniti eius
        expedita facilis harum iste iusto minima nam officia possimus praesentium provident quidem quod repudiandae sapiente
        sint soluta vero voluptatum. Odio, veniam.</p>
</div>

<script>

    var isChecked = false;

    $('.checked_user_box').click(function(){
        var uBox = $(this);
        uBox.toggleClass('checked_user_box_ok');
        isChecked = uBox.hasClass('checked_user_box_ok');
    });

    $('.register_form').submit(function(e){
        e.preventDefault();
        $('.reg_error_text').html('');
        var field = '';

/*
        var inputs = $('.register_form input[type=text]');
        for (var i=0; i<inputs.length; i++)
            if(inputs[i].value.length < 3){
                isChecked = false;
            }

*/

        var fn =  $('.register_form input[name=full_name]').val();
        var em =  $('.register_form input[name=email]').val();
        var p1 =  $('.register_form input[name=password]').val();
        var p2 =  $('.register_form input[name=password_again]').val();

        if (!isChecked){
            field += ' You bot? ';
        }

        if(fn.length < 3){
            isChecked = false;
            field += ' Full Name.';
        }

        var regexp_email = /\S+@\S+\.\S+/;
        if(!regexp_email.test(em)){
            isChecked = false;
            field += ' Email.';
        }

        if(p1 != p2 || p2 == ""){
            isChecked = false;
            field += ' Passwords.';
        }

        if (isChecked){
            $.ajax({
                type:'post',
                data: $('.register_form').serialize(),
                url:'/register',
                success:function(data){
                    if(data == 'success'){
                        console.log('redirect');
                    }else
                        $('.reg_error_text').html('Error, this email is registered!');
                }
            });
        }else{
            $('.reg_error_text').html('Error, fill the field: '+field);
        }

    });
</script>


