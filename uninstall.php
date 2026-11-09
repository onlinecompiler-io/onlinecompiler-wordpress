<?php
/**
 * Removes the plugin's settings when it is deleted from the Plugins screen.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'onlinecompiler_settings' );
