=== Show/Hide Content at Set Time ===
Contributors: thewanderingbrit
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DB5AQD4WA8NK6
Tags: time, date, show hide, expire, active, activate, competition, advert, advertising, content, post, text, hidden, show, appear, hide, shortcode
Requires at least: 2.5
Tested up to: 3.3.1
Stable tag: 1.0.2

Creates shortcodes which you can wrap around text, which specify at what date or time that content should appear or disappear. Great for competitions

== Description ==

This plugin allows you to wrap certain parts of your post content in a shortcode and enter a date or time when that content should either appear, or disappear.

For example, if you have a competition that ends on 12/31/2012, you can use:

`[expires off="2013-01-01"]Please enter our competition[/expires]
[showafter on="2013-01-01"]Sorry, this competition has closed[/showafter]`

This is because on the 31st, you still want to be advertising the competition and you don't want to say its closed until the 1st of January.

You can use any date or time string [supported by PHP](http://www.php.net/manual/en/datetime.formats.php "PHP Date and Time strings"), such as:

`[expires off="10 September 2012"]Will expire on 09/10/2012[/expires]
[expires off="2012-09-10"]Will expire on 09/10/2012[/expires]
[expires off="Septmber 10th, 2012"]Will expire on 09/10/2012[/expires]
[showafter on="2012.09.10 18:00:00"]Will show after 09/10/2012 at 6pm[/showafter]`

Bear in mind that by just entering a date, it will assume a time of midnight at the beginning of that date. So if you set your expiration date to today, it will expire at the beginning of today (12:00:00AM), not the end of the day. If you would prefer to show the content for the rest of today, then you should set your expiration date to tomorrow.

Thanks to [Alex King](http://alexking.org "Alex King") and [Crowd Favorite](http://www.crowdfavorite.com "Crowd Favorite") for their original code in the [Expiring Content Shortcode](http://wordpress.org/extend/plugins/expiring-content-shortcode/ "Expiring Content Shortcode").

Check out [Do It With WordPress](http://www.doitwithwp.com "WordPress Tutorials") for more plugins and tutorials.

== Installation ==

1. Upload `shortcodes-to-show-or-hide-content.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use shortcodes around your text

== Frequently Asked Questions ==

= Something's not right. What should I do? =

Please [contact me](http://www.theukedge.com/contact/ "Contact The UK Edge") if you have any problems with this plugin.

== Changelog ==

= 1.0.2 =
* Code revisions

= 1.0.1 =
* Made function names more unique

= 1.0 =
* Stable public release

== Upgrade Notice ==

= 1.0.2 =
Code revisions for more stability

= 1.0.1 =
Made function names more unique to avoid conflicts with other plugins

= 1.0 =
Stable public release