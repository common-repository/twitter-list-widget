=== Twitter List Widget ===
Contributors: yorik
Donate link: None! Keep your money for important things!
Tags: rss, feed, twitter, list, identica
Requires at least: 2.8.4
Tested up to: 2.8.5
Stable tag: trunk

A widget to display a feed from a twitter list such as those generated from http://twiterlist2rss.appspot.com/ or from a comma-separated list of twitter feeds. In fact, any RSS feed where you want the links, the @someone and the #something to be turned into clickable links.

== Description ==

This plugin allows to place widgets on your sidebars, that fetch the contents of one or more RSS feeds, combine them by date if there is more than one, and display their contents in a twitter-like manner, that is, a list of texts, where links, @someone and #something are turned into links.

Use it typically to condense several twitter feeds into one, or to display any other feed that follows twitter syntax, such as identi.ca

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory or use the wordpress plugin installer
2. Activate the plugin through the 'Plugins' menu in WordPress
3. A new "Twitter List Widget" will be available.
4. Give a list of feeds to the widget, separated by commas, such as http://www.example1.com/rss,http://www.example2.com/rss2

== Screenshots ==

See the plugin in action on http://www.oteatromagico.mus.br/wordpress

== Changelog ==

= 0.1 =
* First version

= 0.2 =
* Added a checkbox to have the first word of the feed turned into a twitter name link (use this if the first word is a twitter name, of course)
* If the feed contains an author field (for ex. twitter search results), the name is added as the first word of the displayed text (useful with checkbox above)
