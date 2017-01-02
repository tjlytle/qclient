<?php
require_once __DIR__ . '/../vendor/autoload.php';

$token = 'your_token';
$client = new \QClient\Client($token);

echo "All Signals" . PHP_EOL;

foreach ($client->signals as $signal) {
    //for now simple array access
    //in the future will add richer object access
    echo $signal['name'] . PHP_EOL;
}

echo PHP_EOL . "Sending Test Signal" . PHP_EOL;


$signal = new \QClient\Resource\Signal();

$signal->setName('Signal from PHP Client')
       ->setPid('DK5QPID')
       ->setZone('KEY_P')
       ->setColor('#000080');

$client->signals->post($signal);

echo PHP_EOL . "Marking as Read / Archived" . PHP_EOL;

$signal->setRead(true)
       ->setArchived(true);

$client->signals->patch($signal);
