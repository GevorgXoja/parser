<?php
//ob_start();
//  sleep(3);
use Facebook\WebDriver\Remote\RemoteWebDriver;
use \Facebook\WebDriver\WebDriverBy;
class Search
{
    public $domen;
    public $tagName;
    public $attrName;
    public $attrValue;
    public $searchText;
    public $pageNum;


    public function __construct($domen,$tagName,$attrName,$attrValue,$searchText,$pageNum){
        $this->domen = $domen;
        $this->tagName = $tagName;
        $this->attrName = $attrName;
        $this->attrValue = $attrValue;
        $this->searchText = $searchText;
        $this->pageNum = $pageNum;
    }


    public  function getHtml2()
    {


        $serverUrl = 'http://localhost:9515';

        $driver = RemoteWebDriver::create($serverUrl, \Facebook\WebDriver\Remote\DesiredCapabilities::chrome());
        $driver->wait(5);
        $driver->get('https://yandex.ru/');



// Find search element by its id, write 'PHP' inside and submit
        $driver->findElement(WebDriverBy::className('input__control')) // find search input element
        ->clear()
            ->sendKeys($this->searchText) // fill the search box
            ->submit(); // submit the whole form
        $driver->wait(5);
        for($i = 1; $i<5; $i++){

//            $chekShowCaptcha = strripos($driver->getCurrentURL(), 'yandex.ru/showcaptcha');
//            var_dump($chekShowCaptcha);
//            if($chekShowCaptcha > 0){
//                // Find element of 'History' item in menu by its css selector
//                $historyButton = $driver->findElement(
//                    WebDriverBy::className('CheckboxCaptcha-Button')
//                );
//
//
//// Read text of the element and print it to output
//                echo 'About to click to a button with text: ' . $historyButton->getText();
//                sleep(10);
//// Click the element to navigate to revision history page
//                $historyButton->click();
//            }
            if($i == 1){
                $html = $driver->getPageSource();
                $this->parseData($html);
                $driver->wait(5);
            }
            else{
                $this->test($driver,$i);
            }


            sleep(10);
        }

// Find element of 'History' item in menu by its css selector

// Read text of the element and print it to output
//echo 'About to click to a button with text: ' . $historyButton->getText();

// Click the element to navigate to revision history page
//$historyButton->click();

// Make sure to always call quit() at the end to terminate the browser session
        $driver->quit();



    }

    public function test($driver,$i){
        $historyButton = $driver->findElements(
            WebDriverBy::className('pager__item_kind_page')
        );

        foreach($historyButton as $li){
            if($li->getText() == $i){
                $li->click();
            }
            sleep(10);
        }

        $html = $driver->getPageSource();
        $this->parseData($html);
    }

    public function parseData($html){
        $dom = new DOMDocument;
        @$dom->loadHTML($html);



        $domxpath = new DOMXPath($dom);
        $newDom = new DOMDocument;
        $newDom->formatOutput = true;

        $filtered = $domxpath->query("//$this->tagName" . '[@' . $this->attrName . "='$this->attrValue']");
        $mysql = new mysqli('localhost','root','root','company');
        if($filtered->length == 0)
            exit();
        foreach ($filtered as $n) {
            $firstLine = $domxpath->query(".//span" . '[@' . "class" . "='OrganicTitleContentSpan organic__title']", $n)->item(0);
            $secondLine = $domxpath->query(".//div" . '[@' . "class" . "='Path Organic-Path path organic__path']", $n)->item(0);
            $secondLine = $domxpath->query(".//b", $secondLine)->item(0);
            $mysql->query ("INSERT INTO  `company_info` (domen,name) VALUES ('$secondLine->textContent', '$firstLine->textContent')");
            var_dump($firstLine->textContent);
            var_dump($secondLine->textContent);
            echo "<br>";

        }
    }



    public  function getHtml()
    {


        $serverUrl = 'http://localhost:9515';

        $driver = RemoteWebDriver::create($serverUrl, \Facebook\WebDriver\Remote\DesiredCapabilities::chrome());

        $driver->get('https://yandex.ru/search/?lr=213&text=%D0%BC%D0%B5%D1%82%D1%80%D0%B8%D0%BA%D0%B0&p=');
//        $driver->get('https://yandex.ru/');
        $chekShowCaptcha = strripos($driver->getCurrentURL(), 'yandex.ru/showcaptcha');
        var_dump($chekShowCaptcha);
        if($chekShowCaptcha > 0){
            // Find element of 'History' item in menu by its css selector
            $historyButton = $driver->findElement(
                WebDriverBy::className('CheckboxCaptcha-Button')
            );


// Read text of the element and print it to output
            echo 'About to click to a button with text: ' . $historyButton->getText();
            sleep(10);
// Click the element to navigate to revision history page
            $historyButton->click();
        }
    sleep(5);
// Find search element by its id, write 'PHP' inside and submit
        $driver->findElement(WebDriverBy::id('uniq16487568328601')) // find search input element
        ->clear()
            ->sendKeys($this->searchText) // fill the search box
            ->submit(); // submit the whole form
//        if($this->pageNum > 0){
//            // Find element of 'History' item in menu by its css selector
//            $historyButton = $driver->findElements(
//                WebDriverBy::className('pager__item_kind_page')
//            );
//
//            var_dump($historyButton);
//
//            foreach($historyButton as $li){
//                if($li->getText() == $this->pageNum+1){
//                    $li->click();
//                }
//            }
//        }


        $html = $driver->getPageSource();
        var_dump($html);
// Read text of the element and print it to output
//echo 'About to click to a button with text: ' . $historyButton->getText();

// Click the element to navigate to revision history page
//$historyButton->click();

// Make sure to always call quit() at the end to terminate the browser session
        $driver->quit();

        $dom = new DOMDocument;
        @$dom->loadHTML($html);



        $domxpath = new DOMXPath($dom);
        $newDom = new DOMDocument;
        $newDom->formatOutput = true;

        $filtered = $domxpath->query("//$this->tagName" . '[@' . $this->attrName . "='$this->attrValue']");
        $mysql = new mysqli('localhost','root','root','company');
        if($filtered->length == 0)
            exit();
        foreach ($filtered as $n) {
            $firstLine = $domxpath->query(".//span" . '[@' . "class" . "='OrganicTitleContentSpan organic__title']", $n)->item(0);
            $secondLine = $domxpath->query(".//div" . '[@' . "class" . "='Path Organic-Path path organic__path']", $n)->item(0);
            $secondLine = $domxpath->query(".//b", $secondLine)->item(0);
            $mysql->query ("INSERT INTO  `company_info` (domen,name) VALUES ('$secondLine->textContent', '$firstLine->textContent')");
            var_dump($firstLine->textContent);
            var_dump($secondLine->textContent);
            echo "<br>";

        }

    }




//    public  function getHtml()
//    {
//
//        $ch = curl_init($this->domen);
//
//        $header = [
//            'useragent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36',
//            'X-Real-Ip' => '185.72.224.203',
//            "Accept" => "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
//            "Sec-Fetch-Site" => "same-origin",
//            "Sec-Fetch-Mode" => "navigate",
//            "Sec-Fetch-User" => "?1",
//            "Sec-Fetch-Dest" => "document",
//        ];
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HEADER, $header);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//        curl_setopt($ch, CURLOPT_COOKIE, 'yandexuid=4704093801579102138;yuidss=4704093801579102138;spravka=dD0xNjU0MTk0NjQ4O2k9MTg1LjcyLjIyNC4yMDM7RD0wODM1MjJCQjlDNTYxMzZGM0U2NjhDQjNEQ0NBNkUyNDRFMTRCQTJEOEJDQkMxOUQxM0E0N0MwNjM1MUNBRDQxOTNFQzcxMDI7dT0xNjU0MTk0NjQ4Njg5MTI4NDgwO2g9ZmZkMjU4Y2NiMGQ0MGEzMWEwMzU0NGUwNjNhOWM5NTg=;');
//
//        // Connection to Proxy srever
//
////        curl_setopt($ch, CURLOPT_PROXY, '87.103.175.250');
////        curl_setopt($ch, CURLOPT_PROXYPORT, '9812');
////        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);
//
//        $html = curl_exec($ch);
//        $info = curl_getinfo($ch);
//
//        $error = curl_error($ch);
//var_dump($info);
//var_dump($error);
//var_dump($html);
////        $html = htmlspecialchars($html);
//
//        curl_close($ch);
//
//
//        $dom = new DOMDocument;
//        @$dom->loadHTML($html);
//
//
//
//        $domxpath = new DOMXPath($dom);
//        $newDom = new DOMDocument;
//        $newDom->formatOutput = true;
//
//        $filtered = $domxpath->query("//$this->tagName" . '[@' . $this->attrName . "='$this->attrValue']");
//        $mysql = new mysqli('localhost','root','root','company');
//        if($filtered->length == 0)
//            exit();
//        foreach ($filtered as $n) {
//            $firstLine = $domxpath->query(".//span" . '[@' . "class" . "='OrganicTitleContentSpan organic__title']", $n)->item(0);
//            $secondLine = $domxpath->query(".//div" . '[@' . "class" . "='Path Organic-Path path organic__path']", $n)->item(0);
//            $secondLine = $domxpath->query(".//b", $secondLine)->item(0);
//            $mysql->query ("INSERT INTO  `company_info` (domen,name) VALUES ('$secondLine->textContent', '$firstLine->textContent')");
//            var_dump($firstLine->textContent);
//            var_dump($secondLine->textContent);
//            echo "<br>";
//
//        }
//
//    }
}