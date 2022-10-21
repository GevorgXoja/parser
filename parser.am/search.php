<?php
require_once('vendor/autoload.php');
require_once('classes/Search.php');
require_once('classes/Getcategories.php');

$mysql = new mysqli('localhost','root','root','company');
$catObj = new Getcategories;
$cat = $catObj->getName();

for($i=$cat['parsed_pages']; $i<30; $i++){
    $url = [
        'lr' => 213,
        'text' => $cat['name'],
        'p' => $i,
    ];
    $domen = http_build_query($url);
    var_dump($domen);
    $hg = new Search("https://yandex.ru/search/?".$domen,'li','class','serp-item serp-item_card',$cat['name'],$i);
    $hg->getHtml();

    $q = "UPDATE `categories` SET `parsed_pages` = $i WHERE `id` = ". $cat['id'];
    $mysql->query($q);
//   sleep(15);

}
