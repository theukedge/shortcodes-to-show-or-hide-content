<?php
/**
 * Shortcodes to show or hide content
 *
 * Creates the shortcodes that can show or hide content on a one-off basis
 * or on a recurring schedule.
 *
 * @package		STSOHC
 * @subpackage	Functions/Shortcodes
 * @copyright	Copyright (c) 2016
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since		2.0
 */

/**
 * Time Restricted Shortcode
 *
 * @since      2.0
 * @author     Dave Clements
 * @param      array  $atts Shortcode attributes.
 * @param      string $content The content contained between the shortcode.
 * @return     string The content to display
 */
function time_restricted_shortcode( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'show'	=> '', // Retired in favour of 'on' for better consistency.
			'hide'	=> '', // Retired in favour of 'off' for better consistency.
			'on'	=> '',
			'off'	=> '',
			'else'	=> '',
		), $atts )
	);

	$showit = 0; // Set some defaults.
	$hideit = 1;

	if ( empty( $on ) && ! empty( $show ) ) { // Replace 'show' attribute with 'on'.
		$on = $show;
	}

	if ( empty( $off ) && ! empty( $hide ) ) { // Replace 'hide' attribute with 'off'.
		$off = $hide;
	}

	if ( empty( $on ) || strtotime( $on ) < current_time( 'timestamp' ) ) { // If on time isn't set, or has already passed, show the content.
		$showit = 1;
	}

	if ( empty( $off ) || strtotime( $off ) > current_time( 'timestamp' ) ) {  // If off time isn't set, or is in the future, show the content.
		$hideit = 0;
	}

	if ( 1 === $showit && 0 === $hideit ) { // Show the content if conditions met.
		return do_shortcode( $content );
	} elseif ( ! empty( $else ) ) {
		return wp_kses_post( $else );
	}

	return '';
}

add_shortcode( 'time-restrict', 'time_restricted_shortcode' );


/**
 * Time Restricted Repeat Shortcode
 *
 * @since      2.0
 * @author     Dave Clements
 * @param      array  $atts Shortcode attributes.
 * @param      string $content The content contained between the shortcode.
 * @return     string The content to display
 */
function repeat_time_restricted_shortcode( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'type' => '',
			'ontime' => '00:00:00',
			'offtime' => '23:59:59',
			'onday' => '',
			'offday' => '',
			'ondate' => '',
			'offdate' => '',
			'onmonth' => '',
			'offmonth' => '',
			'else'	=> '',
		), $atts )
	);

	$showit = 0; // Set some defaults.

	$onwday = date( 'w', strtotime( ucfirst( $onday ) ) ); // Convert day to PHP numerical representation of day (w).
	$offwday = date( 'w', strtotime( ucfirst( $offday ) ) );
	$onmonth = date( 'n', strtotime( $onmonth ) ); // Make sure month is in numberical format.
	$offmonth = date( 'n', strtotime( $offmonth ) );

	$todaysday = date( 'w', current_time( 'timestamp' ) ); // What's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$todaysmonth = date( 'n', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if ( 'annually' === $type && ( empty( $ondate ) || empty( $offdate ) || empty( $onmonth ) || empty( $offmonth ) ) ) { // Show content if not all required attributes set.
		$showit = 1;
	} elseif ( 'annually' === $type && $onmonth < $offmonth ) { // Months do not cross years.
		if ( $onmonth < $todaysmonth && $offmonth > $todaysmonth ) { // Today is in between show months.
			$showit = 1;
		} elseif ( $onmonth === $todaysmonth ) { // Turns on this month and off later.
			if ( $ondate < $todaysdate ) { // On date has already passed.
				$showit = 1;
			} elseif ( $ondate === $todaysdate && $ontime < $currenttime ) { // Today is on date and on time has passed.
				$showit = 1;
			}
		} elseif ( $offmonth === $todaysmonth ) { // Turns off this month and on earlier.
			if ( $offdate > $todaysdate ) { // Off date hasn't arrived yet.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Today is off date and off time hasn't yet passed.
				$showit = 1;
			}
		}
	} elseif ( 'annually' === $type && $onmonth > $offmonth ) { // Months cross years.
		if ( $onmonth < $todaysmonth || $offmonth > $todaysmonth ) { // Today is in between show months.
			$showit = 1;
		} elseif ( $onmonth === $todaysmonth ) { // Turns on this month and off next year.
			if ( $ondate < $todaysdate ) { // On date has already passed.
				$showit = 1;
			} elseif ( $ondate === $todaysdate && $ontime < $currenttime ) { // Today is on date and on time has passed.
				$showit = 1;
			}
		} elseif ( $offmonth === $todaysmonth ) { // Turns off this month and on last year.
			if ( $offdate > $todaysdate ) { // Off date hasn't arrived yet.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Today is off date and off time hasn't yet passed.
				$showit = 1;
			}
		}
	} elseif ( 'annually' === $type && $onmonth === $offmonth && $onmonth === $todaysmonth ) { // Turns on and off this month.
		if ( $ondate < $todaysdate ) { // Today is after on day.
			if ( $offdate > $todaysdate ) { // Off day hasn't yet happened.
				$showit = 1;
			} elseif ( $offdate < $ondate ) { // Doesn't turn off until next year.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Today is off date and off time hasn't yet passed.
				$showit = 1;
			} elseif ( $ondate === $offdate && $offdate !== $todaysdate && $offtime < $ontime ) { // Turns on for almost a full year, less a few hours.
				$showit = 1;
			}
		} elseif ( $ondate > $todaysdate ) { // Today is before on day.
			if ( $offdate < $ondate && $offdate > $todaysdate ) { // Turns off later this month after being on for nearly a year.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Turns off today after being on for nearly a year.
				$showit = 1;
			} elseif ( $ondate === $offdate && $offdate !== $todaysdate && $offtime < $ontime ) { // Turns on for almost a full year, less a few hours.
				$showit = 1;
			}
		} elseif ( $ondate === $todaysdate ) { // Today is on day.
			if ( $offdate > $todaysdate && $ontime < $currenttime ) { // Turns off later this month and on time has passed.
				$showit = 1;
			} elseif ( $offdate < $todaydate && $ontime < $currenttime ) { // Turns off next year and on time has passed.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // Turns on and off today and is between show times.
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $ontime > $offtime && $ontime < $currenttime ) { // Turns on for almost a full year, less a few hours (earlier today).
				$showit = 1;
			} elseif ( $offdate === $todaysdate && $ontime > $offtime && $offtime > $currenttime ) { // Turns on for almost a full year, less a few hours (later today).
				$showit = 1;
			}
		}
	} elseif ( 'annually' === $type && $onmonth === $offmonth && $onmonth !== $todaysmonth && $offdate < $ondate ) { // Turns on and off the same month (but not this month) and is on for most of the year.
		$showit = 1;
	} elseif ( 'annually' === $type && $onmonth === $offmonth && $onmonth !== $todaysmonth && $offdate === $ondate && $offtime < $ontime ) { // Turns on for almost a full year, less a few hours.
		$showit = 1;
	}

	if ( 'monthly' === $type && ( empty( $ondate ) || empty( $offdate ) ) ) { // Show content if not all required attributes set.
		$showit = 1;
	} elseif ( 'monthly' === $type && $ondate < $offdate ) { // Dates do not cross months.
		if ( $ondate < $todaysdate && $offdate > $todaysdate ) { // Today is between show dates.
			$showit = 1;
		} elseif ( $ondate === $todaysdate && $ontime < $currenttime ) { // Today is on date and on time has passed.
			$showit = 1;
		} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Today is off date and off time hasn't yet passed.
			$showit = 1;
		}
	} elseif ( 'monthly' === $type && $ondate > $offdate ) { // Dates cross months.
		if ( $ondate < $todaysdate || $offdate < $todaysdate ) { // Today is between show dates.
			$showit = 1;
		} elseif ( $ondate === $todaysdate && $ontime < $currenttime ) { // Today is on date and on time has passed.
			$showit = 1;
		} elseif ( $offdate === $todaysdate && $offtime > $currenttime ) { // Today is off date and off time hasn't yet passed.
			$showit = 1;
		}
	} elseif ( 'monthly' === $type && $ondate === $offdate && $ondate === $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // On and off on same date (today), and within show times.
		$showit = 1;
	}

	if ( 'weekly' === $type && ( empty( $onday ) || empty( $offday ) ) ) { // Show content if not all required attributes set.
		$showit = 1;
	} elseif ( 'weekly' === $type && $onwday < $offwday ) { // Days do not cross weeks.
		if ( $onwday < $todaysday && $offwday > $todaysday ) { // Today is between show days.
			$showit = 1;
		} elseif ( $onwday === $todaysday && $ontime < $currenttime ) { // Today is on day and on time has passed.
			$showit = 1;
		} elseif ( $offwday === $todaysday && $offtime > $currenttime ) { // Today is off day and off time hasn't yet passed.
			$showit = 1;
		}
	} elseif ( 'weekly' === $type && $onwday > $offwday ) { // Days cross weeks.
		if ( $onwday < $todaysday || $offwday > $todaysday ) { // Today is between show days.
			$showit = 1;
		} elseif ( $onwday === $todaysday && $ontime < $currenttime ) { // Today is on day and on time has passed.
			$showit = 1;
		} elseif ( $offwday === $todaysday && $offtime > $currenttime ) { // Today is off day and off time hasn't yet passed.
			$showit = 1;
		}
	} elseif ( 'weekly' === $type && $onwday === $offwday && $onwday === $todaysday && $ontime < $currenttime && $offtime > $currenttime ) { // On and off on same day (today), and within show times.
		$showit = 1;
	}

	if ( 'daily' === $type && ( empty( $ontime ) || empty( $offtime ) ) ) { // Show content is not all required attributes set.
		$showit = 1;
	} elseif ( 'daily' === $type && $ontime < $offtime ) { // Time does not cross days.
		if ( $ontime < $currenttime && $currenttime < $offtime ) { // Current time is between show times.
			$showit = 1;
		}
	} elseif ( 'daily' === $type && $ontime > $offtime ) { // Time crosses days.
		if ( $ontime < $currenttime || $currenttime < $offtime ) { // Current time is between show times.
			$showit = 1;
		}
	}

	if ( 1 === $showit ) { // If everything's in order, show the content.
		return do_shortcode( $content );
	} elseif ( ! empty( $else ) ) {
		return wp_kses_post( $else );
	}

	return '';
}

add_shortcode( 'time-restrict-repeat', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-1', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-2', 'repeat_time_restricted_shortcode' );
add_shortcode( 'time-restrict-repeat-3', 'repeat_time_restricted_shortcode' );
