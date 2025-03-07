# Google Tag Manager plugin for Sylius eCommerce Platform

[![License](https://img.shields.io/packagist/l/stefandoorn/google-tag-manager-plugin.svg)](https://packagist.org/packages/stefandoorn/google-tag-manager-plugin)
[![Version](https://img.shields.io/packagist/v/stefandoorn/google-tag-manager-plugin.svg)](https://packagist.org/packages/stefandoorn/google-tag-manager-plugin)
[![Build](https://github.com/stefandoorn/google-tag-manager-plugin/actions/workflows/build.yml/badge.svg)](https://github.com/stefandoorn/google-tag-manager-plugin/actions/workflows/build.yml)

## Related plugins

* [Enhanced Ecommerce plugin](https://github.com/stefandoorn/google-tag-manager-enhanced-ecommerce-plugin): allows you a smooth integration with
  Google Analytics Enhanced Ecommerce.

## Installation

### 1. Composer

```shell
composer require stefandoorn/google-tag-manager-plugin
```

### 2. Load bundle

Add to the bundle list (`config/bundles.php`):

```php
    GtmPlugin\GtmPlugin::class => ['all' => true],
```

### 3. Follow installation instructions of required sub bundle (https://github.com/xyNNN/GoogleTagManagerBundle)

Add to your configuration:

```yaml
google_tag_manager:
    enabled: true
    id: "GTM-XXXXXX"
    autoAppend: false
```

And also configure the features you would like to use from this plugin:

```yaml
gtm:
    inject: true
    features:
        environment: true
        route: true
        context: true
        events: true
```

In case you set `autoAppend` to false & also disable the `inject` setting inside this plugin, you have to manage loading of the GTM container yourself.

In case you set `autoAppend` to false & set `inject` to true, be aware of the following:

Required output to your HTML (head, body & footer) are done through events. Make sure the following 'sonata_block_render_events' are present in your views:

* `sylius.shop.layout.javascripts`
* `sylius.shop.layout.head`
* `sylius.shop.layout.before_body`
* `sylius.shop.layout.after_body`

You can dump the default configuration using:

```shell
bin/console config:dump-reference GtmPlugin
```

### 5. Install assets (optional: only if you want to use events feature)

```shell
bin/console assets:install 
bin/console sylius:install:assets
bin/console sylius:theme:assets:install
```

## Features

* `environment`: Send Symfony/Sylius environment to GTM
* `route`: Send Symfony/Sylius route to GTM
* `context`: Send Sylius context information to GTM (currency, locale, channel)
* `events`: See below - allows you to register events easily from inside HTML using JS.

## Usage

### Register events from JS

```javascript
var event = new GaEvent('category');
event.register(action, label, value);
```

Make sure also to listen for this specific event inside your GTM configuration.
