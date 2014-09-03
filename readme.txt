=== Show/Hide Content at Set Time ===

Contributors: thewanderingbrit
Donate link: https://www.theukedge.com/donate/
Tags: time, date, show hide, expire, active, activate, competition, advert, advertising, content, post, text, hidden, show, appear, hide, shortcode, restrict, repeat, daily, monthly, weekly, every, week, day, month, off, on
Requires at least: 2.5
Tested up to: 4.0
Stable tag: 2.1.1
License: GPLv2

Shortcodes to wrap around text, which specify at what date or time that content should appear or disappear, either once, or on a recurring basis.

== Description ==

This plugin provides shortcodes allowing you to display content or hide content at given dates and times. You can also combine the two to show some content at a given time, and then hide it at another time.

There's also a separate shortcode if you want to repeat a schedule for showing and hiding content - for example to only show information about a radio show while it's on air.

= Usage - single use =

To show or hide content at one point in time, you can use the `[time-restrict]` shortcode. For example:

`[time-restrict off="2015-01-01"]Please enter our competition[/time-restrict]
[time-restrict on="2015-01-01"]Sorry, this competition has closed[/time-restrict]`

Note that this will cause the first message to appear on or before Dec 31, 2014 and the second message will appear on or after Jan 1st, 2015.

You can use any date or time string [supported by PHP](http://www.php.net/manual/en/datetime.formats.php "PHP Date and Time strings"), such as:

`[time-restrict off="10 September 2014"]Will expire on 09/10/2014[/time-restrict]
[time-restrict off="2014-09-10"]Will expire on 09/10/2014[/time-restrict]
[time-restrict off="Septmber 10th, 2014"]Will expire on 09/10/2014[/time-restrict]
[time-restrict on="2014.09.10 18:00:00"]Will show after 09/10/2014 at 6pm[/time-restrict]`

You can also combine starting and ending dates for the same piece of content. So if you want some content to appear between January 1st and January 10th, you could do the following:

`[time-restrict on="2015-01-01" off="2015-01-11"]This is a limited time offer[/time-restrict]`

= Usage - repeating schedule =

To show and hide content on a recurring schedule, you can use the `[time-restrict-repeat]` shortcode. Your options are a daily, weekly and monthly schedule.

A daily schedule takes the following form:

`[time-restrict-repeat type="daily" ontime="09:00:00" offtime="17:00:00"]Working 9 to 5[/time-restrict-repeat]`

Note that you can also cross over midnight, so to only show something from 10PM to 5AM, you can use:

`[time-restrict-repeat type="daily" ontime="22:00:00" offtime="05:00:00"]For the night owls[/time-restrict-repeat]`

A weekly schedule takes the following form:

`[time-restrict-repeat type="weekly" onday="Monday" offday="Friday"]The work week[/time-restrict-repeat]`

You can also specify start and stop times if you want (it will assume starting at 00:00:00 and ending at 23:59:59 if these are left out, like above):

`[time-restrict-repeat type="weekly" onday="Friday" offday="Monday" ontime="17:00:00" offtime="08:00:00"]It's the weekend baby![/time-restrict-repeat]`

And lastly, a monthly schedule takes the following form:

`[time-restrict-repeat type="monthly" ondate="01" offdate="07"]The first week of the month[/time-restrict-repeat]`

You can both cross over the end of the month, and apply times to your start and end dates, e.g.

`[time-restrict-repeat type="monthly" ondate="25" offdate="05" ontime="17:00:00" offtime="08:00:00"]We are exceptionally busy at the end of each billing cycle. Please bear with us![/time-restrict-repeat]`

If you’re feeling creative, you can even nest shortcodes to account for multiple concurrent conditions: for example, showing a message during business hours only on Monday to Friday:

`[time-restrict-repeat type="daily" ontime="08:00:00" offtime="17:00:00"][time-restrict-repeat type="weekly" onday="Monday" offday="Friday"]Our store is currently open[/time-restrict-repeat][/time-restrict-repeat]`

= Things to bear in mind =

Bear in mind that by just entering a date, it will assume a time of midnight at the beginning of that date. So if you set your expiration date to today, it will expire at the beginning of today (00:00:00), not the end of the day. If you would prefer to show the content for the rest of today, then you should set your expiration date to tomorrow.

The time used by the plugin is your site's local time (check in Settings > General) since version 2.0 (server time prior to that).

I also run [Do It With WordPress](http://www.doitwithwp.com "WordPress Tutorials"), which has an array of tutorials for managing, modifying and maintaining your WordPress sites, as well as [The WP Butler](http://www.thewpbutler.com), a service for keeping your site maintained, backup up, updated and secure.

== Installation ==

1. Upload `shortcodes-to-show-or-hide-content.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use shortcodes around your text

== Frequently Asked Questions ==

= Something's not right. What should I do? =

Please [contact me](http://www.theukedge.com/contact/ "Contact The UK Edge") if you have any problems with this plugin.

== Changelog ==

= 2.1 =
* Revised code to allow for onday and offday being the same. Same for ondate and offdate.
* Changed attributes for `[time-restrict]` shortcode to be more in line with the rest of the plugin. Now using 'on' and 'off' instead of 'show' and 'hide', though the show and hide attributes will continue to work.

= 2.0 =
* New combined `[time-restrict]` shortcode allows for hide and show times for the same piece of content
* New `[time-restrict-repeat]` shortcode allows you to show and hide content on a daily, weekly or monthly recurring basis
* Code improvements
* Legacy shortcode support
* Enables nesting shortcodes within new shortcodes
* Plugin now respects site's local time, rather than server time

= 1.0.2 =
* Code revisions

= 1.0.1 =
* Made function names more unique

= 1.0 =
* Stable public release

== Upgrade Notice ==

= 2.0 =
New combined shortcode (`[time-restrict]`) and new shortcode for a repeating show/hide schedule (`[time-restrict-repeat]`) - see plugin page for usage details. Old shortcode (`[expires]` and `[showafter]`) will continue to work.

= 1.0.2 =
Code revisions for more stability

= 1.0.1 =
Made function names more unique to avoid conflicts with other plugins

= 1.0 =
Stable public release