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

    $date_string = $this->time_ago_default_format( $post_id );
    $date_string = $this->time_ago_prettify( $date_string ); // make the string prettier
    switch ($choice) {

      case 'default':
        break;

      case 'no_minutes':
        $date_string = $this->time_ago_no_mins_format( $date_string );
        break;

      case 'no_hours':
        $date_string = $this->time_ago_no_hours_format( $date_string );
        break;

      case 'no_days':
        $date_string = $this->time_ago_no_days_format( $date_string );
        break;

      case 'no_weeks':
        $date_string = $this->time_ago_no_weeks_format( $date_string );
        break;

      case 'no_months':
        $date_string = $this->time_ago_no_months_format( $date_string );
        break;

      default:
        $date_string = $the_date;
        break;
    }

    return $date_string;
  }

  public function time_ago_default_format( $post_id ) {
    // subtract the current post date from the current time
    $datestring = human_time_diff( get_the_time( 'U', $post_id ), current_time('timestamp') );
    return $datestring;
  }

  public function time_ago_no_mins_format( $datestring ) {
    if ( strpos( $datestring, 'min' ) !== false ) {
      $datestring = "less than an hour ago";
    }
    return $datestring;
  }

  public function time_ago_no_hours_format( $datestring ) {
    if ( strpos( $datestring, 'hour' ) !== false ||
         strpos( $datestring, 'min' ) !== false) {
      $datestring = "today";
    }
    return $datestring;
  }

  public function time_ago_no_days_format( $datestring ) {
    if ( strpos( $datestring, 'hour' ) !== false ||
         strpos( $datestring, 'min' ) !== false ||
         strpos( $datestring, 'day' ) !== false) {
      $datestring = "this week";
    }
    return $datestring;
  }

  public function time_ago_no_weeks_format( $datestring ) {
    if ( strpos( $datestring, 'hour' ) !== false || strpos( $datestring, 'min' ) !== false || strpos( $datestring, 'day' ) !== false || strpos( $datestring, 'week' ) !== false) {
      $datestring = "this month";
    }
    return $datestring;
  }

  public function time_ago_no_months_format( $datestring ) {
    if ( strpos( $datestring, 'hour' ) !== false ||
         strpos( $datestring, 'min' ) !== false ||
         strpos( $datestring, 'day' ) !== false ||
         strpos( $datestring, 'week' ) !== false ||
         strpos( $datestring, 'month' ) !== false ) {
      $datestring = "this year";
    }
    return $datestring;
  }

  public function time_ago_prettify( $datestring ) {

    $datestring .= ' ago';
    // makes 'minute' a full word. min -> minute, mins -> minutes
    return str_replace( 'min', 'minute', $datestring );
  }
}
