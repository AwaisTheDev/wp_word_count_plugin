<?php
/**
 * Plugin Name:       My Plugin Playground
 * Plugin URI:        https://mdawais.com
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Muhammad Awais
 * Author URI:        https: //mdawais.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . "includes/word-count.php";
require_once plugin_dir_path(__FILE__) . "includes/custom-widget.php";

new wordCountPlugin();
new My_Widget();
