<?php
$LoggedInMiddleware = [
    "handler" => function () {
        return \App\Services\Auth::user();
    },
    "error_message" => "User Must Be Logged In",
    "redirect_to" => "login",
    "key" => "login"
];