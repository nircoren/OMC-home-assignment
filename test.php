<?php
// Load the HTML file
$htmlDoc = new DOMDocument;

$html = 'index.php';


$htmlDoc->loadHTML($html);
$htmlElement = $htmlDoc->getElementsByTagName("table");

foreach ($htmlElement->item(0)->childNodes as $element) {
    echo 'Element name: ' . $element->nodeName . PHP_EOL;
    echo 'Element value: '. $element->nodeValue . PHP_EOL;
}
