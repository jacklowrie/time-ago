<?php // time ago class


if ( !defined('ABSPATH') ) exit;


class Time_Ago {
  public $settings;

  public function __construct() {
    $this->settings = new Time_Ago_Settings(); // add settings
    // add 'format date' action
    add_action( 'get_the_date', array ($this, 'time_ago_format_date'), 10, 3 );
  }


  // the meat of the plugin. Reformat the post date
  public function time_ago_format_date( $the_date, $d, $post ) {
    // Get the post id
    if ( is_int( $post) ) {
      $post_id = $post;
    } else {
      $post_id = $post->ID;
    }

    // get the format setting (user's choice)
    $choice = 'default';
    $options = get_option( 'time_ago_options', $this->settings->time_ago_default_settings() );
    $choice = $options['time_ago_display_type'];

    $date_string = '';
    switch ($choice) {

      case 'default':
        // subtract the current post date from the current time
        $date_string = $this->time_ago_default_format( $post_id );
        break;

      case 'no_minutes':
        $date_string = 'no_mins ';
        break;

      case 'no_hours':
        $date_string = 'no_hours ';
        break;

      case 'no_days':
        $date_string = 'no_days ';
        break;

      case 'no_weeks':
        $date_string = 'no_weeks ';
        break;

      case 'no_months':
        $date_string = 'no_months ';
        break;

      default:
        return 'oops! switch went to default';
        break;
    }

    // make the string prettier
    $date_string = $this->time_ago_prettify( $date_string );
    return $date_string;
  }

  public function time_ago_default_format( $post_id ) {
    $datestring = human_time_diff( get_the_time( 'U', $post_id ), current_time('timestamp') );
    return $datestring;
  }

  public function time_ago_prettify( $datestring ) {

    $datestring .= ' ago';
    // makes 'minute' a full word. min -> minute, mins -> minutes
    return str_replace( 'min', 'minute', $datestring );
  }
} // end of class
