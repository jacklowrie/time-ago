<?php
//uninstall Time ago

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

delete_option('time_ago_options');
