<?php
/**
 * Used to generate an admin for Boat Tracker
 *
 *
 * 
 * @category Admin
 * @author Skipperblogs <info@skipperblogs.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * BOAT_TRACKING_Admin class
 */
class BOAT_TRACKING_Admin
{
    /**
     * Singleton Instance
     * 
     * @var BOAT_TRACKING_Admin $_instance
     */
    private static $_instance = null;

    /**
     * Singleton
     * 
     * @static
     * 
     * @return BOAT_TRACKING_Admin
     */
    public static function init()
    {
        if (!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Instantiate the class
     */
    private function __construct()
    {
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
       // add_action('admin_enqueue_scripts', array('Boat_Tracking', 'enqueue_and_register'));

        /* add settings to plugin page */
        add_filter('plugin_action_links_' . plugin_basename(BTTRK_PLUGIN_FILE), array($this, 'plugin_action_links'));
    }

    /**
     * Admin init registers styles
     */
    public function admin_init() 
    {
        wp_register_style('boat_tracking_admin_stylesheet', plugins_url('style.css', BTTRK_PLUGIN_FILE),[],BTTRK_PLUGIN_VERSION);
    }

    /**
     * Add admin menu page when user in admin area
     */
    public function admin_menu()
    {
        $leaf = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMzkuOSAyMDQuNTciPjx0aXRsZT5Bc3NldCAxNjwvdGl0bGU+PGcgaWQ9IkxheWVyXzIiIGRhdGEtbmFtZT0iTGF5ZXIgMiI+PGcgaWQ9IkxheWVyXzEtMiIgZGF0YS1uYW1lPSJMYXllciAxIj48ZyBpZD0idno5TEdEIj48cGF0aCBkPSJNNzAsNDMuNzRjMTUuMTksMCwzMC4zOC4yMiw0NS41NiwxLDIuODUuMTUsNS43LjQ5LDguNTUuNjdhMS40LDEuNCwwLDAsMSwxLjQ0LDEuMTEsOS43Myw5LjczLDAsMCwxLTEuNjgsOUEyMy41MSwyMy41MSwwLDAsMSwxMTIsNjMuODNjLTcuMSwyLjQ2LTE0LjQ5LDMuMjMtMjEuOTMsMy43MS0xMS41My43NC0yMy4wNi41Ny0zNC41OS4zMWExMTguNDQsMTE4LjQ0LDAsMCwxLTIwLjMyLTJjLTUuNDYtMS4wOC0xMC44LTIuNi0xNS4yNC02LjIxLTIuMzgtMS45My00LjQzLTQuMTYtNS4yNy03LjIxYTEzLjA4LDEzLjA4LDAsMCwxLC4xMS02LjM3Yy4xNS0uNzMuNy0uNiwxLjE3LS42NCw3LS43MSwxNC0xLDIxLTEuMjRDNDgsNDMuODQsNTksNDMuNzYsNzAsNDMuNzRaIiBzdHlsZT0iZmlsbDojZmZmIi8+PHBhdGggZD0iTTYzLjM3LDgxLjUzQTEuMjEsMS4yMSwwLDAsMSw2NC4yMiw4M2MtLjIyLDIuNDMtLjM5LDQuODctLjU5LDcuMywwLC4zNywwLC43Ni0uMzksMS0xLjc4LDEuMjItMy41NiwyLjQ2LTUuNTIsMy42MSwxLjQxLTMuNTMsMy4xMS03LDEuNS0xMC45NUM1OC4xLDg1LjE0LDU3LDg2LjM0LDU1Ljg2LDg3LjZhMi4xMywyLjEzLDAsMCwxLTEuNzUuNzdjLTIuOSwwLTUuODEsMC04LjcxLjA4YTEuNjIsMS42MiwwLDAsMS0xLjU3LS44Yy0uOTUtMS40OC0xLjk1LTIuOTQtMi45NC00LjQtLjMtLjQ0LS41NS0xLTEuMjgtLjY0LTIuMzMsMS00LjY3LDEuODgtNywyLjgxLS40NS4xOC0uNjYsMC0uNDMtLjQxYTMuNDgsMy40OCwwLDAsMSwuNzEtLjgzYzEuMjItMS4zOSwyLjU2LTIuNywzLjYzLTQuMTlBNi42OSw2LjY5LDAsMCwxLDQxLDc3LjM2IiBzdHlsZT0iZmlsbDojZmZmIi8+PHBhdGggZD0iTTk3LjE5LDc2Ljk0YTE5LjI0LDE5LjI0LDAsMCwwLDIuMzMuNTYsNi4wNyw2LjA3LDAsMCwxLDQuMjIsMi42NGMxLjQyLDEuNzYsMywzLjQyLDQuNTcsNS4yN2E3LjY5LDcuNjksMCwwLDEtMi44OC0uODhjLTEuNDctLjUyLTIuOTItMS4wOC00LjMzLTEuNzNhMS4zMiwxLjMyLDAsMCwwLTIsLjYxYy0uOTIsMS40NS0yLDIuODMtMi44OSw0LjI3YTEuNDksMS40OSwwLDAsMS0xLjQ1Ljc1Yy0zLS4wNS02LS4wOC05LS4wN2ExLjg0LDEuODQsMCwwLDEtMS41MS0uNzJjLTEuMTEtMS4yNS0yLjI2LTIuNDctMy40NC0zLjc0LTEuNDcsNCwwLDcuNSwxLjc3LDEwLjkxbC0uMzQuMTktNS41Mi0zLjgzYS44Mi44MiwwLDAsMS0uMjctLjY4Yy0uMi0yLjYtLjM3LTUuMi0uNjEtNy44LS4wNy0uNzQuNC0uOTQuODktMS4xNyIgc3R5bGU9ImZpbGw6I2ZmZiIvPjxwYXRoIGQ9Ik01NS4zNSwxODguNzhjMC0uNTgtLjA1LTEuMTcsMC0xLjc1LDAtLjQyLS4xMi0xLjE1LjQ0LTEuMTNzLjMyLjcuMzEsMS4xM0M1NS4yNywxODcuMzgsNTUuODUsMTg4LjMsNTUuMzUsMTg4Ljc4WiIgc3R5bGU9ImZpbGw6I2ZmZiIvPjxwYXRoIGQ9Ik0xMjIuNTYsNzljLjY1LTQsLjEtNS40Mi0zLjM3LTcuNjJhMS4xNiwxLjE2LDAsMCwxLS41MS0xLjE3YzAtMS43NywwLTMuNTQtLjA4LTUuMzEsMC0uMzMuMS0uODktLjU1LS42N2E4Ljc3LDguNzcsMCwwLDAtMi44NSwxLjI5Yy0uNTYuNDctLjIxLDEuMjgtLjMyLDEuOTNzLS4yMiwxLjU4LS4zLDIuMzdjLTEuMDgsMTEuMDYtMi44MSwyMi00Ljg4LDMyLjkzLTEuOCw5LjQ0LTUuOCwxNy41OC0xMywyNC4wOS0uNjguNjEtLjkxLjYxLTEuMy0uMjEtMS4xLTIuMzQtMi4yOC00LjY1LTMuNDEtN2ExMS41MSwxMS41MSwwLDAsMC02LTUuNzljLTMuNzQtMS42MS03LjQ0LTMuMzItMTEuMTYtNS0uNDMtLjE5LS44NC0uNDEtMS4yNi0uNjIsMC0uMTEuMDUtLjIzLjA4LS4zNWg3LjI3QTMzLjgsMzMuOCwwLDAsMCw3NywxMDQuNzVhLjYuNiwwLDAsMC0uNzEtLjExYy0xLjIxLjkxLTIuNjEuNi0zLjkuNS0zLS4yMi02LjEyLjY4LTkuMDgtLjU3YS40My40MywwLDAsMC0uMzYuMDdBMzMuNjQsMzMuNjQsMCwwLDAsNTguNzMsMTA4aDcuNTZjLTQsMi04LDMuODgtMTIsNS42NGExMiwxMiwwLDAsMC02LjQ0LDYuMDdjLTEsMi4wNi0yLDQuMDctMyw2LjEtLjg3LDEuNzYtLjgzLDEuNzItMi4yMS40NGEzOS4xNiwzOS4xNiwwLDAsMS0xMS42LTIwYy0xLjE3LTQuODItMi05LjcyLTIuOC0xNC42MUMyNi44OCw4My44MiwyNS44OCw3NS45MywyNS4xNiw2OGMtLjE3LTItLjY5LTMuMTYtMi42My0zLjU4YS45NC45NCwwLDAsMS0uMjMtLjFjLS42Ny0uMzMtMS0uMTktMSwuNjIsMCwxLjczLDAsMy40NS0uMDYsNS4xOCwwLC41MiwwLC44OS0uNjQsMS4xNy0zLDEuMzQtNC4wNywzLjE4LTMuNjUsNi40czEsNi4yMywxLjQxLDkuMzZjLjcyLDUuNDUsMi4wOSwxMC44MiwxLjg0LDE2LjM5LS4xMywyLjgyLDEuNTQsNC41OSw0LjM2LDQuODQuNjEsMCwuODguMiwxLC44NWEzMy41OSwzMy41OSwwLDAsMS0uNjksMTJjLS44MSwzLjMyLTIuMjQsNi40Mi0zLjI3LDkuNjYtMi4zMiw3LjMzLTIuMiwxNC40OSwxLjIsMjEuNDkuMTUuMzEuMTYuODguNTkuODhzLjM5LS41Ni41Mi0uODlxLjktMi4yMywxLjgxLTQuNDVsLjMxLDBjLTEuMDksMTEuNjEsMi43MSwyMS40MiwxMC43NiwyOS45YTMyLjEzLDMyLjEzLDAsMCwwLDEuMTUtNy4xOCwxLjM4LDEuMzgsMCwwLDEsLjU3LDEsNDEuNjksNDEuNjksMCwwLDAsNy44NSwxNi4wNiw0My43LDQzLjcsMCwwLDAsNy40OCw3LjQ3Yy4yNS4yLjQ5LjU1Ljg2LjM5cy4xOC0uNTMuMjEtLjhxLjI2LTMsLjQ5LTUuOTFjLjI3LS41NywwLTEuMi4yMy0xLjc5cy4zNC0uMDYuNSwwYTExLjczLDExLjczLDAsMCwwLDEsMy44OGMyLjM2LDYuMTUsNi44LDEwLjQsMTIuNDUsMTMuNTNhLjgxLjgxLDAsMCwwLC45MiwwLDMxLjI3LDMxLjI3LDAsMCwwLDcuMzMtNS41LDI1LjQsMjUuNCwwLDAsMCw2LjUxLTEyLjMzYy4zLDMsLjYsNS45NS45Myw5LjE4YTQyLjYsNDIuNiwwLDAsMCwxMC41NC0xMS4xMiw0Ny43Myw0Ny43MywwLDAsMCw2LjEyLTEzLjg5LDM5LjQsMzkuNCwwLDAsMCwxLjM3LDdjOC04LjQ5LDExLjc5LTE4LjI2LDEwLjg4LTI5LjgyLjgsMS41NiwxLjQyLDMuMSwyLDQuNjQuMDkuMjEsMCwuNTUuMzMuNnMuNDQtLjMuNTctLjU2YTI1LjE2LDI1LjE2LDAsMCwwLDIuNzMtOWMuNjYtNi40OC0xLjE3LTEyLjQ5LTMuNDQtMTguNDNhMzIuMjUsMzIuMjUsMCwwLDEtMS45Mi0xNmMuMDgtLjYuMjUtLjg0LjkxLS45MmE0LjQxLDQuNDEsMCwwLDAsNC4xNC00LjY4LDI4LjY3LDI4LjY3LDAsMCwxLDAtNC41NEMxMjAuNSw5Mi4zOCwxMjEuNDcsODUuNjgsMTIyLjU2LDc5Wk03OC44NywxMjcuMTdhMTgsMTgsMCwwLDEtMTAuNDYsMS45QTE2LjMyLDE2LjMyLDAsMCwxLDYyLjg4LDEyOGMtMi4xMi0xLTQuMTUtMi4yLTYuMjUtMy4yNS0uNzktLjQtLjY4LS42OC0uMS0xLjE0Ljg5LS43LDEuNzctMS40MSwyLjYxLTIuMTdhMS4zNiwxLjM2LDAsMCwxLDEuNTktLjI4LDIxLDIxLDAsMCwwLDQsMS40MWM1LC44Nyw5Ljk0Ljc4LDE0LjY3LTEuNGExLjA1LDEuMDUsMCwwLDEsMS4yOC4wNmMxLjEzLDEsMi4zLDEuOTQsMy41NCwzQTUyLjQyLDUyLjQyLDAsMCwxLDc4Ljg3LDEyNy4xN1oiIHN0eWxlPSJmaWxsOiNmZmYiLz48cGF0aCBkPSJNMTM4Ljg2LDIwLjg1QTE2My40MiwxNjMuNDIsMCwwLDAsMTAxLjIzLDQuOTJhMTI0LjcsMTI0LjcsMCwwLDAtMjgtNC44NEM2Mi41Mi0uNDEsNTIuMSwxLjQ5LDQxLjgsNC4wOUExNTUuNDQsMTU1LjQ0LDAsMCwwLDE0LjkxLDEzLjdRNy42OSwxNywuODQsMjFjLS40Mi4yNC0uOTMuNDMtLjgzLDEuMTEuODcsNS41NywzLjMxLDEwLjA4LDguNSwxMi43OWEzLjE3LDMuMTcsMCwwLDAsMi40MS4yOWMyLjMyLS41Nyw0LjY2LTEuMDgsNy0xLjYyYTksOSwwLDAsMSwxLjU1LS4yMmMtLjU3LjEzLTEsLjY1LTEuNTQuNzRsLTYuMzEsMi41MWMtLjQ5LjE5LS45MS4zOS0uODIsMS4wNmE4LjE0LDguMTQsMCwwLDAsMiw0LjkzLDIuNjksMi42OSwwLDAsMCwyLjQ0LDFjNC40Mi0uNjQsOC44Ni0xLDEzLjMxLTEuMyw5LjMyLS41OCwxOC42NS0uOSwyOC0xLDE0LjA2LS4xOSwyOC4xMS0uMDksNDIuMTUuNDMsOC43NS4zMywxNy40OS43NywyNi4xNywxLjkzYTIuMjQsMi4yNCwwLDAsMCwxLjQ0LS4zM2MxLjgyLTEsMi4zMS0yLjg3LDIuNzgtNC43Mi4yNi0xLC4xNi0xLjgyLTEtMi4xNS0uNTMtLjYxLTEuMzgtLjQ0LTItLjgxaDBhMTcuMSwxNy4xLDAsMCwwLTQuMTUtMS43OCw0LjYxLDQuNjEsMCwwLDEtMS41Mi0uNSw1LjMsNS4zLDAsMCwxLDEuOC41YzEuNTYuNjksMy4zLjUsNC45LDFoMGEyNC43MywyNC43MywwLDAsMCwyLjUzLjUzLDIuNzMsMi43MywwLDAsMCwxLjQ2LS4yOGM1LjE2LTIuNDQsNy42My02Ljc5LDguNzQtMTIuMTVBMS42NSwxLjY1LDAsMCwwLDEzOC44NiwyMC44NVpNNzkuMjgsMzIuOTFjLTYuNSw2LjMyLTE2Ljc1LDQuNjctMjEuMTctMy40YTE2LjA5LDE2LjA5LDAsMCwxLTIuMDctOEM1Ni4yLDE1Ljc0LDU4LjQsMTEsNjMuNTIsOGM2LjEzLTMuNTcsMTMuMjUtMS43NSwxNy41NSw0LjIxQTE2LjQxLDE2LjQxLDAsMCwxLDc5LjI4LDMyLjkxWiIgc3R5bGU9ImZpbGw6I2ZmZiIvPjxwYXRoIGQ9Ik03Ny42OCwyMy44M2E0LjM5LDQuMzksMCwwLDAtMS40My0yLjQ2Yy0uMTguNC0uNDYuNy0uNDEuOTEuMjUuODctMS44OSwxLjQtLjMzLDIuNDJhLjQzLjQzLDAsMCwxLS4wOC4yMSwyLjMxLDIuMzEsMCwwLDEtMi4yLDEsMS40MSwxLjQxLDAsMCwxLTEuMzUtMS40Yy0uMS0xLS4yLTItLjIyLTNzLS40LTEuODcuMTItMi41OCwxLjQzLS4yMywyLjE2LS4zM2EuNzMuNzMsMCwwLDAsLjc0LS44MmMwLS41Mi0uMDgtLjk0LS42OC0xcy0xLS4wOS0xLjUxLS4xNWMtLjM0LDAtLjgyLjE0LS45My0uMzlzLjMyLS41My41Ny0uNzNhMi41LDIuNSwwLDAsMCwuMzktMy43NiwzLDMsMCwwLDAtNC4zLS4xNSwyLjU2LDIuNTYsMCwwLDAsLjMsNGMuMjUuMjIuNjQuMzYuNTEuNzZzLS41Ni4yOC0uODguMzJjLS41LjA2LTEsLjA5LTEuNS4xNHMtLjcyLjQ3LS43MiwxLC4yMy43OC43NC44MSwxLjEuMDgsMS42NC4xMmMuMzcsMCwuNjkuMTQuNjkuNTdhMjkuNywyOS43LDAsMCwxLS4zNiw1LjY2LDEuNjgsMS42OCwwLDAsMS0yLjQuOTFjLS43My0uMy0uODMtLjU3LS41NC0xLjQxYS43NC43NCwwLDAsMC0uMTItLjQ2Yy0uNC0uODEtLjgyLTEuNjEtMS4yNC0yLjQzLS41LjE3LS42NS42LS44NSwxLS43NCwxLjIzLS43MSwxLjQ0LjA3LDIuNWExLjEsMS4xLDAsMCwxLC4wOS40OSw0LDQsMCwwLDAsMi45LDMuMzJjMS4xNi40NSwyLjQ2LjU4LDMuNDIsMS41MS4yNi4yNi41My4xNS43Ny0uMDhBNS45LDUuOSwwLDAsMSw3My4yMywyOWMxLjkzLS41OSwzLjYzLTEuNDgsMy43NS0zLjg1LDAtLjE3LDAtLjQxLjIxLS4zN0M3OC4zOCwyNSw3Ny43NiwyNC4yMSw3Ny42OCwyMy44M1ptLTcuMzQtOS40MWExLjEsMS4xLDAsMCwxLTEuMjUtMS4xMWMwLS43OC41LTEuMDgsMS4xOS0xLjE3LjY4LjA3LDEuMjQuMzIsMS4yNCwxLjExQTEuMSwxLjEsMCwwLDEsNzAuMzQsMTQuNDJaIiBzdHlsZT0iZmlsbDojZmZmIi8+PC9nPjwvZz48L2c+PC9zdmc+';

        $admin = "manage_options";
        $author = "edit_posts";

        if (current_user_can($admin)) {
            $main_link = 'boat-tracker';
            $main_page = array($this, "settings_page");
        } else {
            $main_link = 'leaflet-shortcode-helper';
            $main_page = array($this, "shortcode_page");
        }

        add_menu_page("Boat Tracker", "Boat Tracker", $author, $main_link, $main_page, $leaf);
        add_submenu_page("boat-tracker", __('Settings', 'boat-tracker'), __('Settings', 'boat-tracker'), $admin, "boat-tracker", array($this, "settings_page"));
    }

    /**
     * Main settings page includes form inputs
     */
    public function settings_page()
    {
        wp_enqueue_style('boat_tracking_admin_stylesheet');

        $settings = BOAT_TRACKING_Plugin_Settings::init();
        $plugin_data = get_plugin_data(BTTRK_PLUGIN_FILE);
        include 'templates/settings.php';
    }

    /**
     * Add settings link to the plugin on Installed Plugins page
     * 
     * @return array
     */
    public function plugin_action_links($links)
    {
        $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=boat-tracker') ) .'">Settings</a>';
        return $links;
    }
}