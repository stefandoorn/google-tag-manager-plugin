# Google Tag Manager plugin for Sylius 

[![License](https://img.shields.io/packagist/l/stefandoorn/google-tag-manager-plugin.svg)](https://packagist.org/packages/stefandoorn/google-tag-manager-plugin) [![Version](https://img.shields.io/packagist/v/stefandoorn/google-tag-manager-plugin.svg)](https://packagist.org/packages/stefandoorn/google-tag-manager-plugin) [![Build status on Linux](https://img.shields.io/travis/stefandoorn/google-tag-manager-plugin/master.svg)](http://travis-ci.org/stefandoorn/google-tag-manager-plugin) [![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/stefandoorn/google-tag-manager-plugin.svg)](https://scrutinizer-ci.com/g/stefandoorn/google-tag-manager-plugin/) [![Code Coverage](https://scrutinizer-ci.com/g/stefandoorn/google-tag-manager-plugin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/stefandoorn/google-tag-manager-plugin/?branch=master)

Google Tag Manager plugin for Sylius eCommerce Platform

## Installation

### 1. Composer

`composer require stefandoorn/google-tag-manager-plugin`

### 2. Follow installation instructions of required sub bundle

https://github.com/xyNNN/GoogleTagManagerBundle

### 3. Load bundle

Add to the bundle list in `app/AppKernel.php`:

```php
new GtmPlugin\GtmPlugin(),
```

### 4. Adjust configurations

Add to `app/config/config.yml`:

```yml
    - { resource: "@GtmPlugin/Resources/config/services.yml" }
```

Required output to your HTML (head, body & footer) are done through events. Make sure the following 'sonata_block_render_events' are present in your views:

* `sylius.shop.layout.javascripts`
* `sylius.shop.layout.before_head`
* `sylius.shop.layout.before_body`

And configure the features you would like to use/not. Find a base configuration reference by running:

```
bin/console config:dump-reference GtmPlugin
```

### 5. Install assets

```
bin/console assets:install 
bin/console sylius:install:assets
bin/console sylius:theme:assets:install
```

## Features

* `environment`: Send Symfony/Sylius environment to GTM
* `route`: Send Symfony/Sylius route to GTM

## Usage

### Register events from JS
 
```javascript
    var event = new GaEvent('category');
    event.register(action, label, value);
```
