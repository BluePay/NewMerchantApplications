<?php



class BluePayOnlineAppWebhook
{
    private $secret_key;
    private $account_no;
    private $submission_id;
    private $tps;
    private $datetime;
    private $field;
    private $value;

    public $debug;

    public function setSecretKey($secret_key = null)
    {
        $this->secret_key = $secret_key;

        return $this;
    }

    public function setAccountNo($account_no = null)
    {
        $this->account_no = $account_no;

        return $this;
    }

    public function setSubmissionId($submission_id = null)
    {
        $this->submission_id = $submission_id;

        return $this;
    }

    public function setTps($tps = null)
    {
        $this->tps = $tps;

        return $this;
    }

    public function setDatetime($datetime = null)
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function setField($field = null)
    {
        $this->field = $field;

        return $this;
    }

    public function setValue($value = null)
    {
        $this->value = $value;

        return $this;
    }

    public function isTps()
    {
        if (empty($this->secret_key) ||
            empty($this->account_no) ||
            empty($this->tps)
        ) {
            $this->debug = 'ERROR: MISSING VALUES';
            return false;
        }

        $check = hash('sha512', $this->secret_key.$this->account_no.$this->field.$this->value);

        if ($this->tps == $check) {
            return true;
        }
        $this->debug = 'ERROR: TPS IS INCORRECT';
        return false;
    }



}


class BluePayOnlineAppApi
{
    private $api_account_id;
    private $secret_key;
    private $url;
    private $operation;
    private $user_agent;
    private $processing_agreement_type;
    private $params = [];

    public function setApiAccountId($api_account_id = null)
    {
        if (empty($this->api_account_id = $api_account_id)) {
//            exit('API_ACCOUNT_ID NOT SET');
        }
        return $this;
    }

    public function setSecretKey($secret_key = null)
    {
        if (empty($this->secret_key = $secret_key)) {
//            exit('SECRET_KEY NOT SET');
        }
        return $this;
    }

    public function setUrl($url = null)
    {
        if (empty($this->url = $url)) {
//            exit('URL NOT SET');
        }
        return $this;
    }

    public function setOperation($operation = null)
    {
        if (empty($this->operation = $operation)) {
//            exit('OPERATION NOT SET');
        }
        return $this;
    }

    public function setUserAgent($user_agent = 'BluePay Partner General')
    {
        if (empty($this->user_agent = $user_agent)) {
//            exit('USER_AGENT NOT SET');
        }
        return $this;
    }

    public function setProcessingAgreementType($processing_agreement_type = null)
    {
        $this->processing_agreement_type = $processing_agreement_type;
        return $this;
    }

    public function setParams($params = null)
    {
        $this->params = $params;
    }

    public function generateTps()
    {
        switch($this->operation) {
            case "submit":
                return sha1($this->secret_key.
                    $this->params['bn_dba'].
                    $this->params['bn_location_phone_number_1'].
                    $this->params['si_transit_aba'].
                    $this->params['si_deposit_account_number']
                );
                break;
        }

        return true;
    }





    public function send()
    {
        if (empty($this->api_account_id) ||
            empty($this->operation) ||
            empty($this->url) ||
            empty($this->secret_key) ||
            empty($this->user_agent) ||
            empty($this->params)
        ) {
            return 'ERROR: MISSING VALUES';
        }

        $this->params['tamper_proof_seal'] = $this->generateTps();
        $this->params['processing_agreement_type'] = $this->processing_agreement_type;
        $this->params['api_account_id'] = $this->api_account_id;
        $endpoint = $this->url.$this->operation.'/';
        $data = json_encode($this->params);

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint); // Set the URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // Verification of the SSL certificate.
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Content-Length: ' . strlen($data))
            );

            $response = ['body' => curl_exec($ch), 'headers' => curl_getinfo($ch)];
            curl_close($ch);

        }catch (\Exception $e){
            $response = ['message' => 'Unable To Enable Payments. Please Contact Support.'];
        }

        return $response;
    }


    public function getStatus($appID = null)
    {
        if (empty($this->api_account_id) ||
            empty($this->operation) ||
            empty($this->url) ||
            empty($this->secret_key) ||
            empty($appID)
        ) {
            return 'ERROR: MISSING VALUES';
        }
        //status tamper_proof_seal = sha1( <SECRET_KEY> + submission_id + <API_ACCOUNT_ID> )
        $this->params['tamper_proof_seal'] = sha1($this->secret_key.$appID.$this->api_account_id);
        $this->params['api_account_id'] = $this->api_account_id;
        $this->params['submission_id'] = $appID;
        $endpoint = $this->url.$this->operation.'/';
        $data = json_encode($this->params);
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint); // Set the URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // Verification of the SSL certificate.
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Content-Length: ' . strlen($data))
            );
            $response = ['body' => curl_exec($ch), 'headers' => curl_getinfo($ch)];
            curl_close($ch);
        }catch (\Exception $e){
            $response = ['message' => 'Unable To Enable Payments. Please Contact Support.'];
        }
        return $response;
    }
}


class BluePayOnlineAppPrefillApi
{
    private $api_account_id;
    private $secret_key;
    private $url;
    private $operation;
    private $params = [];
    private $response;
    private $status = 'WORKING_AS_EXPECTED';

    public function setSecretKey($secret_key)
    {
        $this->secret_key = $secret_key;
        return $this;
    }

    public function setApiAccountId($api_account_id)
    {
        $this->api_account_id = $api_account_id;
        return $this;
    }

    public function setUrl($url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function setOperation($operation = null)
    {
        $this->operation = $operation;
        return $this;
    }

    public function setParams($params = null)
    {
        $this->params = $params;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function generateTps($operation = null)
    {
        switch($operation) {
            case 'create':
                $tps = hash('sha512', $this->secret_key.$this->api_account_id);
                break;
            default:
                $tps = null;
                break;
        }

        return $tps;
    }

    public function create()
    {
        if (empty($this->api_account_id) ||
            empty($this->url) ||
            empty($this->secret_key)
        ) {
            $this->status = 'ERROR: MISSING VALUES';
            return false;
        }
        $tps = $this->generateTps('create');

        $endpoint = $this->url.'?accountid='.$this->api_account_id.'&tps='.$tps;

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint); // Set the URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // Verification of the SSL certificate.
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=UTF-8'
            ));

            $response = ['body' => curl_exec($ch), 'headers' => curl_getinfo($ch)];
            curl_close($ch);

        }catch (\Exception $e){
            $this->status = 'CONNECTION ERROR CHECK CURL CONNECTION';
            return false;
        }

        if (!isset($response['headers']['http_code'])) {
            $this->status = 'BLUEPAY SERVER MAY HAVE TIMED OUT';
            return false;
        }

        if ($response['headers']['http_code'] == 201) {
            $response_php = json_decode($response['body'], true);
            return $response_php['prefill_id'];
        }

        $this->status = 'BLUEPAY SERVER NOT RESPONDING WITH 201';
        return false;
    }


    public function update($prefillid = null)
    {
        if (empty($this->params)) {
            $this->status = 'PARAMS MUST BE SET IN ORDER TO UPDATE RECORD';
            return false;
        }
        $data = json_encode($this->params);

        $tps = $this->generateTps('create');

        $endpoint = $this->url.'/'.$prefillid.'?accountid='.$this->api_account_id.'&tps='.$tps;

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint); // Set the URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // Verification of the SSL certificate.
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Content-Length: ' . strlen($data))
            );

            $response = ['body' => curl_exec($ch), 'headers' => curl_getinfo($ch)];
            curl_close($ch);

        }catch (\Exception $e){
            $this->status = 'CONNECTION ERROR CHECK CURL CONNECTION';
            return false;
        }

        if (!isset($response['headers']['http_code'])) {
            $this->status = 'BLUEPAY SERVER MAY HAVE TIMED OUT';
            return false;
        }

        if ($response['headers']['http_code'] == 200) {
            $response_php = json_decode($response['body'], true);

            if ($response_php['status'] == 'success.data_has_been_modified') {
                $this->status = $response['body'];
                return true;
            }

        } elseif($response['headers']['http_code'] == 400) {
            $response_php = json_decode($response['body'], true);
            $this->status = $response['body'];
            return false;
        }

        $this->status = 'BLUEPAY SERVER NOT RESPONDING WITH '.$response['headers']['http_code'];
        return false;
    }

    public function replace($prefillid = null)
    {
        if (empty($this->params)) {
            $this->status = 'PARAMS MUST BE SET IN ORDER TO REPLACE RECORD';
            return false;
        }
        $data = json_encode($this->params);

        $tps = $this->generateTps('create');

        $endpoint = $this->url.'/'.$prefillid.'?accountid='.$this->api_account_id.'&tps='.$tps;

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint); // Set the URL
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // Verification of the SSL certificate.
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json; charset=UTF-8',
                    'Content-Length: ' . strlen($data))
            );

            $response = ['body' => curl_exec($ch), 'headers' => curl_getinfo($ch)];
            curl_close($ch);

        }catch (\Exception $e){
            $this->status = 'CONNECTION ERROR CHECK CURL CONNECTION';
            return false;
        }

        if (!isset($response['headers']['http_code'])) {
            $this->status = 'BLUEPAY SERVER MAY HAVE TIMED OUT';
            return false;
        }

        if ($response['headers']['http_code'] == 200) {
            $response_php = json_decode($response['body'], true);

            if ($response_php['status'] == 'success.data_has_been_replaced') {
                $this->status = $response['body'];
                return true;
            }

        } elseif($response['headers']['http_code'] == 400) {
            $response_php = json_decode($response['body'], true);
            $this->status = $response['body'];
            return false;
        }

        $this->status = 'BLUEPAY SERVER NOT RESPONDING WITH '.$response['headers']['http_code'];
        return false;
    }

    public function view($prefillid = null)
    {
        $tps = $this->generateTps('create');

        $endpoint = $this->url.'/'.$prefillid.'?accountid='.$this->api_account_id.'&tps='.$tps;

        try{
            $options = array('http' => array('ignore_errors' => TRUE));
            $context  = stream_context_create($options);
            $response['body'] = file_get_contents($endpoint, false, $context);
            $response['headers'] = $http_response_header;

        }catch (\Exception $e){
            $this->status = 'CONNECTION ERROR CHECK GET CONNECTION';
            return null;
        }

        if (!isset($response['headers'][0])) {
            $this->status = 'BLUEPAY SERVER MAY HAVE TIMED OUT';
            return null;
        }

        if ($response['headers'][0] == 'HTTP/1.0 200 OK') {

            return $response['body'];

        } else {
            $this->status = 'BLUEPAY SERVER NOT RESPONDING WITH 200';
            return null;
        }


    }


}


