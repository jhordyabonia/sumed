# Mage2 Module Sumed Attributes

    ``sumed/module-attributes``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
N/A

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Sumed`
 - Enable the module by running `php bin/magento module:enable Sumed_Attributes`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require sumed/module-attributes`
 - enable the module by running `php bin/magento module:enable Sumed_Attributes`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications




## Attributes

 - Product - brand (brandID)

 - Product -  Unit of measurement (umID)

 - Product - Bulb thread type (Btty)

 - Product - Maximum bulb power (Mbpo)

 - Product - Luminous flux (Lflux)

