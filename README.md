# Laravel SMSA Integration
- * This package is not a official package from SMSA just my develop *
- it's a laravel package for [SMSA](http://smsaexpress.com) Integration

# 1- Installation
1. Require the package using composer:

    ```
    composer require ahyadessam/smsa
    ```

2. Add the service provider to the `providers` in `config/app.php`:

    ```php
    Smsa\SmsaServiceProvider::class,

    ```

3. Add alias provider to the `aliases` in `config/app.php`:

    ```php
    'Smsa' => Smsa\SmsaFacade::class,

    ```

4. Publish the public assets:

    ```
    php artisan vendor:publish
    ```

5. Configure your `SMSA` account data in `config/smsa.php`:

# 2- Content Methods
- `Shipping` : Create shipping and git AWB number.
- `PrintAWB` : Create Shipping policy PDF.
- `Tracking` : Get shipping tracking.
- `Cancel`   : Cancel shipping.

# Parameters is needed
You can get more information from SMSA documentations

# Example about using
```php
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
```

# 3- Return structure
 All methods will return array contain `status` (true|false) and `value` (your need value)

# 4- Contact
for any question you can contact with me on twitter [@AhyadEssam](https://twitter.com/AhyadEssam), thanks
