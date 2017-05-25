<?php

include_once('BluePayOnlineApp.php');

$bluepay = new BluePayOnlineAppApi;
$bluepay->setSecretKey('PUT_SECRET_KEY_HERE')
    ->setApiAccountId('PUT_ACCOUNT_ID_HERE')
    ->setUrl('https://onlineapp.bluepay.com/interfaces/api/onlineapp/')
    ->setOperation('submit')
    ->setProcessingAgreementType()
    ->setUserAgent('Put Your Company Name Here');

$params = [

    'bn_dba' => 'BLUEPAY TEST0411',
    'bn_location_contact_fname' => 'New',
    'bn_location_contact_lname' => 'Merchant',
    'bn_location_address' => '184 Shuman Boulevard',
    'sfs_accept_only' => 'yes',
    'bn_location_city' => 'Naperville',
    'bn_location_state' => 'IL',
    'bn_location_zip' => '60563',
    'bn_location_phone_number_1' => '866-739-8324',
    'mp_type_of_business' => 'ECOM',
    'mp_average_ticket' => '100',
    'mp_expected_card_sales_mo' => '2000',
    'mp_type_of_goods_sold' => 'Widgets',
    'si_transit_aba' => '12345678',
    'si_deposit_account_number' => '987987987',
    'mp_email' => 'test@bluepay.com',
    'oi_owner_1_ssn_1' => '322-54-6789',
    'oi_owner_1_fname' => 'test',
    'oi_owner_1_lname' => 'tester',
    'sfs_non_pci_compliance_fee' => '15.00',
    'si_transit_aba2' => ' ',
    'si_deposit_account_number2' => ' ',
    'ci_tax_filing_name' => 'test',
    'ci_fed_tax_id' => 'reset',
    'ci_ownership_type' => '',
    'agreebox_pci' => 'yes',
    'remote_address' => 'gdfgdf',
    'agreebox_merchant_app' => 'yes',
    'agreebox_program_guide' => 'yes',
    'agreebox_electronic_signature' => 'yes',
    'taps_software_equip_1_type' => 'BluePay Mobile',
    'create_gateway' => '0',
    'sfs_accept_only_question' => 'no',
    'sfs_pricing_type' => ' ',
    'sfs_tiered_debit_qual_rate' => '1.19',
    'sfs_tiered_credit_qual_rate' => '1.19',
    'sfs_tiered_credit_mid_qual_rate' => '1.19',
    'sfs_tiered_credit_non_qual_rate' => '1.19',
    'sfs_tiered_debit_mid_qual_rate' => '1.19',
    'sfs_tiered_debit_non_qual_rate' => '1.19',
    'processing_agreement_type' => '',
    'mp_max_ticket' => '2000',
    'app_version' => '',

];

$bluepay->setParams($params);
$response = $bluepay->send();


$returned_body_as_php_array = json_decode($response['body'], true);
$returned_headers_php_array = $response['headers'];

var_dump($returned_body_as_php_array);

exit(PHP_EOL);



