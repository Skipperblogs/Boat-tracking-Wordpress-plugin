<?php
/**
 * Class for getting and setting db/default values
 * 
 * @category Admin
 * @author Skipperblogs <info@skipperblogs.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once BTTRK_PLUGIN_DIR . 'class.plugin-option.php';

// TODO: add option to reset just a single field

/**
 * Used to get and set values
 * 
 * Features:
 * * Add prefixes to db options
 * * built-in admin settings page method
 */
class BOAT_TRACKING_Plugin_Settings
{
    /**
     * Prefix for options, for unique db entries
     * 
     * @var string $prefix
     */
    public $prefix = 'boat_tracking_';
    
    /**
     * Singleton instance
     * 
     * @var BOAT_TRACKING_Plugin_Settings
     **/
    private static $_instance = null;

    /**
     * Default values and admin form information
     * Needs to be created within __construct
     * in order to use a function such as __()
     * 
     * @var array $options
     */
    public $options = array();

    /**
     * Singleton
     * 
     * @static
     * 
     * @return BOAT_TRACKING_Plugin_Settings
     */
    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Instantiate the class
     */
    private function __construct() 
    {


        $this->options = array(
            'sb_map_id' => array(
                'display_name'=>__('Map ID', 'boat-tracker'),
                'default'=>'',
                'type' => 'text',
                'helptext' => __('Retreive your Map ID from <a href="https://www.skipperblogs.com/dashboard/nav/map/share">https://www.skipperblogs.com/dashboard/nav/map/share</a>', 'boat-tracker')
            ),

        );

        foreach ($this->options as $name => $details) {
            $this->options[ $name ] = new BOAT_TRACKING_Plugin_Option($details);
        }
    }

    /**
     * Wrapper for WordPress get_options (adds prefix to default options)
     *
     * @param string $key                
     * 
     * @return varies
     */
    public function get($key) 
    {
        $default = $this->options[ $key ]->default;
        $key = $this->prefix . $key;
        return get_option($key, $default);
    }

    /**
     * Wrapper for WordPress update_option (adds prefix to default options)
     *
     * @param string $key   Unique db key
     * @param varies $value Value to insert
     * 
     * @return BOAT_TRACKING_Plugin_Settings
     */
    public function set ($key, $value) {
        $key = $this->prefix . $key;
        update_option($key, $value);
        return $this;
    }

    /**
     * Wrapper for WordPress delete_option (adds prefix to default options)
     *
     * @param string $key Unique db key
     * 
     * @return boolean
     */
    public function delete($key) 
    {
        $key = $this->prefix . $key;
        return delete_option($key);
    }

    /**
     * Delete all options
     *
     * @return BOAT_TRACKING_Plugin_Settings
     */
    public function reset()
    {
        foreach ($this->options as $name => $option) {
            if (
                !property_exists($option, 'noreset') ||
                $option->noreset != true
            ) {
                $this->delete($name);
            }
        }
        return $this;
    }
}
