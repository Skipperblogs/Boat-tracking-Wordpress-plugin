<?php
/** 
 * BOAT_TRACKING_Plugin_Option
 * 
 * Store values; render widgets
 * 
 *
 * 
 * @category Shortcode
 * @author Skipperblogs <info@skipperblogs.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * BOAT_TRACKING_Plugin_Option
 */
class BOAT_TRACKING_Plugin_Option
{
    /**
     * Default Value
     * 
     * @var varies $default
     */
    public $default = '';
    
    /**
     * Input type ex: ('text', 'select', 'checkbox')
     * 
     * @var string $type 
     */
    public $type;
    
    /**
     * Optional used for select; maybe checkbox/radio
     * 
     * @var array $options
     */
    public $options = array();

    /**
     * Optional used for label under input
     * 
     * @var string $helptext
     */
    public $helptext = '';

    /**
     * All properties that we will be setting
     */
    public $display_name = '';
    public $min = 0;
    public $max = 0;
    public $step = 0;

    /**
     * Instantiate class
     * 
     * @param array $details A list of options
     */
    function __construct($details = array())
    {
        if (!$details) {
            // just an empty db entry (for now)
            // nothing to store, nothing to render
            return;
        }

        $option_filter = array(
            'display_name'     =>     FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'default'          =>     null,
            'type'             =>     FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'min'              =>     "",
            'max'              =>     "",
            'step'             =>     "",
            'options'          =>     array(
                'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'flags'  => FILTER_FORCE_ARRAY
            ),
            'helptext'         =>     null
        );

        // get matching keys only
        $details = array_intersect_key($details, $option_filter);

        // apply filter
        $details = filter_var_array($details, $option_filter);

        foreach ($details as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Renders a widget
     * 
     * @param string $name  widget name
     * @param varies $value widget value
     * 
     * @return HTML
     */
    function BTTRK_widget ($name, $value)
    {
        switch ($this->type) {
        case 'text':
            ?>
        <input 
            class="full-width" 
            name="<?php echo wp_kses_post($name); ?>"
            type="<?php echo wp_kses_post($this->type); ?>"
            id="<?php echo wp_kses_post($name); ?>"
            value="<?php echo wp_kses_post(htmlspecialchars($value)); ?>"
            />
            <?php
            break;

        
        case 'number':
            ?>
        <input 
            class="full-width" 
            min="<?php echo isset($this->min) ? wp_kses_post($this->min) : ""; ?>"
            max="<?php echo isset($this->max) ? wp_kses_post($this->max) : ""; ?>"
            step="<?php echo isset($this->step) ? wp_kses_post($this->step) : "any"; ?>"
            name="<?php echo wp_kses_post($name); ?>"
            type="<?php echo wp_kses_post($this->type); ?>"
            id="<?php echo wp_kses_post($name); ?>"
            value="<?php echo wp_kses_post(htmlspecialchars($value)); ?>"
            />
            <?php
            break;
            
        case 'textarea':
            ?>

        <textarea 
            id="<?php echo wp_kses_post($name); ?>"
            class="full-width" 
            name="<?php echo wp_kses_post($name); ?>"><?php echo wp_kses_post(htmlspecialchars($value)); ?></textarea>

            <?php
            break;

        case 'checkbox':
            ?>

        <input 
            class="checkbox" 
            name="<?php echo wp_kses_post($name); ?>"
            type="checkbox" 
            id="<?php echo wp_kses_post($name); ?>"
            <?php if ($value) echo ' checked="checked"' ?> 
            />
            <?php
            break;

        case 'select':
            ?>
        <select id="<?php echo wp_kses_post($name); ?>"
            name="<?php echo wp_kses_post($name); ?>"
            class="full-width">
        <?php
        foreach ($this->options as $o => $n) {
        ?>
            <option value="<?php echo wp_kses_post($o); ?>"<?php if ($value == $o) echo ' selected' ?>>
                <?php echo wp_kses_post($n); ?>
            </option>
        <?php
        }
        ?>
        </select>
                <?php
            break;
        default:
            ?>
        <div>No option type chosen for <?php echo wp_kses_post($name); ?> with value <?php echo wp_kses_post(htmlspecialchars($value)); ?></div>
            <?php
            break;
        }
    }
}