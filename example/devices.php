<?php
require_once __DIR__ . '/../vendor/autoload.php';

$token = 'your_token';
$client = new \QClient\Client($token);

echo "All Device Definitions" . PHP_EOL;

foreach ($client->device_definitions as $device) {
    //for now simple array access
    //in the future will add richer object access
    echo $device['name'] . PHP_EOL;
}

echo PHP_EOL . "Registered Devices" . PHP_EOL;

foreach ($client->devices as $device) {
    //for now simple array access
    //in the future will add richer object access
    echo $device['pid'] . PHP_EOL;
}
