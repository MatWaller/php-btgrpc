# Simple Bitcoin Gold JSON-RPC client.

## Installation
Run ```php composer.phar require matwaller/php-goldrpc``` in your project directory or add following lines to composer.json
```javascript
"require": {
    "matwaller/php-goldrpc": "^2.0"
}
```
and run ```php composer.phar install```.

## Requirements
PHP 7.0 or higher

## Usage
Create new object with url as parameter
```php
/**
 * Don't forget to include composer autoloader by uncommenting line below
 * if you're not already done it anywhere else in your project.
 **/
// require 'vendor/autoload.php';

use Waller\Gold\Client as GoldClient;

$Goldd = new GoldClient('http://rpcuser:rpcpassword@localhost:19998/');
```
or use array to define your Goldd settings
```php
/**
 * Don't forget to include composer autoloader by uncommenting line below
 * if you're not already done it anywhere else in your project.
 **/
// require 'vendor/autoload.php';

use Waller\Gold\Client as GoldClient;

$Goldd = new GoldClient([
    'scheme'   => 'http',                 // optional, default http
    'host'     => 'localhost',            // optional, default localhost
    'port'     => 19998,                  // optional, default 19998
    'user'     => 'goldcashrpc',      // required
    'password' => 'rpcpassword',          // required
    'ca'       => '/etc/ssl/ca-cert.pem'  // optional, for use with https scheme
]);
```

```php
/**
 * Get block info.
 */
$block = $Goldd->getBlock('000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f');

$block('hash')->get();     // 000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f
$block['height'];          // 0 (array access)
$block->get('tx.0');       // 4a5e1e4baab89f3a32518a88c31bc87f618f76673e2cc77ab2127b7afdeda33b
$block->count('tx');       // 1
$block->has('version');    // key must exist and CAN NOT be null
$block->exists('version'); // key must exist and CAN be null
$block->contains(0);       // check if response contains value
$block->values();          // array of values
$block->keys();            // array of keys
$block->random(1, 'tx');   // random block txid
$block('tx')->random(2);   // two random block txid's
$block('tx')->first();     // txid of first transaction
$block('tx')->last();      // txid of last transaction

/**
 * Send transaction.
 */
$result = $Goldd->sendToAddress('mmXgiR6KAhZCyQ8ndr2BCfEq1wNG2UnyG6', 0.1);
$txid = $result->get();

/**
 * Get transaction amount.
 */
$result = $Goldd->listSinceBlock();
$gold = $result->sum('transactions.*.amount');
$goldtoshi = \Waller\Gold\to_goldtoshi($gold);
```
To send asynchronous request, add Async to method name:
```php
$Goldd->getBlockAsync(
    '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f',
    function ($response) {
        // success
    },
    function ($exception) {
        // error
    }
);
```

You can also send requests using request method:
```php
/**
 * Get block info.
 */
$block = $Goldd->request('getBlock', '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f');

$block('hash');            // 000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f
$block['height'];          // 0 (array access)
$block->get('tx.0');       // 4a5e1e4baab89f3a32518a88c31bc87f618f76673e2cc77ab2127b7afdeda33b
$block->count('tx');       // 1
$block->has('version');    // key must exist and CAN NOT be null
$block->exists('version'); // key must exist and CAN be null
$block->contains(0);       // check if response contains value
$block->values();          // get response values
$block->keys();            // get response keys
$block->first('tx');       // get txid of the first transaction
$block->last('tx');        // get txid of the last transaction
$block->random(1, 'tx');   // get random txid

/**
 * Send transaction.
 */
$result = $Goldd->request('sendtoaddress', 'mmXgiR6KAhZCyQ8ndr2BCfEq1wNG2UnyG6', 0.06);
$txid = $result->get();

```
or requestAsync method for asynchronous calls:
```php
$Goldd->requestAsync(
    'getBlock',
    '000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f',
    function ($response) {
        // success
    },
    function ($exception) {
        // error
    }
);
```

## Multi-Wallet RPC
```php
/**
 * Get wallet2.dat balance.
 */
$balance = $Goldd->wallet('wallet2.dat')->getbalance();

echo $balance->get(); // 0.10000000
```


## Helpers
Package provides following helpers to assist with value handling.
### `to_gold()`
Converts value in goldtoshi to gold.
```php
echo Waller\Gold\to_gold(100000); // 0.00100000
```
### `to_goldtoshi()`
Converts value in gold to goldtoshi.
```php
echo Waller\Gold\to_goldtoshi(0.001); // 100000
```
### `to_ubtg()`
Converts value in gold to ubtg/bits.
```php
echo Waller\Gold\to_ubtg(0.001); // 1000.0000
```
### `to_mbtg()`
Converts value in gold to mbtg.
```php
echo Waller\Gold\to_mbtg(0.001); // 1.0000
```
### `to_fixed()`
Trims float value to precision without rounding.
```php
echo Waller\Gold\to_fixed(0.1236, 3); // 0.123
```

## License

This product is distributed under MIT license.