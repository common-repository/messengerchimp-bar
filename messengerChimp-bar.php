<?php
/*
Plugin Name: MessengerChimp Bar
Plugin URI: https://www.mindiq.in/
Description: Send Newsletters directly to User's Facebook Messenger
Author: Deven Bhooshan
Version: 1.1
Author URI: https://www.mindiq.in/

*/


add_action('wp_enqueue_scripts', 'mindiq_load_jssdk', 1);
add_action('wp_footer', 'mindiq_headercode', 90);
add_action('admin_init', 'mindiq_admin_init');
add_action('admin_menu', 'mindiq_plugin_menu');


function mindiq_admin_init()
{
    register_setting('mc_messenger_chimp_options', 'mindiq_page_id');
    register_setting('mc_messenger_chimp_options', 'mindiq_text');
    register_setting('mc_messenger_chimp_options', 'mindiq_color');
    register_setting('mc_messenger_chimp_options', 'mindiq_location');
}

function mindiq_load_jssdk()
{
    echo '
	<script>window.fbAsyncInit = function () {
	    FB.init({appId: \'897548893700849\', xfbml: !0, version: \'v2.6\'})
	}, function (e, n, t) {
	    var o, c = e.getElementsByTagName(n)[0];
	    e.getElementById(t) || (o = e.createElement(n), o.id = t, o.src = \'//connect.facebook.net/en_US/sdk.js\', c.parentNode.insertBefore(o, c))
	}(document, \'script\', \'facebook-jssdk\')</script>
	';
    wp_enqueue_style("mindiq-top-bar", plugins_url('/style.css', __FILE__));
}

function mindiq_plugin_menu()
{
    add_options_page('MessengerChimp', 'MessengerChimp', 'manage_options', 'mc_messenger_chimp_options', 'messenger_chimp_plugin_options');
}

function messenger_chimp_plugin_options()
{
    echo '<div class="wrap">'; ?>
    <h2>MessengerChimp Bar</h2>
    <p>You need to have a <a target="_blank" href="https://www.mindiq.in/">MindIQ</a> account to use this plugin. Just
        insert your Facebook Page ID here and then you are done.</p>
    <form method="post" action="options.php">
        <?php settings_fields('mc_messenger_chimp_options'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Facebook page id</th>
                <td><input type="text" name="mindiq_page_id" placeholder="198070140626064"
                           value="<?php echo get_option('mindiq_page_id'); ?>"/></td>
                <th scope="row">Bar location</th>
                <td>
                    <?php
                    $tab_location_default = 'Bottom';

                    $tab_location = (get_option('mindiq_location') == FALSE) ? $tab_location_default : get_option('mindiq_location');

                    ?>
                    <input type="radio" name="mindiq_location"
                           value="Top" <?php echo $tab_location == "Top" ? "checked" : "" ?> > Top<br>
                    <input type="radio" name="mindiq_location"
                           value="Bottom" <?php echo $tab_location == "Bottom" ? "checked" : "" ?> > Bottom<br>
                </td>
                </th>
            </tr>
            <tr valign="top">
                <th scope="row">Enter your Text</th>
                <td><textarea name="mindiq_text" rows="10" cols="50"
                              placeholder="Get Latest Updates directly on Facebook Messenger by clicking here"><?php echo get_option('mindiq_text'); ?></textarea>
                </td>
                <!--            <input type="text" name="mindiq_text"  placeholder="Get Latest Updates directly on Facebook Messenger by clicking here" value="-->
                <?php //echo get_option('mindiq_text');
                ?><!--" /></td>-->
                <th scope="row">Tab Color</th>
                <td><input type="text" name="mindiq_color" placeholder="#ffcc00"
                           value="<?php echo get_option('mindiq_color'); ?>"/></td>


            </tr>
        </table>

        <p class="submit"><input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>"/></p>
    </form>
    <br/><br/>

    <h2>An example website</h2>
    <img width="100%" height="100%" src="http://i.imgur.com/odPSqUH.png">


    <?php
    echo '</div>';
}


function mindiq_headercode()
{
    $form_action = apply_filters('mctb_form_action', null);

    ?>
    <!-- css3 hello bar -->
    <?php

    $tab_location_default = 'Bottom';

    $tab_location = (get_option('mindiq_location') == FALSE) ? $tab_location_default : get_option('mindiq_location');
    if ($tab_location == 'Top') {
        $bar_class = "mindiq-bar-top";
        $bar_switch_class = "mindiq-bar-switch-top";
    } else {
        $bar_class = "mindiq-bar-bottom";
        $bar_switch_class = "mindiq-bar-switch-bottom";
    }
    ?>


    echo '
    <script>
        var hidden = true;
        var hideChar = "▲";
        var showChar = "▼";

        var tab_location = '<?php echo $tab_location ?>';
        if (tab_location === 'Top') {
            hideChar = "▲";
            showChar = "▼";
        } else {
            hideChar = "▼";
            showChar = "▲";
        }

        function toggleBar() {

            if (hidden) {
                document.getElementById("mindiq-bar").style.display = "none";
                document.getElementById("mindiq-bar-switch").innerHTML = showChar;
                hidden = false;
            } else {
                document.getElementById("mindiq-bar").style.display = "block";
                document.getElementById("mindiq-bar-switch").innerHTML = hideChar;
                hidden = true;
            }

        }

        setTimeout(function () {
            document.getElementById('mindiq-bar-switch').innerHTML = hideChar;
        }, 500)

    </script>';


    <!-- mindiq messengerChimp bar -->
    <div class="mindiq-bar-outer <?php echo $bar_class ?> ">
        <div id="mindiq-bar" class="mindiq-bar-style" style="background: <?php
        $myOption_1 = '#eeeeee';
        $myOption_2 = (get_option('mindiq_color') == FALSE) ? $myOption_1 : get_option('mindiq_color');
        echo $myOption_2;
        ?>">
            <div style="padding-top: 5px; padding-bottom: 5px; min-height: 33px; text-align: center;">
                <span>
                    <?php
                    $myOption_def = 'Get Latest Updates directly on Facebook Messenger by clicking here';

                    $myOption = (get_option('mindiq_text') == FALSE) ? $myOption_def : get_option('mindiq_text');
                    echo $myOption;

                    ?>
                </span>
                <span
                    style="vertical-align: middle;"
                    class='fb-send-to-messenger' color='blue' data-ref='<?php echo get_option('mindiq_page_id'); ?>'
                    messenger_app_id='897548893700849'
                    page_id='<?php echo get_option('mindiq_page_id'); ?>' size='large'>
                </span>
            </div>
        </div>

        <!-- style="vertical-align: middle;" -->

        <div id="mindiq-bar-switch-outer"
             class="<?php echo $bar_switch_class ?>"
             style="background: <?php $myOption_2 = (get_option('mindiq_color') == FALSE) ? '#eeeeee' : get_option('mindiq_color');
             echo $myOption_2; ?>"
             onclick="toggleBar()">
            <span id="mindiq-bar-switch" style="float: right; padding: 5px 15px;">
        </span>
        </div>
    </div>


    </div>
    <?php
}