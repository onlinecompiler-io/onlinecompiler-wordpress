<?php
/**
 * The HTML of one code runner, shared by the block and the shortcode.
 *
 * The widget (https://onlinecompiler.io/docs/widget) turns a div with class "runcode" into an
 * editor: its code is the div's <pre>, the program's input its <pre data-stdin>.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Escapes text for a <pre> the widget reads back with textContent.
 *
 * Every <, >, & and quote is encoded (an &amp; already in the code stays as written), and so are
 * [ and ], so WordPress never runs a shortcode that happens to appear in the code.
 *
 * @param string $text The code or input.
 * @return string
 */
function onlinecompiler_escape_text( $text ) {
	$html = htmlspecialchars( (string) $text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', true );
	return str_replace( array( '[', ']' ), array( '&#91;', '&#93;' ), $html );
}

/**
 * Renders a code runner.
 *
 * @param array $args {
 *     What the author set. Anything left out comes from Settings, OnlineCompiler.io.
 *
 *     @type string $code       The starting code.
 *     @type string $stdin      The starting standard input.
 *     @type string $lang       A compiler id, or a short name such as "python".
 *     @type string $theme      auto, light or dark.
 *     @type int    $height     The editor's height in pixels; 0 to fit the code.
 *     @type bool   $readonly   Stop readers editing the code.
 *     @type string $langSelect "true" or "false" to show or hide the language menu; '' to let the widget decide.
 *     @type string $problem    A stored problem's id: the widget shows that problem instead of code.
 *     @type string $locale     pt or es for the widget's buttons and messages; '' for English.
 *     @type string $className  Extra classes for the wrapper.
 * }
 * @return string HTML.
 */
function onlinecompiler_render( $args ) {
	$settings = onlinecompiler_settings();
	$args     = wp_parse_args(
		$args,
		array(
			'code'       => '',
			'stdin'      => '',
			'lang'       => '',
			'theme'      => '',
			'height'     => 0,
			'readonly'   => false,
			'langSelect' => '',
			'problem'    => '',
			'locale'     => null,
			'className'  => '',
		)
	);

	$code    = (string) $args['code'];
	$stdin   = (string) $args['stdin'];
	$problem = trim( (string) $args['problem'] );

	if ( '' === $settings['key'] ) {
		// Without a key the widget can't run anything: readers still see the code, and an
		// administrator sees why it doesn't run.
		$html = '' !== $code ? '<pre class="onlinecompiler-code">' . onlinecompiler_escape_text( $code ) . '</pre>' : '';
		if ( current_user_can( 'manage_options' ) ) {
			$html .= sprintf(
				'<p class="onlinecompiler-notice"><strong>%1$s</strong> <a href="%2$s">%3$s</a></p>',
				esc_html__( 'This code runner needs a widget key.', 'onlinecompiler' ),
				esc_url( admin_url( 'options-general.php?page=onlinecompiler' ) ),
				esc_html__( 'Add it in Settings, OnlineCompiler.io.', 'onlinecompiler' )
			);
		}
		return $html;
	}

	$lang   = onlinecompiler_resolve_language( $args['lang'] );
	$lang   = '' !== $lang ? $lang : $settings['lang'];
	$theme  = array_key_exists( (string) $args['theme'], onlinecompiler_themes() ) ? (string) $args['theme'] : $settings['theme'];
	$locale = null === $args['locale'] ? $settings['locale'] : (string) $args['locale'];
	$locale = array_key_exists( $locale, onlinecompiler_locales() ) ? $locale : '';
	$height = absint( $args['height'] );

	$attributes = array(
		'class'    => trim( 'runcode onlinecompiler-runner ' . $args['className'] ),
		'data-key' => $settings['key'],
	);
	if ( '' !== $problem ) {
		// The problem decides the languages, the starting code and the tests.
		$attributes['data-problem'] = $problem;
	} else {
		$attributes['data-lang'] = $lang;
	}
	$attributes['data-theme'] = $theme;
	if ( $height > 0 ) {
		$attributes['data-height'] = (string) $height;
	}
	if ( $args['readonly'] ) {
		$attributes['data-readonly'] = '1';
	}
	if ( 'true' === $args['langSelect'] || 'false' === $args['langSelect'] ) {
		$attributes['data-lang-select'] = $args['langSelect'];
	}
	if ( '' !== $locale ) {
		$attributes['data-locale'] = $locale;
	}

	$html = '<div';
	foreach ( $attributes as $name => $value ) {
		$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
	}
	$html .= '>';
	if ( '' === $problem ) {
		if ( '' !== $code ) {
			$html .= '<pre>' . onlinecompiler_escape_text( $code ) . '</pre>';
		}
		if ( '' !== $stdin ) {
			$html .= '<pre data-stdin>' . onlinecompiler_escape_text( $stdin ) . '</pre>';
		}
	}
	$html .= '</div>';

	wp_enqueue_script( 'onlinecompiler-widget' );
	return $html;
}
