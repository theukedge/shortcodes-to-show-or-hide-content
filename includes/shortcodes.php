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
			'offdate' => '',
			'onmonth' => '',
			'offmonth' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );
	$onmonth = date( 'n', strtotime( $onmonth ) ); // make sure month is in numberical format
	$offmonth = date( 'n', strtotime( $offmonth ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$todaysmonth = date( 'n', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'annually' && ( empty( $ondate ) || empty( $offdate ) || empty( $onmonth ) || empty( $offmonth ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth < $offmonth ) { // months do not cross years
		if( $onmonth < $todaysmonth && $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off later
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on earlier
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth > $offmonth ) { // months cross years
		if( $onmonth < $todaysmonth || $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off next year
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on last year
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth == $todaysmonth ) { // turns on and off this month
		if( $ondate < $todaysdate ) { // today is after on day
			if( $offdate > $todaysdate ) { // off day hasn't yet happened
				$showit = 1;
			} elseif( $offdate < $ondate ) { // doesn't turn off until next year
				$showit = 1;
			} elseif ( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate > $todaysdate) { // today is before on day
			if( $offdate < $ondate && $offdate > $todaysdate ) { // turns off later this month after being on for nearly a year
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // turns off today after being on for nearly a year
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate == $todaysdate ) { // today is on day
			if( $offdate > $todaysdate && $ontime < $currenttime ) { // turns off later this month and on time has passed
				$showit = 1;
			} elseif( $offdate < $todaydate && $ontime < $currenttime ) { // turns off next year and on time has passed
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // turns on and off today and is between show times
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $ontime < $currenttime ) { // turns on for almost a full year, less a few hours (earlier today)
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $offtime > $currenttime ) { // turns on for almost a full year, less a few hours (later today)
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate < $ondate ) { // turns on and off the same month (but not this month) and is on for most of the year
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate == $ondate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
		$showit = 1;
	}

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
		if( $onwday < $todaysday || $offwday > $todaysday ) { // today is between show days
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
			'offdate' => '',
			'onmonth' => '',
			'offmonth' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );
	$onmonth = date( 'n', strtotime( $onmonth ) ); // make sure month is in numberical format
	$offmonth = date( 'n', strtotime( $offmonth ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$todaysmonth = date( 'n', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'annually' && ( empty( $ondate ) || empty( $offdate ) || empty( $onmonth ) || empty( $offmonth ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth < $offmonth ) { // months do not cross years
		if( $onmonth < $todaysmonth && $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off later
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on earlier
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth > $offmonth ) { // months cross years
		if( $onmonth < $todaysmonth || $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off next year
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on last year
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth == $todaysmonth ) { // turns on and off this month
		if( $ondate < $todaysdate ) { // today is after on day
			if( $offdate > $todaysdate ) { // off day hasn't yet happened
				$showit = 1;
			} elseif( $offdate < $ondate ) { // doesn't turn off until next year
				$showit = 1;
			} elseif ( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate > $todaysdate) { // today is before on day
			if( $offdate < $ondate && $offdate > $todaysdate ) { // turns off later this month after being on for nearly a year
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // turns off today after being on for nearly a year
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate == $todaysdate ) { // today is on day
			if( $offdate > $todaysdate && $ontime < $currenttime ) { // turns off later this month and on time has passed
				$showit = 1;
			} elseif( $offdate < $todaydate && $ontime < $currenttime ) { // turns off next year and on time has passed
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // turns on and off today and is between show times
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $ontime < $currenttime ) { // turns on for almost a full year, less a few hours (earlier today)
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $offtime > $currenttime ) { // turns on for almost a full year, less a few hours (later today)
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate < $ondate ) { // turns on and off the same month (but not this month) and is on for most of the year
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate == $ondate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
		$showit = 1;
	}

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
		if( $onwday < $todaysday || $offwday > $todaysday ) { // today is between show days
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
			'offdate' => '',
			'onmonth' => '',
			'offmonth' => ''
		), $atts )
	);

	$showit = 0; // set some defaults

	$onwday = date( 'w', strtotime( $onday ) ); // convert day to PHP numerical representation of day (w)
	$offwday = date( 'w', strtotime( $offday ) );
	$onmonth = date( 'n', strtotime( $onmonth ) ); // make sure month is in numberical format
	$offmonth = date( 'n', strtotime( $offmonth ) );

	$todaysday = date ( 'w', current_time( 'timestamp' ) ); // what's the time, Mr. Wolf?
	$todaysdate = date( 'j', current_time( 'timestamp' ) );
	$todaysmonth = date( 'n', current_time( 'timestamp' ) );
	$currenttime = date( 'H:i:s', current_time( 'timestamp' ) );

	if( $type == 'annually' && ( empty( $ondate ) || empty( $offdate ) || empty( $onmonth ) || empty( $offmonth ) ) ) { // show content if not all required attributes set
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth < $offmonth ) { // months do not cross years
		if( $onmonth < $todaysmonth && $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off later
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on earlier
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth > $offmonth ) { // months cross years
		if( $onmonth < $todaysmonth || $offmonth > $todaysmonth ) { // today is in between show months
			$showit = 1;
		} elseif( $onmonth == $todaysmonth ) { // turns on this month and off next year
			if( $ondate < $todaysdate ) { // on date has already passed
				$showit = 1;
			} elseif( $ondate == $todaysdate && $ontime < $currenttime ) { // today is on date and on time has passed
				$showit = 1;
			}
		} elseif( $offmonth == $todaysmonth ) { // turns off this month and on last year
			if( $offdate > $todaysdate ) { // off date hasn't arrived yet
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth == $todaysmonth ) { // turns on and off this month
		if( $ondate < $todaysdate ) { // today is after on day
			if( $offdate > $todaysdate ) { // off day hasn't yet happened
				$showit = 1;
			} elseif( $offdate < $ondate ) { // doesn't turn off until next year
				$showit = 1;
			} elseif ( $offdate == $todaysdate && $offtime > $currenttime ) { // today is off date and off time hasn't yet passed
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate > $todaysdate) { // today is before on day
			if( $offdate < $ondate && $offdate > $todaysdate ) { // turns off later this month after being on for nearly a year
				$showit = 1;
			} elseif( $offdate == $todaysdate && $offtime > $currenttime ) { // turns off today after being on for nearly a year
				$showit = 1;
			} elseif ( $ondate == $offdate && $offdate != $todaysdate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
				$showit = 1;
			}
		} elseif( $ondate == $todaysdate ) { // today is on day
			if( $offdate > $todaysdate && $ontime < $currenttime ) { // turns off later this month and on time has passed
				$showit = 1;
			} elseif( $offdate < $todaydate && $ontime < $currenttime ) { // turns off next year and on time has passed
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime < $currenttime && $offtime > $currenttime ) { // turns on and off today and is between show times
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $ontime < $currenttime ) { // turns on for almost a full year, less a few hours (earlier today)
				$showit = 1;
			} elseif( $offdate == $todaysdate && $ontime > $offtime && $offtime > $currenttime ) { // turns on for almost a full year, less a few hours (later today)
				$showit = 1;
			}
		}
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate < $ondate ) { // turns on and off the same month (but not this month) and is on for most of the year
		$showit = 1;
	} elseif( $type == 'annually' && $onmonth == $offmonth && $onmonth != $todaysmonth && $offdate == $ondate && $offtime < $ontime ) { // turns on for almost a full year, less a few hours
		$showit = 1;
	}

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
		if( $onwday < $todaysday || $offwday > $todaysday ) { // today is between show days
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
