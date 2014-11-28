<?php

use rec\View;
/**
 *
 */
View::addScript('interface','js/interface.js');
View::addJavascript("
    $(function(){
        $('h2').trigger('click');
    });
");

?>
<h2>formLogin.php</h2>
<p class="data"></p>
<?php

View::addJavascript("
    $('h2').click(function(){
        $.ajax({
            url:'/login',
            success: function(data){
                $('.loading').remove();
                $('.data').html(data);
            },
            beforeSend: function(){
                $('h2').append('<span class=\"loading\">Loading...</span>')
            },
            error: function(data){
                console.log(data.status);
            }
        });
    });

");


?>