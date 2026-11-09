<?php
/**
 * The [onlinecompiler] shortcode, for the classic editor and anywhere shortcodes run.
 *
 *     [onlinecompiler lang="python"]
 *     name = input()
 *     print(f"Hello, {name}")
 *     [/onlinecompiler]
 *
 * Attributes: lang, stdin (\n for a new line), theme, height, readonly, lang_select, problem, locale.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gets the code back as the author typed it.
 *
 * Before shortcodes run, WordPress turns line breaks in a post into <p> and <br />, and the
 * visual editor stores < and & as entities. This undoes both.
 *
 * @param string $content The text between [onlinecompiler] and [/onlinecompiler].
 * @return string
 */
function onlinecompiler_shortcode_code( $content ) {
	$code = (string) $content;
	// Only the line break goes: the next line's indentation is part of the code.
	$code = preg_replace( '#<br\s*/?>\r?\n?#i', "\n", $code );
	$code = preg_replace( '#</p>\s*<p[^>]*>#i', "\n\n", $code );
	$code = preg_replace( '#</?p[^>]*>#i', '', $code );
	$code = html_entity_decode( $code, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	// Drop the line break after [onlinecompiler] and before [/onlinecompiler], keep the rest.
	$code = preg_replace( '/^[ \t]*\r?\n|\r?\n[ \t]*$/', '', $code );
	return str_replace( "\r\n", "\n", $code );
}

/**
 * Renders [onlinecompiler]...[/onlinecompiler].
 *
 * @param array|string $atts    The shortcode's attributes.
 * @param string|null  $content The code.
 * @return string
 */
function onlinecompiler_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'lang'        => '',
			'stdin'       => '',
			'theme'       => '',
			'height'      => '',
			'readonly'    => '',
			'lang_select' => '',
			'problem'     => '',
			'locale'      => null,
		),
		$atts,
		'onlinecompiler'
	);

	$yes = array( '1', 'true', 'yes' );
	$lang_select = strtolower( (string) $atts['lang_select'] );
	if ( in_array( $lang_select, $yes, true ) ) {
		$lang_select = 'true';
	} elseif ( in_array( $lang_select, array( '0', 'false', 'no' ), true ) ) {
		$lang_select = 'false';
	} else {
		$lang_select = '';
	}

	return onlinecompiler_render(
		array(
			'code'       => onlinecompiler_shortcode_code( null === $content ? '' : $content ),
			'stdin'      => str_replace( '\n', "\n", html_entity_decode( (string) $atts['stdin'], ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ),
			'lang'       => $atts['lang'],
			'theme'      => strtolower( (string) $atts['theme'] ),
			'height'     => $atts['height'],
			'readonly'   => in_array( strtolower( (string) $atts['readonly'] ), $yes, true ),
			'langSelect' => $lang_select,
			'problem'    => $atts['problem'],
			'locale'     => null === $atts['locale'] ? null : strtolower( (string) $atts['locale'] ),
		)
	);
}
add_shortcode( 'onlinecompiler', 'onlinecompiler_shortcode' );

/**
 * Keeps WordPress from curling the quotes in the code ("" to “”), which would break it.
 *
 * @param string[] $shortcodes Shortcodes whose content is left as typed.
 * @return string[]
 */
function onlinecompiler_no_texturize( $shortcodes ) {
	$shortcodes[] = 'onlinecompiler';
	return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'onlinecompiler_no_texturize' );
