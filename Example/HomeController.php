<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smsa;

class HomeController extends Controller
{
  public function smsa_shipping(){
    $parameters = [
      'refNo'         => '22222',
      'idNo'          => 'id',
      'cName'         => 'name',
      'cntry'         => 'KSA',
      'cCity'         => 'Riyadh',
      'cMobile'       => '033333333',
      'cAddr1'        => 'test',
      'cAddr2'        => '',
      'PCs'           => '1',
      'cEmail'        => 'test@test.com',
      'weight'        => '1',
      'cZip'          => '',
      'cPOBox'        => '',
      'cTel1'         => '',
      'cTel2'         => '',
      'carrValue'     => '',
      'carrCurr'      => '',
      'codAmt'        => '',
      'custVal'       => '',
      'custCurr'      => '',
      'insrAmt'       => '',
      'insrCurr'      => '',
      'itemDesc'      => '',
      'prefDelvDate'  => '',
      'gpsPoints'     => ''
    ];

    $test = Smsa::Shipping($parameters);

    dd($test);
  }

  public function print_label(){
    $get_pdf = Smsa::PrintAWB('290012998106');
    dd($get_pdf);
  }

  public function tracking(){
    $tracking = Smsa::Tracking('290012998106');
    dd($tracking);
  }

  public function cancel_shipping(){
    $cancel = smsa::Cancel('290012998106', 'test');
  }
}
