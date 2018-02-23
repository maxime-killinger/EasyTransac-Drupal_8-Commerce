# INTRODUCTION

The EasyTransac module adds a payment method to Drupal 7 Commerce in order to
check out via EasyTransac's payment service.

## REQUIREMENTS

This module requires the following PHP specifications:

* cURL
* OpenSSL version >= 1.0.1

This module requires the following modules:

* Drupal commerce (https://www.drupal.org/project/commerce)

This module requires an EasyTransac account, please visit:

* EasyTransac (https://easytransac.com)

## INSTALLATION

* Install as you would normally install a contributed Drupal module. See: https://drupal.org/documentation/install/modules-themes/modules-7 for further information.


## CONFIGURATION

* Create an account on https://www.easytransac.com and configure your application by allowing your server's IP address. This will provide you with an API Key.

* Enter your API Key on the payment method configuration page, accessible via Store / Configuration / Payment methods / EasyTransac payment / Actions / Enable payment method: Easytransac Payment / edit and don't forget to save the form.