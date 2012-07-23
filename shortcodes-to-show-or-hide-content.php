<?php
/*
Plugin Name: Shortcodes to show or hide content
Plugin URI: http://www.doitwithwp.com/
Description: Set a date/time to show or hide specific parts of a post's content
Version: 1.0.2
Author: Dave Clements
Author URI: http://www.theukedge.com
License: GPL2
*/

/*  Copyright 2011  Dave Clements  (email : http://www.theukedge.com/contact/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Add expiration shortcode //

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

// Add showafter shortcode //

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