<?php
$AdminMiddleware = [
    "handler" => function () {
        return \App\Services\Auth::role() == \App\Enums\Role::Admin;
    },
    "error_message" => "User Must Be Admin",
    "redirect_to" => "/home",
    "key" => "admin",
];