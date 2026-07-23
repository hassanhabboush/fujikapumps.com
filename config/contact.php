<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact form recipient
    |--------------------------------------------------------------------------
    |
    | Where submissions from the public contact form are delivered. Read through
    | config so it keeps working under `php artisan config:cache`, where env()
    | calls made at request time return null.
    |
    */

    'receiver' => env('RECIEVER_EMAIL', 'sales@fujikapumps.com'),

    'receiver_name' => env('RECIEVER_NAME', 'Fujika Contact Form'),

];
