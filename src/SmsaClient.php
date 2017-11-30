<?php

namespace Smsa;

class SmsaClient {

  private $_apiUrl = "http://track.smsaexpress.com/SECOM/SMSAwebService.asmx";
  private $_passkey;
  private $_sender_name;
  private $_contact_person;
  private $_POBox;
  private $_address;
  private $_sender_country;
  private $_sender_city;
  private $_sender_phone;
  private $_shipping_type;

  public function __construct(){
    if(config('smsa.testing_environment'))
      $this->_passkey = config('smsa.passKey_testing');
    else
      $this->_passkey = config('smsa.passKey_production');

    $this->_sender_name     = config('smsa.sender_name');
    $this->_contact_person  = config('smsa.contact_person');
    $this->_POBox           = config('smsa.POBox');
    $this->_address         = config('smsa.address');
    $this->_sender_country  = config('smsa.sender_country');
    $this->_sender_city     = config('smsa.sender_city');
    $this->_sender_phone    = config('smsa.sender_phone');
    $this->_shipping_type   = config('smsa.shipping_type');
  }

  public function Shipping($parameters_array){
    $variables = array(
        'passKey'       => $this->_passkey,
        'sentDate'      => date('Y/m/d'),
        'shipType'      => $this->_shipping_type,
        'sName'         => $this->_sender_name,
        'sContact'      => $this->_contact_person,
        'sAddr1'        => $this->_address,
        'sAddr2'        => '',
        'sCity'         => $this->_sender_city,
        'sPhone'        => $this->_sender_phone,
        'sCntry'        => $this->_sender_country,
    );

    $xml = $this->createXml('addShip', array_merge($parameters_array, $variables));
    $result = $this->send($xml);

    $response = (array) $result->Body->addShipResponse->addShipResult;

    if(is_numeric($response[0])){
      return ['status' => true, 'value' => 'Shipped'];
    }else{
      return ['status' => false, 'value' => $response[0]];
    }
  }

  public function PrintAWB($awb_no){
    $variables = array(
        'passKey'       => $this->_passkey,
        'awbNo'         => $awb_no
    );

    $xml = $this->createXml('getPDF', $variables);
    $result = $this->send($xml);

    $response = (array) $result->Body->getPDFResponse;

    if(!empty($response) && isset($response['getPDFResult'])){
      file_put_contents( public_path('smsa_labels/'.$awb_no.'.pdf'), base64_decode($response['getPDFResult']));
      return ['status' => true, 'value' => 'smsa_labels/'.$awb_no.'.pdf'];
    }else{
      return ['status' => false, 'value' => ''];
    }
  }

  public function Tracking($awb_no){
    $variables = array(
        'passKey'       => $this->_passkey,
        'awbNo'         => $awb_no
    );

    $xml = $this->createXml('getTracking', $variables);
    $result = $this->send($xml);

    $response = (array) $result->Body;

    if(isset($response['getTrackingResponse'])){
      $array = (array) $result->Body->getTrackingResponse->getTrackingResult->diffgrdiffgram->NewDataSet->Tracking;
      return ['status' => false, 'value' => $array];
    }else{
      $msg = (array) $response['Fault']->faultstring;
      return ['status' => false, 'value' => $msg[0]];
    }
  }

  public function Cancel($awb_no, $reason){
    $variables = array(
        'awbNo'     => $awb_no,
        'passkey'   => $this->_passkey,
        'reas'      => $reason
    );

    $data = $this->createXml('cancelShipment', $variables);
    $result = $this->send($data);

    $response = (array) $result->Body->cancelShipmentResponse->cancelShipmentResult;
    return ['status' => false, 'value' => $response[0]];
  }

  private function createXml($method, $variables){
    $xmlcontent = '<?xml version="1.0" encoding="utf-8"?>
          <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
              <'.$method.' xmlns="http://track.smsaexpress.com/secom/">';
                if(count($variables)){
                  foreach($variables As $key=>$val){
                      $xmlcontent .= '<'.$key.'>'.$val.'</'.$key.'>';
                  }
                }
      $xmlcontent .= '</'.$method.'>
            </soap:Body>
          </soap:Envelope>';

      $headers = array(
          "POST /SECOM/SMSAwebService.asmx HTTP/1.1",
          "Host: track.smsaexpress.com",
          "Content-Type: text/xml; charset=utf-8",
          "Content-Length: ".strlen($xmlcontent),
          "SOAPAction: http://track.smsaexpress.com/secom/".$method
      );

      return array(
          'xml'       => $xmlcontent,
          'header'    => $headers
      );
  }

  public function send(array $data){
      $ch = curl_init();
      //curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_URL, $this->_apiUrl);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data['xml']);
      $content=curl_exec($ch);

      $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $content);
      $xml = simplexml_load_string($clean_xml);
      return $xml;
  }

}
