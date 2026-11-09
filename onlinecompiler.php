<?php
/**
 * Plugin Name:       OnlineCompiler.io Code Runner: Online Compiler and Runnable Code Blocks
 * Plugin URI:        https://github.com/onlinecompiler-io/onlinecompiler-wordpress
 * Description:       An online compiler for your posts: runnable code blocks readers edit and run in Python, C++, Java, JavaScript, SQL and 46 languages in all. Add the Code runner block or the [onlinecompiler] shortcode.
 * Version:           0.1.0
 * Requires at least: 6.3
 * Requires PHP:      7.4
 * Author:            OnlineCompiler.io
 * Author URI:        https://onlinecompiler.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       onlinecompiler-io-code-runner
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ONLINECOMPILER_VERSION', '0.1.0' );
define( 'ONLINECOMPILER_DIR', plugin_dir_path( __FILE__ ) );
define( 'ONLINECOMPILER_URL', plugin_dir_url( __FILE__ ) );

// The widget is served by OnlineCompiler.io, so every site runs the current version.
define( 'ONLINECOMPILER_WIDGET_URL', 'https://onlinecompiler.io/widget.js' );

require_once ONLINECOMPILER_DIR . 'includes/languages.php';
require_once ONLINECOMPILER_DIR . 'includes/settings.php';
require_once ONLINECOMPILER_DIR . 'includes/render.php';
require_once ONLINECOMPILER_DIR . 'includes/shortcode.php';
require_once ONLINECOMPILER_DIR . 'includes/block.php';

/**
 * Registers the widget script. It is only enqueued on pages that show a code runner.
 */
function onlinecompiler_register_widget_script() {
	wp_register_script(
		'onlinecompiler-widget',
		ONLINECOMPILER_WIDGET_URL,
		array(),
		ONLINECOMPILER_VERSION, // Only a cache key: OnlineCompiler.io always serves its current widget.
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'init', 'onlinecompiler_register_widget_script' );
