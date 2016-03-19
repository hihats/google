# Batch Scripts for getting Google Analytics Data

## Case PHP

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
