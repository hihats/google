# Job Scripts taking Google Analytics Data and processing them

*service-account.json file must be placed in parent directory*

## Case PHP

### Configuration
```bash
mkdir config
vim config/simple.php
```
- configディレクトリを作成し、配下に***.phpファイルを作成
- Analyticsデータ取得時のパラメータを下記サンプルのように設定

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

### Simple sample script
[前日分のデータを取得Sample](https://github.com/hihats/google/blob/master/analytics/bin/simple_get.php)
