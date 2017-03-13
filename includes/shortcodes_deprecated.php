<?php

/*
 * Deprecate shortcodes with dashes
 *
 * Since Wordpress doesn't play nice with dashes in shortcode names, we've replaced those in favor of underscores.
 * Discussion: https://github.com/theukedge/shortcodes-to-show-or-hide-content/issues/26
 */
function replace_shortcodes() {
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
}


/*
 * Helper functions for administration
 */
function showhide_plugin_upgrade_action() {
	// run upgrade actions
	replace_shortcodes(); // this function can be disabled / removed later on. The rest we can keep for future administration.

	// afterwards, save new version number in WP options
	update_option( 'showhide_plugin_version', SHOWHIDE_PLUGIN_VERSION );
}


function showhide_plugin_check_version() {
	// check if current version is in the WP options. If not, or non-existent, run upgrade action
	if ( SHOWHIDE_PLUGIN_VERSION !== get_option( 'showhide_plugin_version' ) ) {
		showhide_plugin_upgrade_action();
	}
}

add_action( 'plugins_loaded', 'showhide_plugin_check_version' );


?>