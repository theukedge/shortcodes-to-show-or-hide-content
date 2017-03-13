<?php

/*
 * Deprecate shortcodes with dashes
 *
 * Since Wordpress doesn't play nice with dashes in shortcode names, we've replaced those in favor of underscores.
 * Discussion: https://github.com/theukedge/shortcodes-to-show-or-hide-content/issues/26
 */

/*
// Keep the old shortcodes for a while. Better safe than sorry.
add_shortcode( 'time-restrict', 'time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-1', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-2', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-3', 'repeat_time_restricted_shortcode' );
*/

/*
 * Helper functions to update existing shortcodes (those with dashes) to new format (with underscores).
 */

function showhide_plugin_upgrade_action() {
	// do upgrade actions
	global $wpdb;

	// time-restrict-repeat-* to time_restrict_repeat_*
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[time-restrict-repeat-','[time_restrict_repeat_')" ); // open tag
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[/time-restrict-repeat-','[/time_restrict_repeat_')" ); // close tag

	// time-restrict-repeat to time_restrict_repeat
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[time-restrict-repeat','[time_restrict_repeat')" ); // open tag
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[/time-restrict-repeat]','[/time_restrict_repeat]')" ); // close tag

	// time-restrict to time_restrict
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[time-restrict','[time_restrict')" ); // open tag
	$wpdb->query( "UPDATE $wpdb->posts SET post_content = replace(post_content,'[/time-restrict]','[/time_restrict]')" ); // close tag

	// afterwards, save new version number in options
	update_option( 'showhide_plugin_version', SHOWHIDE_PLUGIN_VERSION );
}

function showhide_plugin_check_version() {
	// check if current version is in the DB. If not, or non existend, run an upgrade action
	if ( SHOWHIDE_PLUGIN_VERSION !== get_option( 'showhide_plugin_version' ) ) {
		showhide_plugin_upgrade_action();
	}

}

add_action( 'plugins_loaded', 'showhide_plugin_check_version' );


?>