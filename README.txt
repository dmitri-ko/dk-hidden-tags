=== WP Plugin Name ===
Tags: search, tag, hidden, woocommerce, product
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds ability to add extra terms to WooCommerce Product.

== Description ==

The WooCommerce Hidden Search Terms plugin adds ability to add and use in search extra terms to any WooCommerce Product.
The plugin adds Hidden Tags tab to standard Product tabs in admin screen. On the tab user can add a list of space
separated tags that could be used in search algorithm. The terms added by the plugin are hidden and aren't shown on
frontend.

In order to use extra terms in search algorithm use WP filter 'dk_search_hidden'.

The plugin supports uploader to update new version from Plugins admin panel.


== Installation ==

1. Download the plugin from the [source](http://dmitriko.ru/wp/wp-content/uploads/updater/dk-hidden-tags/dk-hidden-tags.zip) .
2. Go to WordPress admin area and visit Plugins » Add New page.
3. Click on the ‘Upload Plugin’ button on top of the page.
4. In the plugin upload form click on the ‘Choose File’ button and select the plugin file you downloaded earlier to your computer.
5. Click on the ‘Install Now’ button
6. Activate the plugin through the 'Plugins' menu in WordPress
7. Place `<?php apply_filter('dk_search_hidden', $term); ?>` in your templates


== Changelog ==

= 1.0.0 =
* Initial release.


