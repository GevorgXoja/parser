<?php
require_once('vendor/autoload.php');

use Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy;

$serverUrl = 'http://localhost:9515';

$driver = RemoteWebDriver::create($serverUrl, \Facebook\WebDriver\Remote\DesiredCapabilities::chrome());

$driver->get('https://yandex.ru/');

// Find search element by its id, write 'PHP' inside and submit
$driver->findElement(WebDriverBy::id('uniq16487568328601')) // find search input element
    ->clear()
->sendKeys('PHP') // fill the search box
->submit(); // submit the whole form

$chekShowCaptcha = strripos($driver->getCurrentURL(), 'yandex.ru/showcaptcha');
if($chekShowCaptcha > 0){
    // Find element of 'History' item in menu by its css selector
    $historyButton = $driver->findElement(
        WebDriverBy::cssSelector('#CheckboxCaptcha-Button')
    );

// Read text of the element and print it to output
    echo 'About to click to a button with text: ' . $historyButton->getText();

// Click the element to navigate to revision history page
    $historyButton->click();
}

var_dump($driver->getPageSource());
// Make sure to always call quit() at the end to terminate the browser session
$driver->quit();