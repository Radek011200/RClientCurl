# RClientCurl


### How to install
```php
composer require radek011200/curl-client-php
```

### How to use - example
```php
use Radek011200\CurlClientPhp\Curl;
use Radek011200\CurlClientPhp\Request\Options;

$curl = new Curl();
$options = new Options();
$response = $curl->Get('https://github.com/Radek011200/RClientCurl', $options);

var_dump($response);
```
## Configuration
### Customize headers and options
```php
use Radek011200\CurlClientPhp\Request\Options;
use Radek011200\CurlClientPhp\Request\Header;
use Radek011200\CurlClientPhp\Request\CurlOpt;

$options = (new Options())
    ->addHeader(new Header('key', 'value'))
    ->addHeader(New Header('Accept', 'application/json'))
    ->addCurlOPT(new CurlOpt(CURLOPT_HEADER, 1));

var_dump($options->getCurlOPT(), $options->getHeaders());
```

### JWT authorization
```php
use Radek011200\CurlClientPhp\Request\Options;

$options = (new Options())->addJwtToken('token');
```

### BASIC authorization
```php
use Radek011200\CurlClientPhp\Request\Options;

$options = (new Options())->addBasicAuthLoginData('login', 'password');
```