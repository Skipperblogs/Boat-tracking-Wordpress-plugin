<?php
/**
 * Boat Tracker Class File
 * 
 * @category Admin
 * @author Skipperblogs <info@skipperblogs.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Boat Tracker Class
 */
class Boat_Tracking
{


    /**
     * Singleton Instance of Boat Tracking
     * 
     * @var Boat_Tracking
     **/
    private static $instance = null;

    /**
     * Singleton init Function
     * 
     * @static
     */
    public static function init() {
        if ( !self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Boat_Tracking Constructor
     */
    private function __construct() {
        $this->init_hooks();

        function mapShortCode($atts){

            wp_enqueue_script('boat_tracking_scripts',plugins_url('assets/mapshare-scripts.min.js',BTTRK_PLUGIN_FILE), ['jquery'], BTTRK_PLUGIN_VERSION, true);
            wp_enqueue_script('boat_tracking_map_init',plugins_url('assets/map-init.js',BTTRK_PLUGIN_FILE),['boat_tracking_scripts'], BTTRK_PLUGIN_VERSION,true);
            wp_enqueue_style('boat_tracking_map_styles',plugins_url('assets/mapshare-styles.min.css',BTTRK_PLUGIN_FILE),[], BTTRK_PLUGIN_VERSION);
            wp_add_inline_script('boat_tracking_scripts','var map_override=true;var assets_url="'.plugin_dir_url( __FILE__ ).'/assets/";','before');


            $atts['height'] = !isset($atts['height']) ? '250' : $atts['height'];
            $atts['width'] = !isset($atts['width']) ? '100%' : $atts['width'];

            $atts['height'] .= is_numeric($atts['height']) ? 'px' : '';
            $atts['width'] .= is_numeric($atts['width']) ? 'px' : '';
            $settings  = BOAT_TRACKING_Plugin_Settings::init();

            if(strlen($settings->get('sb_map_id')) > 10)
                $html = '<div class="boat-tracker skipperblogs-wp" data-map-id="'.wp_kses_post($settings->get('sb_map_id')).'" id="map-wrapper" style="height:'.htmlspecialchars($atts['height']).'; width:'.htmlspecialchars($atts['width']).'"><div id="map"></div></div>';
            else
                $html = '<p><i>The map cannot be loaded without map ID. Check your settings.</i></p>';
            return $html;
        }



        add_shortcode('boat-tracker','mapShortCode');
    }

    /**
     * Add actions and filters
     */
    private function init_hooks()
    {

        // BOAT_TRACKING_Plugin_Settings
        include_once BTTRK_PLUGIN_DIR . 'class.plugin-settings.php';

        // BOAT_TRACKING_Admin
        include_once BTTRK_PLUGIN_DIR . 'class.admin.php';
        
        // init admin
        BOAT_TRACKING_Admin::init();

        add_action( 'plugins_loaded', array('Boat_Tracking', 'load_text_domain' ));

        $settings = self::settings();

        //if ($settings->get('shortcode_in_excerpt')) {
        //    // allows maps in excerpts
        //    add_filter('the_excerpt', 'do_shortcode');
       // }
    }

    /**
     * Triggered when user uninstalls/removes plugin
     */
    public static function uninstall()
    {
        // remove settings in db
        // it needs to be included again because __construct 
        // won't need to execute
        $settings = self::settings();
        $settings->reset();

        // remove geocoder locations in db
        include_once BTTRK_PLUGIN_DIR . 'class.geocoder.php';
        Leaflet_Geocoder::remove_caches();
    }

    /**
     * Loads Translations
     */
    public static function load_text_domain()
    {
        load_plugin_textdomain( 'boat-tracker', false, dirname( plugin_basename( BTTRK_PLUGIN_FILE ) ) . '/languages/' );
    }


    /**
     * Get settings from BOAT_TRACKING_Plugin_Settings
     * @return BOAT_TRACKING_Plugin_Settings
     */
    public static function settings () {
        include_once BTTRK_PLUGIN_DIR . 'class.plugin-settings.php';
        return BOAT_TRACKING_Plugin_Settings::init();
    }

    /**
     * Parses liquid tags from a string
     *
     * @param string $str
     *
     * @return array|null
     */
    public function liquid ($str) {
        if (!is_string($str)) {
            return null;
        }
        $templateRegex = "/\{ *(.*?) *\}/";
        preg_match_all($templateRegex, $str, $matches);

        if (!$matches[1]) {
            return null;
        }

        $str = $matches[1][0];

        $tags = explode(' | ', $str);

        $original = array_shift($tags);

        if (!$tags) {
            return null;
        }

        $output = array();

        foreach ($tags as $tag) {
            $tagParts = explode(': ', $tag);
            $tagName = array_shift($tagParts);
            $tagValue = implode(': ', $tagParts) || true;

            $output[$tagName] = $tagValue;
        }

        // preserve the original
        $output['original'] = $original;

        return $output;
    }

    /**
     * Renders a json-like string, removing quotes for values
     *
     * allows JavaScript variables to be added directly
     *
     * @return string
     */
    public function rawDict ($arr) {
        $obj = '{';

        foreach ($arr as $key=>$val) {
            $obj .= "\"$key\": $val,";
        }

        $obj .= '}';

        return $obj;
    }

    /**
     * Filter all floats to remove commas, force decimals, and validate float
     * see: https://wordpress.org/support/topic/all-maps-are-gone/page/3/#post-14625548
     */
    public function filter_float ($flt) {
        // make sure the value actually is a float
        $out = filter_var($flt, FILTER_VALIDATE_FLOAT);
        
        // some locales seem to force commas
        $out = str_replace(',', '.', $out);
        
        return $out;
    }

    /**
     * Bounds are given as "50, -114; 52, -112"
     * Converted to 2d-array: [[50, -114], [52, -112]]
     */
    public function convert_bounds_str_to_arr ($bounds) {
        if (isset($bounds)) {
            try {
                // explode by semi-colons and commas
                $arr = preg_split("[;|,]", $bounds);

                return array(
                    array(
                        $this->filter_float($arr[0]), 
                        $this->filter_float($arr[1])
                    ),
                    array(
                        $this->filter_float($arr[2]), 
                        $this->filter_float($arr[3])
                    )
                );
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }
}
