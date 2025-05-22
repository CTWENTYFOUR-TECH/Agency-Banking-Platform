<?php
// $raw = '{ \"data\":{\"customerNumber\":\"R023688778\",\"accountDetails\":{\"accountDetails\":null,\"comments\":[\"Customer exists - R023688778 with existing SASAV account - 04883773883\"]}},\"responseCode\":\"10\",\"responseDescription\":\"Partial Success\",\"AccountNumber\":null,\"transactionRef\":null}';

// // Step 1: Remove slashes (if any)
// $clean = stripslashes($raw);

// // Step 2: Decode JSON
// $data = json_decode($clean, true);

// echo json_encode($data);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://online.yesmfbank.com/afrigo/Card.svc/api/cardblock',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 30,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
	"br_no":"0000",
	"cnl_cd":"TLL",
	"crd_no":"564030510000004009",
	"rmk":"Card Block",
	"total_cost":"0"
}',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

var_dump ($response); die();

