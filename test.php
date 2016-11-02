<?php

$curl = curl_init();

 $curl_header = 'EASYTRANSAC-API-KEY:YWE0NTQyYjk4ODUzYWFiY2E0MzI3OWY4YTkxNTdhMjA4Yjk4NTUyODc5ODdhNTBiMjQ1NjlmZjc4MzI0ZTJkZA==';
 
 $be_coupons = 'EASYTRANSAC-API-KEY:OWUwNjdiZWFkYzhkNjI1NjFhNmU4NDY3YTkxY2E5ODk4NDg2OGMxYWQ4ZmY5ZmEyYWE4ZTNmMjc4YzE5Yjc4Mg==';
  
  curl_setopt($curl, CURLOPT_HTTPHEADER, array($be_coupons));
  curl_setopt($curl, CURLOPT_POST, TRUE);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $cur_url = 'https://www.easytransac.com/api/payment/page';
  curl_setopt($curl, CURLOPT_URL, $cur_url);
  defined('CURL_SSLVERSION_TLSv1')   || define('CURL_SSLVERSION_TLSv1', 1);
//  curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
  
  curl_setopt($curl, CURLOPT_SSLVERSION, 6);
  $data=array();
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  $et_return = curl_exec($curl);
  curl_close($curl);
  $et_return = json_decode($et_return, TRUE);
  var_dump($et_return);
  
  
  