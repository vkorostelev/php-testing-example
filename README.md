# PHP Testing Example

My first hands-on experience with PHP and Codeception.
Developed using PHP 7.4 on MacOS via Brew.

# SEO
Script for testing SEO tags on the web sites.

##### running script
```shell script
php test_seo.php
```
##### Output example
```
Checking URL: https://www.theverge.com
> Title: ОК
> Description: Failed
>> Expected: Example of the wrong description
>> Got: The Verge was founded in 2011 in partnership with Vox Media, and covers the intersection of technology, science, art, and culture. Its mission is to offer in-depth reporting and long-form feature stories, breaking news coverage, product information, and community content in a unified and cohesive manner. The site is powered by Vox Media's Chorus platform, a modern media stack built for web-native news in the 21st century.
----------
Checking URL: https://www.spacex.com
> Title: ОК
> Description: ОК
----------
Checking URL: https://www.vox.com
> Title: Failed
>> Expected: Example of the wrong title
>> Got: Vox - Understand the News
> Description: ОК
----------
Checking URL: https://www.yahoo.com/news/
> Title: ОК
> Description: ОК
----------
Checking URL: https://finance.yahoo.com/
> Title: ОК
> Description: ОК
----------
Overall test results:
all: 5, success: 3, fail: 2.
```


# UI
Acceptance test example using Codeception and Selenium

##### Requirements
* composer
* selenium 3.8

##### installing dependencies
```shell script
composer install
```

##### starting selenium
Is required Selenium to be started at `http://localhost:4444`.
Otherwise, change these settings in `ui/codeception.yml`.

##### running tests
```
vendor/bin/codecept run
```
