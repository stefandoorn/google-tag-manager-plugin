# Upgrade 1.x to 2.0

## Upgrade

Upgrading might be as simple as running the following command:

```bash
$ composer require stefandoorn/google-tag-manager-plugin:^2.0
```

Keep reading to understand the main changes that happened as part of the 2.0 release.

## Main changes

### Sylius

The plugin has been upgraded to work with Sylius ^2.0.

Also, the testing structure has been updated as much possible to reflect `PluginSkeleton^2.0`.

### PHP

Sylius 2.0 requires a minimum of PHP 8.2, and the plugin has been updated similarly.

### Sylius Twig Hooks

All `BlockEventListener` have been removed. They are replaced by Sylius Twig Hooks configurations.

| Removed `BlockEventListener`                                          | Replaced by Twig Hooks Config                                  |
|------------------------------------------------------------------------|----------------------------------------------------------------|
| `sylius.google_tag_manager.block_event_listener.layout.before_head`   | `sylius_shop.base.head: @GtmPlugin/head.html.twig`             |
| `sylius.google_tag_manager.block_event_listener.layout.before_body`   | `sylius_shop.base.header: @GtmPlugin/body.html.twig`           |
| `sylius.google_tag_manager.block_event_listener.layout.after_body`    | `sylius_shop.base.offcanvas: @GtmPlugin/after_body.html.twig`  |
| `sylius.google_tag_manager.block_event_listener.layout.javascripts`   | `sylius_shop.base#javascripts: @GtmPlugin/events_javascript.html.twig` |
