# PHP Testing Example

My first hands-on experience with PHP and Codeception.
Developed using PHP 7.4 on MacOS via Brew.

# SEO
Script for testing SEO tags on the web sites.

##### running script
```shell script
php test_seo.php
```
##### Input
Data for tests stored in seo.csv file near the testing script.
Data in each row must be divided by `,`, enclosed with `""` brackets and placed in the following order:
1. url (required)
2. title
3. description
4. og:title
5. og:description
6. og:site_name
7. og:image
8. og:video
9. og:url
10. og:locale
11. og:type

Tags can be skipped but must follow the same order. For example:
* `"https://spacex.com","title example","description example"` - good
* `"https://spacex.com","title example",,,"og:description example"` - also good
* `"https://spacex.com","title example","og:title example"` - bad

##### Output example
```
Checking URL: https://www.theverge.com
> "title": ОК
> "description": failed
>> expected: Example of the wrong description
>> got: The Verge was founded in 2011 in partnership with Vox Media, and covers the intersection of technology, science, art, and culture. Its mission is to offer in-depth reporting and long-form feature stories, breaking news coverage, product information, and community content in a unified and cohesive manner. The site is powered by Vox Media's Chorus platform, a modern media stack built for web-native news in the 21st century.
> "og:description": ОК
----------
Checking URL: https://www.spacex.com
> "title": ОК
> "description": ОК
> "og:title": ОК
> "og:description": ОК
----------
Checking URL: https://www.vox.com
> "title": failed
>> expected: Example of the wrong title
>> got: Vox - Understand the News
> "description": ОК
----------
Checking URL: https://www.yahoo.com/news/
> "title": ОК
> "description": ОК
----------
Checking URL: https://finance.yahoo.com/
> "title": ОК
> "description": ОК
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
