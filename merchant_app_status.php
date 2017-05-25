<?php

include_once('BluePayOnlineApp.php');
$bluepay = new BluePayOnlineAppApi;
$bluepay->setSecretKey('PUT_SECRET_KEY_HERE')
    ->setApiAccountId('PUT_ACCOUNT_ID_HERE')
    ->setUrl('https://onlineapp.bluepay.com/interfaces/api/onlineapp/')
    ->setOperation('status')
    ->setUserAgent('Put Your Company Name Here');

// App ID (submission_id) = 75000000
$response = $bluepay->getStatus(75000000);

$returned_body_as_php_array = json_decode($response['body'], true);
var_dump($returned_body_as_php_array);

exit(PHP_EOL);


