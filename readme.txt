=== Show/Hide Content at Set Time ===

Contributors: thewanderingbrit
Donate link: https://www.theukedge.com/donate/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=donate
Tags: time, date, show hide, expire, active, activate, competition, giveaway, advert, advertising, content, post, text, hidden, show, appear, hide, shortcode, restrict, repeat, daily, monthly, weekly, every, week, day, month, off, on, yearly, annually, annual, year
Requires at least: 2.5
Tested up to: 4.7
Stable tag: 2.5
License: GPLv2

Shortcodes to wrap around text, which specify at what date or time that content should appear or disappear, either once, or on a recurring basis.

== Description ==

**Like this plugin?** Consider [leaving a quick review](https://wordpress.org/support/view/plugin-reviews/shortcodes-to-show-or-hide-content "Review Show/Hide Content at Set Time") or writing about how you've used it on your site - [send me a link](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact Dave") to that write up if you do.

This plugin is maintained on [GitHub](https://github.com/theukedge/shortcodes-to-show-or-hide-content), so feel free to use the repository for reporting issues, submitting feature requests and submitting pull requests.

This plugin provides shortcodes allowing you to display content or hide content at given dates and times. You can also combine the two to show some content at a given time, and then hide it at another time.

There's also a separate shortcode if you want to repeat a schedule for showing and hiding content - for example to only show information about a radio show while it's on air.

= Usage - single use =

To show or hide content at one point in time, you can use the `[time-restrict]` shortcode. For example:

`[time-restrict off="2015-01-01"]Please enter our competition[/time-restrict]
[time-restrict on="2015-01-01"]Sorry, this competition has closed[/time-restrict]`

If you do not enter a time with the date, it will default to 00:00:00, so in the above example, the first message will show up until 2015-01-01 00:00:00 (i.e. until the first second of 2015) and the second message will show up from 2015-01-01 00:00:00 (i.e. from the first second of 2015)

You can use any date or time string [supported by PHP](http://www.php.net/manual/en/datetime.formats.php "PHP Date and Time strings"), such as:

`[time-restrict off="September 10th, 2014"]Will display until 09/10/2014[/time-restrict]
[time-restrict off="2014-09-10"]Will display until 09/10/2014[/time-restrict]
[time-restrict off="10-Septmber 2014"]Will display until 09/10/2014[/time-restrict]
[time-restrict on="2014/09/10 18:00:00"]Will show after 09/10/2014 at 6pm[/time-restrict]`

You can also combine starting and ending dates for the same piece of content. So if you want some content to appear between January 1st and January 10th, you could do the following:

`[time-restrict on="2015-01-01" off="2015-01-11"]
This is a limited time offer
[/time-restrict]`

= Usage - repeating schedule =

To show and hide content on a recurring schedule, you can use the `[time-restrict-repeat]` shortcode. Your options are a daily, weekly, monthly or annual schedule.

A daily schedule takes the following form:

`[time-restrict-repeat type="daily" ontime="09:00:00" offtime="17:00:00"]
Working 9 to 5
[/time-restrict-repeat]`

Note that you can also cross over midnight, so to only show something from 10PM to 5AM, you can use:

`[time-restrict-repeat type="daily" ontime="22:00:00" offtime="05:00:00"]
For the night owls
[/time-restrict-repeat]`

A weekly schedule takes the following form:

`[time-restrict-repeat type="weekly" onday="Monday" offday="Friday"]
The work week
[/time-restrict-repeat]`

You can also specify start and stop times if you want (it will assume starting at 00:00:00 and ending at 23:59:59 if these are left out, like above):

`[time-restrict-repeat type="weekly" onday="Friday" offday="Monday" ontime="17:00:00" offtime="08:00:00"]
It's the weekend baby!
[/time-restrict-repeat]`

A monthly schedule takes the following form:

`[time-restrict-repeat type="monthly" ondate="01" offdate="07"]
The first week of the month
[/time-restrict-repeat]`

You can both cross over the end of the month, and apply times to your start and end dates, e.g.

`[time-restrict-repeat type="monthly" ondate="25" offdate="05" ontime="17:00:00" offtime="08:00:00"]
We are exceptionally busy at the end of each billing cycle. Please bear with us!
[/time-restrict-repeat]`

And lastly, an annual schedule takes the following form:

`[time-restrict-repeat type="annually" onmonth="June" offmonth="August" ondate="01" offdate="31"]
I'm currently in the mountains enjoying the summer!
[/time-restrict-repeat]`

You can cross over the end of the month or even the end of the year, and apply times to your start and end dates, e.g.

`[time-restrict-repeat type="annually" onmonth="December" ondate="24" offmonth="January" offdate="05" ontime="17:00:00" offtime="08:00:00"]
Our employees are currently enjoying time with their families for Christmas. We'll be back after the New Year.
[/time-restrict-repeat]`

If you’re feeling creative, you can even nest shortcodes to account for multiple concurrent conditions. Since you cannot nest shortcodes with the same name, you need to add -2 or -3 to time-restrict-repeat in your shortcode (e.g. `[time-restrict-repeat-2]`).

For example, showing a message during business hours only on Monday to Friday:

`[time-restrict-repeat type="daily" ontime="08:00:00" offtime="17:00:00"]
[time-restrict-repeat-2 type="weekly" onday="Monday" offday="Friday"]
Our store is currently open
[/time-restrict-repeat-2]
[/time-restrict-repeat]`

You can also define a message which should appear if your content is not showing by using the `else` attribute @since 2.5. For example, you could invite people to visit your store during opening hours and ask them come back soon otherwise, like this:

`[time-restrict-repeat type="daily" ontime="08:00:00" offtime="17:00:00" else="We're currently closed. Come back and see us when we're open."]
[time-restrict-repeat-2 type="weekly" onday="Monday" offday="Friday" else="We're currently closed. Come back and see us when we're open."]
Our store is currently open
[/time-restrict-repeat-2]
[/time-restrict-repeat]`

Note that with nested shortcodes, you need to add the `else` attribute to each shortcode.

= Things to bear in mind =

* The time used by the plugin is your site's local time (check in Settings > General).
* The `else` attribute *does* accept HTML, but is restricted by [WordPress' limitation on HTML inside shortcode attributes](https://codex.wordpress.org/Shortcode_API#HTML).

I also run [Do It With WordPress](http://www.doitwithwp.com/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=my-other-sites "WordPress Tutorials"), which has an array of tutorials for managing, modifying and maintaining your WordPress sites, as well as [The WP Butler](https://www.thewpbutler.com/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=wordpress-services "WordPress Maintenance Services"), a service for keeping your site maintained, backed up, updated and secure.

== Installation ==

1. Upload `shortcodes-to-show-or-hide-content.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use shortcodes around your text

== Frequently Asked Questions ==

= How do I get the plugin to do "this"? =

Check the [Description tab](https://wordpress.org/plugins/shortcodes-to-show-or-hide-content/description/) for several examples showing what the plugin can do.

If you're still stuck, the best thing to do is to submit an issue on [GitHub](https://github.com/theukedge/shortcodes-to-show-or-hide-content/issues). If you don't have a GitHub account, then [get in touch with me](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact The UK Edge") and I'd be glad to help you out.

= Can you add a feature to this widget? =

Probably. The best thing to do is to create a new feature request on [GitHub](https://github.com/theukedge/shortcodes-to-show-or-hide-content/issues). If you don't have a GitHub account, then [tell me](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact The UK Edge") what this widget should do that it doesn't currently.

= Something's not right. What should I do? =

The best thing to do is to submit an issue on [GitHub](https://github.com/theukedge/shortcodes-to-show-or-hide-content/issues). If you don't have a GitHub account, then [get in touch with me](https://www.theukedge.com/contact/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=contact "Contact The UK Edge") and I'll see about getting it fixed.

== Changelog ==

= 2.5 =

Release date: December 14, 2016

* Addition of `else` parameter.
* Retire old (pre v2.0) shortcodes.

= 2.4 =

Release date: May 23, 2016

* Coding standards, logic and performance improvements.

= 2.3.1 =

Release date: August 31, 2015

* Corrected an error in the weekly logic (props [Jérôme](https://github.com/Grome13))

= 2.3 =

Release date: December 5, 2014

* Introduced recurring annual schedule (previously only monthly/weekly/daily). Just use type="annually" in your shortcodes.
* Code improvements.

= 2.2 =

Release date: October 9, 2014

* Fixed inability to nest shortcodes by introducing distinct copies of the time-restrict-repeat shortcode (`[time-restrict-repeat-2]` and `[time-restrict-repeat-3]`)

= 2.1.1 =

Release date: October 2, 2014

* Fixed error where content would show all the time when either onday and offday matched, or offdate and ondate matched.

= 2.1 =

Release date: August 30, 2014

* Revised code to allow for onday and offday being the same. Same for ondate and offdate.
* Changed attributes for `[time-restrict]` shortcode to be more in line with the rest of the plugin. Now using 'on' and 'off' instead of 'show' and 'hide', though the show and hide attributes will continue to work.

= 2.0 =

Release date: August 22, 2014

* New combined `[time-restrict]` shortcode allows for hide and show times for the same piece of content
* New `[time-restrict-repeat]` shortcode allows you to show and hide content on a daily, weekly or monthly recurring basis
* Code improvements
* Legacy shortcode support
* Enables nesting shortcodes within new shortcodes
* Plugin now respects site's local time, rather than server time

== Upgrade Notice ==

= 2.5 =
Addition of `else` parameter to (optionally) specify what should appear when the enclosed content is hidden. Retire pre-2.0 shortcodes.

= 2.4 =
Coding standards, logic and performance improvements.

= 2.3.1 =
Corrected an error in the weekly logic

= 2.3 =
You can now create annual recurring schedules for your shortcodes using type="annually" in your shortcode. See [Description tab](https://wordpress.org/plugins/shortcodes-to-show-or-hide-content/description/) for more details on how to use this.

= 2.2 =
Fixed issue with nesting multiple `[time-restrict-repeat]` shortcodes to achieve more complex scheduling. See [Description tab](https://wordpress.org/plugins/shortcodes-to-show-or-hide-content/description/) for more details on how to use these.

= 2.1.1 =
Now allows for onday and offday being the same (show content for one whole day, or a part of a single day, every week, or every month). Updated show and hide attributes to on and off, for better uniformity.

= 2.0 =
New combined shortcode (`[time-restrict]`) and new shortcode for a repeating show/hide schedule (`[time-restrict-repeat]`) - see plugin page for usage details. Old shortcode (`[expires]` and `[showafter]`) will continue to work.
