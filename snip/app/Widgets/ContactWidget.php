<?php

namespace app\Widgets;

use rec\Widgets;

class ContactWidget extends Widgets
{
    public $subject;
    public $email;

    public function run()
    {
        return $this->renderPartial('contact_form',
            [
                'subject'=>$this->subject,
                'email'=>$this->email
            ]
        );
    }

}