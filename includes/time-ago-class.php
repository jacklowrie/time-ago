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
  public function time_ago_format_date($the_date, $d, $post) {
    // Get the post id
    if ( is_int( $post) ) {
      $post_id = $post;
    } else {
      $post_id = $post->ID;
    }
    // subtract the current post date from the current time
    $date_string = human_time_diff( get_the_time( 'U', $post_id ) , current_time('timestamp') ) . ' ago';
    // make the string prettier
    $date_string = str_replace('min', 'minute', $date_string);

    return $date_string;
  } // end of time_ago_date_format
} // end of class
