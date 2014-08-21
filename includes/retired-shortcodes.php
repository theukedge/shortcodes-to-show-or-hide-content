<?php

/************************** 
these shortcodes have been retired in favour of [time-restrict] and [time-restrict-repeat]
**************************/


// add expiration shortcode

function thewanderingbrit_expire_shortcode($args = array(), $content = '') {
	extract(shortcode_atts(
		array(
			'off' => 'tomorrow', // shortcode will not work without a set date
		),
		$args
	));
	if (strtotime($off) > time()) {
		return $content;
	}
	return '';
}

add_shortcode('expires', 'thewanderingbrit_expire_shortcode');


// add showafter shortcode

function thewanderingbrit_showafter_shortcode($args = array(), $content = '') {
	extract(shortcode_atts(
		array(
			'on' => 'tomorrow', // shortcode will not work without a set date
		),
		$args
	));
	if (strtotime($on) < time()) {
		return $content;
	}
	return '';
}
add_shortcode('showafter', 'thewanderingbrit_showafter_shortcode');