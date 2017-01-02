PHP Client for Das Keyboard Q
==============================

Currently work in progress. See [example](example) for usage.
 
Installation
------------

    composer require tjlytle/qclient
     
Client Setup
------------

    $token = 'oauth 2 token';
    $client = new \QClient\Client($token);

API Support
-----------

Currently the client provides access to collections by providing iterable objects. The items are simple containers for 
the API response providing array access to the data:

    foreach($client->device_descriptions as $description){
        echo $description['name'];
    }
    
    foreach($client->devices as $device):
    foreach($client->signals as $signal):
    
To create a signal, use the collection and the method, as well as a `Signal` object:

    $signal = new Signal();
    $signal->setName('Sent from PHP Client');
    $signal->setPid('DK5QPID');
    $signal->setZone('KEY_A');
    $signal->setColor('#008000');
    
    $client->signals->post($signal);
    
To update, use the patch method:

    $client->signals->patch($signal);