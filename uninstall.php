<?php
// this is the uninstall handler
// include unregister_setting, delete_option, and other uninstall behavior here

if (!defined('WP_UNINSTALL_PLUGIN'))
{
    die;
}
include( plugin_dir_path(__FILE__) . 'wp-plugin.php');
$options = 'nlpcaptcha_options';
unregister_setting("${name}_group", $name);
delete_option($options);



?>