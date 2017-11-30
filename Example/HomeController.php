<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smsa;

class HomeController extends Controller
{
  public function smsa_shipping(){
    $parameters = [
      'refNo'         => '122222',
      'idNo'          => '1',
      'cName'         => 'customer name',
      'cntry'         => 'KSA',
      'cCity'         => 'Riyadh',
      'cMobile'       => '050505050505',
      'cAddr1'        => 'testing',
      'PCs'           => '1',
      'cEmail'        => 'test@test.com',
      'weight'        => '1',
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
