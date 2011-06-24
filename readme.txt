=== Show/Hide Content at Set Time ===
Contributors: thewanderingbrit
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DB5AQD4WA8NK6
Tags: time, date, show hide, expire, active, activate, competition, advert, advertising, content, post, text
Requires at least: 2.5
Tested up to: 3.1.3
Stable tag: 1.0

Creates shortcodes which you can wrap around text, which specify at what date or time that content should appear or disappear. Great for competitions

== Description ==

This plugin allows you to wrap certain parts of your post content in a shortcode and enter a date or time when that content should either appear, or disappear.

For example, if you have a competition that ends on 12/31/2012, you can use:
`[expires off="2012-12-31"]Please enter our competition[/expires]
[showafter on="2012-12-31"]Sorry, this competition has closed[/closed]`

You can use any date or time string [supported by PHP](http://www.php.net/manual/en/datetime.formats.php "PHP Date and Time strings"), such as:
`[expires off="10 September 2012"]Will expire on 09/10/2012[/expires]
[expires off="2012-09-10"]Will expire on 09/10/2012[/expires]
[expires off="Septmber 10th, 2012"]Will expire on 09/10/2012[/expires]
[showafter on="2012.09.10 18:00:00"]Will show after 09/10/2012 at 6pm[/showafter]`

Thanks to [Alex King](http://alexking.org "Alex King") and [Crowd Favorite](http://www.crowdfavorite.com "Crowd Favorite") for their original code in the [Expiring Content Shortcode](http://wordpress.org/extend/plugins/expiring-content-shortcode/ "Expiring Content Shortcode").

Check out [Do It With WordPress](http://www.doitwithwp.com "WordPress Tutorials") for more plugins and tutorials.

== Installation ==

1. Upload `shortcodes-to-show-or-hide-content.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use shortcodes around your text

== Frequently Asked Questions ==

= I need some help =

Please [contact me](http://www.theukedge.com/contact/ "Contact The UK Edge") if you have any problems with this plugin.

== Changelog ==

= 1.0 =
* Stable public release

== Upgrade Notice ==

= 1.0 =
Stable public release