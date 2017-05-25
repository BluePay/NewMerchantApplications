<?php

/*
 * Set path to the  BluePayOnlineApp.php helper script.
 */
include_once('BluePayOnlineApp.php');


/*
 * Set these values before running script.
 * The short app hash value here applies to one specific short form,
 * however, you can use any valid short form hash here.
 *
 * Example: /interfaces/create_form/shortapp/<HASH GOES HERE>
 *
 */
const BASE_URL = 'https://onlineapp.bluepay.com/';
const YOUR_SHORT_APP = 'GET HASH FROM YOUR BLUEPAY INTEGRATION SUPPORT REPRESENTATIVE';
const USER_ID = 'ENTER API ACCOUNT ID HERE';
const USER_ACCESS_KEY = 'ENTER BLUEVIEW ACCESS KEY HERE';



$your_shortapp = BASE_URL.'interfaces/create_form/shortapp/'.YOUR_SHORT_APP;
$bluepay = new \BluePayOnlineAppPrefillApi;
$bluepay->setSecretKey(USER_ACCESS_KEY)
        ->setApiAccountId(USER_ID)
        ->setUrl(BASE_URL.'interfaces/create_form/api/prefill');
$prefill_id = $bluepay->create();

$params = [
    'bn_dba'=>'BluePay Widgets Inc.',
    'bn_location_contact_fname'=>'Billy',
    'bn_location_contact_lname'=>'Merchant',
    'bn_location_address'=>'184 Shuman Blvd',
    'sfs_accept_only'=>'yes',
    'bn_location_city'=>'Naperville',
    'bn_location_state'=>'IL',
    'bn_location_zip'=>'60502',
    'bn_location_phone_number_1'=>'800-214-6978',
    'mp_type_of_business'=>'Retail' ,
    'mp_average_ticket'=>'100' ,
    'mp_expected_card_sales_mo'=>'2000',
    'mp_type_of_goods_sold'=>'Widgets',
    'si_transit_aba'=>'111000025',
    'si_deposit_account_number'=>'987987987',
    'mp_email'=>'test@bluepay.com',
    'mp_website'=> 'https://www.bluepay.com',
    'oi_owner_1_ssn_1'=>'322-54-6789',
    'oi_owner_1_fname'=>'Billys',
    'oi_owner_1_lname'=>'Merchants',
    'oi_owner_1_title' => 'President',
    'sfs_non_pci_compliance_fee'=>'15.00',
    'ci_tax_filing_name'=>'Billy Q Merchant',
    'ci_fed_tax_id'=>'9999999',
    'mp_max_ticket' => '599',
    'ci_ownership_type'=>'LLC'
];
$response = $bluepay->setParams($params)->update($prefill_id);

/*
 * View operation.
 * $response = $bluepay->view($prefill_id);
 */


$foo = <<<EOF
<html>
<head>
</head>
<body>
<section id="content1" class="section">
    <div class="onlineappForm">
<iframe width="100%" height="3700px" frameborder="0" allowTransparency="true" src="
EOF;
$foo .= $your_shortapp.'?prefillid='.$prefill_id;
$foo .= <<<EOF
"></iframe>
    </div>
</section>
</body>
EOF;

echo $foo;
exit();
