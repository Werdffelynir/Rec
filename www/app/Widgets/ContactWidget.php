<?php
/**
 * Created by PhpStorm.
 * User: Werdffelynir
 * Date: 06.11.2014
 * Time: 0:35
 */

namespace app\Widgets;

use rec\Widgets;

class ContactWidget extends Widgets
{
    public $subject;
    public $email;

    public function run() {
        return '
        <div class="contact_form">
            <h3>Contact Form</h3>
            <form action="">
                <div> <input name="name" type="text" value="'.$this->subject.'"/> Your Name</div>
                <div> <input name="email" type="text" value="'.$this->email.'"/> Your Email</div>
                <div> <textarea name="text" id="" cols="70" rows="4"></textarea></div>
                <div> <input type="submit" value="Send Massages"/></div>
            </form>
        </div>
        ';
    }

}