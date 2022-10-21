<?php
ob_start();

class Getcategories
{
    public function getName(){
        $mysql = new mysqli('localhost','root','root','company');
        $text = $mysql->query("SELECT `id`, `name`, `parsed_pages` FROM `categories` WHERE `parsed_pages` < 30 ORDER BY `id` ASC LIMIT 1");
        $text = $text->fetch_assoc();
        return $text;
    }
}