# UPGRADE FROM `1.2.3` TO `2.0.0`

1. All `BlockEventListener` have been removed. They are replaced by Sylius Twig Hooks configurations:
   `sylius.google_tag_manager.block_event_listener.layout.before_head` replaced by twig hooks config `sylius_shop.base.head: @GtmPlugin/head.html.twig`
    `sylius.google_tag_manager.block_event_listener.layout.before_body` replaced by twig hooks config `sylius_shop.base.header: @GtmPlugin/body.html.twig`
    `sylius.google_tag_manager.block_event_listener.layout.after_body` replaced by twig hooks config `sylius_shop.base.offcanvas: @GtmPlugin/after_body.html.twig`
    `sylius.google_tag_manager.block_event_listener.layout.javascripts` replaced by twig hooks config `sylius_shop.base#javascripts: @GtmPlugin/events_javascript.html.twig`
