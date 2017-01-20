iCash.pl: Market
==

## Getting started

```php
<?php
use iCashpl\Market\Market;

$market = new Market('YOU_APP_KEY');

$market->setService([
	'id' => 'EdyEm5QfbSm7oSg8XojpkH87xz8qVHx2',
	'text' => 'ICH.TEST',
	'number' => 7055,
	'cost' => 1,
	'name' => 'PRODUCT 1'
]);

$market->setService([
	'id' => 't5Cv3HCrcBJnfYrLN5oKstAgcxjwE9ex',
	'text' => 'ICH.TEST2',
	'number' => 7055,
	'cost' => 1,
	'name' => 'PRODUCT 2'
]);

if (isset($_POST['code'])) {
    
    $market->getStatusCode([
        'service' => $_POST['service'],
        'code' => $_POST['code']
    ]);
    
    // ok
    if ($market->getCurrentService() && $market->icash()->statusOk()) {

    }
    // error
    else {

    }
}
?>
```