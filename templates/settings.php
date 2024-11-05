<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Settings View
 * 
 *
 * 
 * @category Admin
 * @author Skipperblogs <info@skipperblogs.com>
 */

$title = $plugin_data['Name'];
$description = __('A plugin for adding live tracking map from Skipperblogs.com.', 'boat-tracker');
$version = $plugin_data['Version'];
?>
<div class="wrap">

<h1><?php echo wp_kses_post($title); ?> <small>version: <?php echo wp_kses_post($version); ?></small></h1>

<?php
    /** START FORM SUBMISSION */

    // validate nonce!
    define('BTTRK_NONCE_NAMENAME', 'boat-tracker-nonce');
    define('BTTRK_NONCE_NAMEACTION', 'boat-tracker-action');

    function BTTRL_check_nonce () {
        $verified = (
            isset($_POST[BTTRK_NONCE_NAMENAME]) &&
            check_admin_referer(BTTRK_NONCE_NAMEACTION, BTTRK_NONCE_NAMENAME)
        );

        if (!$verified) {
            // side-effects can be fun?
            ?>
            <div class="notice notice-error is-dismissible">
                <p>Sorry, your nonce did not verify</p>
            </div>
            <?php
        }

        return $verified;
    }

    if (isset($_POST['submit']) && BTTRL_check_nonce() && check_admin_referer(BTTRK_NONCE_NAMEACTION, BTTRK_NONCE_NAMENAME)) {
        /* copy and overwrite $post for checkboxes */


        foreach ($settings->options as $name => $option) {
            if (!$option->type) continue;

            $value = trim( stripslashes( sanitize_text_field($_POST[$name])) );
            $settings->set($name, $value);
        }
?>
<div class="notice notice-success is-dismissible">
    <p>Options Updated!</p>
</div>
<?php
    } elseif (isset($_POST['reset']) && BTTRL_check_nonce() && check_admin_referer(BTTRK_NONCE_NAMEACTION, BTTRK_NONCE_NAMENAME)) {
        $settings->reset();
?>
<div class="notice notice-success is-dismissible">
    <p>Options have been reset to default values!</p>
</div>
<?php
    }
    /** END FORM SUBMISSION */

?>
<div class="settings-header">
    <div class="left">
    <p>
        Track your boat around the world with Skipperblogs.com and display an interactive map on your website.
        Use AIS, Iridium, our mobile app, Garmin InReach, SPOT, E-mail and other to live report...
        Just check out <a href="https://www.skipperblogs.com/features?source=wp" target="_blank">https://www.skipperblogs.com/features</a> .
    </p>
    </div>
    <div class="right">
        <img src="<?php echo wp_kses_post(plugin_dir_url( __DIR__ ) . 'assets/settings-header.jpg') ; ?>" />
    </div>
</div>
<h3>Found an issue?</h3>
<p>Send an email to<b>: <a href="mailto:support@skipperblogs.com" target="_blank">support@skipperblogs.com</a></p>
    <div class="container">
        <h2>Settings</h2>
        <hr>
    </div>
<div id="settings">
    <div class="form wrap">
        <form method="post">
            <div class="settings">
                <?php wp_nonce_field(BTTRK_NONCE_NAMEACTION, BTTRK_NONCE_NAMENAME); ?>

                <?php
                foreach ($settings->options as $name => $option) {
                    if (!$option->type) continue;
                ?>
                <div class="container">
                    <label>
                        <span class="label"><?php echo wp_kses_post($option->display_name); ?></span>
                        <span class="input-group">
                        <?php
                        $option->BTTRK_widget($name, $settings->get($name));
                        ?>
                        </span>
                    </label>

                    <?php
                    if ($option->helptext) {
                    ?>
                    <div class="helptext">
                        <p class="description"><?php echo wp_kses_post($option->helptext); ?></p>
                    </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
                <div class="submit">
                    <input type="submit"
                        name="submit"
                        id="submit"
                        class="button button-primary"
                        value="Save Changes">
                    <input type="submit"
                        name="reset"
                        id="reset"
                        class="button button-secondary"
                        value="Reset to Defaults">
                </div>
            </div>
        </form>
    </div>
    <div class="instructions wrap">
        <h1>Installation steps</h1>
        <div class="card">
            <h2 class="title">1. Create your Skipperblogs account.</h2>
            <p>
                This plugins work in combination with an active Skipperblogs account. If you don't have one, register here <a href="https://www.skipperblogs.com/register?source=wp" target="_blank">https://www.skipperblogs.com/register</a>  It's Free !
                <br>
                Once registered, you can <a target="_blank" href="https://www.skipperblogs.com/dashboard/nav/tracking">enable the tracking</a>.
            </p>
        </div>
        <div class="card">
            <h2 class="title">2. Copy your Map ID</h2>
            <p>
                Copy our Map ID from the Skipperblogs' dashboard <a href="https://www.skipperblogs.com/dashboard/nav/map/share?source=wp" target="_blank">map share</a> and paste it in this settings page.<br>
            </p>
        </div>
        <div class="card">
            <h2 class="title">3. Add the map in your content</h2>
            <p>
                Add the following shortcode in your Wordpress content where you want to display the map. <br>
                <code>[boat-tracker]</code>
            </p>
        </div>
        <div class="card">
            <h2 class="title">4. Map customization</h2>
            <p>
                Customize the map background, boat icon, track color and style directly from your Skipperblogs account in the  <a href="https://www.skipperblogs.com/dashboard/map-editor?source=wp" target="_blank">map editor</a>
            </p>
            <p>
                To change the map dimension on Wordpress, use the following shortcode parameters<br>
                <code>[boat-tracker height="250" width="100%"]</code>
            </p>
        </div>
    </div>
</div>
