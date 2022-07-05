# Akamai Client for Magento 2 Module

## Installation

Please use composer to install the extension.

    * composer require hordieiev/module-akamai-client
    * php bin/magento setup:upgrade && php bin/magento setup:di:compile
    * php bin/magento module:enable Hordieiev_AkamaiClient

## Akamai Documentation
* [Add system setting which we need for access to Akamai API](https://techdocs.akamai.com/adaptive-media-delivery/reference/api-get-started)
* [Add tags as that is required in the akamai documentation](https://techdocs.akamai.com/purge-cache/docs/assign-cache-tags)
* [Cache tag](https://techdocs.akamai.com/purge-cache/reference/cache-tag)

## Run unit tests
Code has been developed with unit tests.
To run them, you can use regular console command:
```bash 
php ./vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist ./vendor/hordieiev/module-akamai-client
```

## Changelog
    * 1.0.1 - Implemented http client to Akamai connect
    * 1.0.0 - Initialize module
