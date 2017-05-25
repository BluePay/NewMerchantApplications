<?php

include_once('BluePayOnlineApp.php');
$secretkey = 'SharedOnlineAppApiAccessKey';

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$bluepay = new BluePayOnlineAppWebhook();
$bluepay->setSecretKey($secretkey)
        ->setAccountNo($input['account_no'])
        ->setSubmissionId($input['submission_id'])
        ->setTps($input['tps'])
        ->setField($input['field'])
        ->setValue($input['value']);

// Check to see if authenticated via Tps.
if (true !== $bluepay->isTps()) {
    // Did not authenticate from the Tps.
    $response = 'HTTP/1.1 403 Forbidden';
    // ...
}

// Grant access
/* 
Persist values ...

$input['account_no']
$input['submission_id']
$input['tps']        
$input['field'] 
$input['value']
        
Return $response = 'HTTP/1.1 200 OK';       
 
*/       
        
        
