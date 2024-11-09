<?php

/**
 * Plugin Name: Boat Tracker
 * Description: Track your boat around the globe with Iridium, AIS, InReach, PredictWind, SPOT, Startlink, etc, and display an interactive map on your website.
 * Author: Skipperblgs
 * Author URI: https://www.skipperblogs.com/
 * Text Domain: boat-tracker
 * Domain Path: /languages/
 * Version: 1.0.0
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit();
}

define('BTTRK_PLUGIN_VERSION', '3.4.1');
define('BTTRK_PLUGIN_FILE', __FILE__);
define('BTTRK_PLUGIN_DIR', plugin_dir_path(__FILE__));

// import main class
require_once BTTRK_PLUGIN_DIR . 'class.boat-tracker.php';

// uninstall hook
register_uninstall_hook(__FILE__, ['Boat_Tracking', 'uninstall']);

add_action('init', ['Boat_Tracking', 'init']);
