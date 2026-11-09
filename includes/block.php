<?php
/**
 * The Code runner block. It is rendered on the server, so the code is kept in the block's
 * attributes and never passes through the filters that change post text.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the block and its editor script.
 */
function onlinecompiler_register_block() {
	wp_register_script(
		'onlinecompiler-block-editor',
		ONLINECOMPILER_URL . 'blocks/code-runner/editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		ONLINECOMPILER_VERSION,
		true
	);
	wp_register_style(
		'onlinecompiler-block-editor',
		ONLINECOMPILER_URL . 'blocks/code-runner/editor.css',
		array(),
		ONLINECOMPILER_VERSION
	);

	$languages = array();
	foreach ( onlinecompiler_languages() as $id => $language ) {
		$languages[] = array(
			'value' => $id,
			'label' => $language[0],
		);
	}
	$settings = onlinecompiler_settings();
	wp_add_inline_script(
		'onlinecompiler-block-editor',
		'window.onlinecompilerBlock = ' . wp_json_encode(
			array(
				'languages'   => $languages,
				'defaultLang' => $settings['lang'],
				'hasKey'      => '' !== $settings['key'],
				'settingsUrl' => admin_url( 'options-general.php?page=onlinecompiler' ),
			)
		) . ';',
		'before'
	);
	wp_set_script_translations( 'onlinecompiler-block-editor', 'onlinecompiler-io-code-runner' );

	register_block_type(
		ONLINECOMPILER_DIR . 'blocks/code-runner',
		array( 'render_callback' => 'onlinecompiler_render_block' )
	);
}
add_action( 'init', 'onlinecompiler_register_block' );

/**
 * Renders the block on the page.
 *
 * @param array $attributes The block's attributes.
 * @return string
 */
function onlinecompiler_render_block( $attributes ) {
	$wrapper = get_block_wrapper_attributes();
	$classes = preg_match( '/class="([^"]*)"/', $wrapper, $match ) ? html_entity_decode( $match[1], ENT_QUOTES, 'UTF-8' ) : '';
	return onlinecompiler_render(
		array(
			'code'       => isset( $attributes['code'] ) ? $attributes['code'] : '',
			'stdin'      => isset( $attributes['stdin'] ) ? $attributes['stdin'] : '',
			'lang'       => isset( $attributes['lang'] ) ? $attributes['lang'] : '',
			'theme'      => isset( $attributes['theme'] ) ? $attributes['theme'] : '',
			'height'     => isset( $attributes['height'] ) ? $attributes['height'] : 0,
			'readonly'   => ! empty( $attributes['readOnly'] ),
			'langSelect' => isset( $attributes['langSelect'] ) ? $attributes['langSelect'] : '',
			'problem'    => isset( $attributes['problem'] ) ? $attributes['problem'] : '',
			'className'  => $classes,
		)
	);
}
