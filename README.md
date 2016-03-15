# Some scripts using google api service

## Case PHP

Install [Library](https://github.com/google/google-api-php-client) by composer

```bash
$ mkdir google-api-php-client
$ cd google-api-php-client
$ composer require google/apiclient:^2.0.0@RC
```
### Configuration
```bash
mkdir config
vim config/simple.php
```
e.g.
```php
<?php
return [
  "view_ids" => [12345678],
  "dimensions" => 'ga:pagePath,ga:date',
  "metrics" => "ga:pageviews,ga:sessions,ga:users,ga:bounceRate",
  "filters" => "ga:pageviews>100",
  "sort" => "ga:date"
];
```
