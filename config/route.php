<?php
/*
*   В массиве указываем роутеры. Указываем url, название контроллера и функцию через разделитель(разделитель взят из laravel)
*/
$route = [
    "/index" => "Controller@index",
    '/login' => "Controller@login",
    '/register' => "Controller@register",
    '/' => "Controller@index",
    '/approved' => "Controller@approved",
    '/recovery' => "Controller@recovery",
    '/home' => "HomeController@home",
    '/settings' => "HomeController@home",
    '/nitification' => "HomeController@nitification", // роутер для оповещения яндекс денег
    '/home/password/change' => "HomeController@change",
    '/home/image/change' => "HomeController@imageChange"
];
?>