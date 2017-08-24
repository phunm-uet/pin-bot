<?php
require('vendor/autoload.php'); 
use seregazhuk\PinterestBot\Factories\PinterestBot;
$bot = PinterestBot::create();
$result = $bot->auth->login('teewonder_us', 'phuapple28121995');

if (!$result) {
    echo $bot->getLastError();
    die();
}
// $boards = $bot->boards->forUser('teewonder_us');
$pins = $bot->pins->search('dogs quote',1)->toArray();
print_r($pins);
?>