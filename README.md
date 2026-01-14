### Example project composer.json using this package:

```
{
    "name": "ram/sklep",
    "description": "Sklep",
    "autoload": {
        "psr-4": {
            "Ram\\Sklep\\": "src/"
        }
    },
    "require": {
        "ram/sklep-api-client": "@dev",
        "guzzlehttp/guzzle": "^7.10",
        "php-http/guzzle7-adapter": "^1.1"
    },
    "repositories": [
        {
            "type": "path",
            "url": "sklep-api-client"
        }
    ],
    "config": {
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

### Example use:
```
$shop = new \SAC\App\Api\Shop([
    'basic_auth_user' => '{user}',
    'basic_auth_pass' => '{pass}',
]);

$producers = new \SAC\App\Shop\Producers($shop);

//create new producer
try {
    $producers->create(\SAC\App\Model\Producer::fromArray([
        'id' => 11235,
        'name' => 'costam1',
        'site_url' => 'onet1.pl',
        'logo_filename' => 'onet1',
        'ordering' => 'aaa1231',
        'source_id' => 'nll'
    ]));
} catch (\SAC\App\Api\Exception\ResponseException $e) {
    echo 'Response error: ' . $e->getMessage() . ' Response code: ' . $e->getHttpCode();
}

// get all producers
$producers->getAll();
```
