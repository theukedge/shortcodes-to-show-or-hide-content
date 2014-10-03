<?php

function time_restricted_shortcode( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'show' => '', // retired in favour of 'on' for better consistency
			'hide' => '', // retired in favour of 'off' for better consistency
			'on' => '',
			'off' => '',
		), $atts )
	);

	$showit = 0; // set some defaults
	$hideit = 1;

	if( empty( $on ) && !empty( $show ) ) { //  replace 'show' attribute with 'on'
		$on = $show;
	}

	if( empty( $off ) && !empty( $hide ) ) { //  replace 'hide' attribute with 'off'
		$off = $hide;
	}

	if( empty( $on ) || strtotime( $on ) < current_time( 'timestamp' ) ) { // if on time isn't set, or has already passed, show the content
		$showit = 1;
	}

	if( empty( $off ) || strtotime( $off ) > current_time( 'timestamp' ) ) {  // if off time isn't set, or is in the future, show the content
		$hideit = 0;
	}

	if( $showit == 1 && $hideit == 0 ) { // show the content if conditions met
		return do_shortcode( $content );
	}

	return;
}

add_shortcode( 'time-restrict', 'time_restricted_shortcode' );


function repeat_time_restricted_shortcode( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'type' => '',
			'ontime' => '00:00:00',
			'offtime' => '23:59:59',
			'onday' => '',
			'offday' => '',
			'ondate' => '',
			'offdate' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'monthly' && ( empty( $ondate ) || empty( $offdate ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'monthly' && $ondate < $offdate ) { // dates do not cross months
		if( $ondate < $todaysdate && $offdate > $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate > $offdate ) { // dates cross months
		if( $ondate < $todaysdate || $offdate < $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate == $offdate && $ondate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same date (today), and within show times
		$showit =1;
	}

	if( $type == 'weekly' && ( empty( $onday ) || empty( $offday ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'weekly' && $onwday < $offwday ) { // days do not cross weeks
		if( $onwday < $todaysday && $offwday > $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday > $offwday ) { // days cross weeks
		if( $onwday < $todaysday || $offwday < $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday == $offwday && $onwday == $todaysday && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same day (today), and within show times
		$showit = 1;
	}

	if( $type == 'daily' && ( empty( $ontime ) || empty( $offtime ) ) ) { //show content is not all required attributes set
		$showit = 1;
	} elseif( $type =='daily' && $ontime < $offtime ) { // time does not cross days
		if( $ontime < $currenttime && $currenttime < $offtime ) { //current time is between show times
			$showit = 1;
		}
	} elseif( $type == 'daily' && $ontime > $offtime ) { //time crosses days
		if( $ontime < $currenttime || $currenttime < $offtime ) { // current time is between show times
			$showit = 1;
		}
	}

	if( $showit == 1 ) { // if everything's in order, show the content
		return do_shortcode( $content );
	}

	return;
}

add_shortcode('time-restrict-repeat', 'repeat_time_restricted_shortcode' );


function repeat_time_restricted_shortcode_2( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'type' => '',
			'ontime' => '00:00:00',
			'offtime' => '23:59:59',
			'onday' => '',
			'offday' => '',
			'ondate' => '',
			'offdate' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'monthly' && ( empty( $ondate ) || empty( $offdate ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'monthly' && $ondate < $offdate ) { // dates do not cross months
		if( $ondate < $todaysdate && $offdate > $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate > $offdate ) { // dates cross months
		if( $ondate < $todaysdate || $offdate < $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate == $offdate && $ondate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same date (today), and within show times
		$showit =1;
	}

	if( $type == 'weekly' && ( empty( $onday ) || empty( $offday ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'weekly' && $onwday < $offwday ) { // days do not cross weeks
		if( $onwday < $todaysday && $offwday > $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday > $offwday ) { // days cross weeks
		if( $onwday < $todaysday || $offwday < $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday == $offwday && $onwday == $todaysday && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same day (today), and within show times
		$showit = 1;
	}

	if( $type == 'daily' && ( empty( $ontime ) || empty( $offtime ) ) ) { //show content is not all required attributes set
		$showit = 1;
	} elseif( $type =='daily' && $ontime < $offtime ) { // time does not cross days
		if( $ontime < $currenttime && $currenttime < $offtime ) { //current time is between show times
			$showit = 1;
		}
	} elseif( $type == 'daily' && $ontime > $offtime ) { //time crosses days
		if( $ontime < $currenttime || $currenttime < $offtime ) { // current time is between show times
			$showit = 1;
		}
	}

	if( $showit == 1 ) { // if everything's in order, show the content
		return do_shortcode( $content );
	}

	return;
}

add_shortcode('time-restrict-repeat-2', 'repeat_time_restricted_shortcode_2' );


function repeat_time_restricted_shortcode_3( $atts, $content ) {
	extract( shortcode_atts(
		array(
			'type' => '',
			'ontime' => '00:00:00',
			'offtime' => '23:59:59',
			'onday' => '',
			'offday' => '',
			'ondate' => '',
			'offdate' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'monthly' && ( empty( $ondate ) || empty( $offdate ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'monthly' && $ondate < $offdate ) { // dates do not cross months
		if( $ondate < $todaysdate && $offdate > $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate > $offdate ) { // dates cross months
		if( $ondate < $todaysdate || $offdate < $todaysdate ) { // today is between show dates
			$showit = 1;
		} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
			$showit = 1;
		} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'monthly' && $ondate == $offdate && $ondate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same date (today), and within show times
		$showit =1;
	}

	if( $type == 'weekly' && ( empty( $onday ) || empty( $offday ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'weekly' && $onwday < $offwday ) { // days do not cross weeks
		if( $onwday < $todaysday && $offwday > $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday > $offwday ) { // days cross weeks
		if( $onwday < $todaysday || $offwday < $todaysday ) { // today is between show days
			$showit = 1;
		} elseif( $onwday == $todaysday && $ontime < $currenttime ) { // today is on day and on time has passed
			$showit = 1;
		} elseif( $offwday == $todaysday && $offtime > $currenttime ) { // today is off day and off time hasn't yet passed
			$showit = 1;
		}
	} elseif( $type == 'weekly' && $onwday == $offwday && $onwday == $todaysday && $ontime < $currenttime && $offtime > $currenttime ) { // on and off on same day (today), and within show times
		$showit = 1;
	}

	if( $type == 'daily' && ( empty( $ontime ) || empty( $offtime ) ) ) { //show content is not all required attributes set
		$showit = 1;
	} elseif( $type =='daily' && $ontime < $offtime ) { // time does not cross days
		if( $ontime < $currenttime && $currenttime < $offtime ) { //current time is between show times
			$showit = 1;
		}
	} elseif( $type == 'daily' && $ontime > $offtime ) { //time crosses days
		if( $ontime < $currenttime || $currenttime < $offtime ) { // current time is between show times
			$showit = 1;
		}
	}

	if( $showit == 1 ) { // if everything's in order, show the content
		return do_shortcode( $content );
	}

	return;
}

add_shortcode('time-restrict-repeat-3', 'repeat_time_restricted_shortcode_3' );