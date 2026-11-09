<?php
/**
 * Settings, OnlineCompiler.io: the widget key and the defaults every code runner starts from.
 *
 * @package OnlineCompiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ONLINECOMPILER_OPTION = 'onlinecompiler_settings';

/**
 * The saved settings, with defaults for anything not saved yet.
 *
 * @return array{key: string, lang: string, theme: string, locale: string}
 */
function onlinecompiler_settings() {
	$saved = get_option( ONLINECOMPILER_OPTION, array() );
	return wp_parse_args(
		is_array( $saved ) ? $saved : array(),
		array(
			'key'    => '',
			'lang'   => 'python-3.14',
			'theme'  => 'auto',
			'locale' => '',
		)
	);
}

/**
 * The themes the widget offers.
 *
 * @return array<string, string>
 */
function onlinecompiler_themes() {
	return array(
		'auto'  => __( 'Follow the reader\'s system (light or dark)', 'onlinecompiler-io-code-runner' ),
		'light' => __( 'Light', 'onlinecompiler-io-code-runner' ),
		'dark'  => __( 'Dark', 'onlinecompiler-io-code-runner' ),
	);
}

/**
 * The languages the widget's buttons and messages come in.
 *
 * @return array<string, string>
 */
function onlinecompiler_locales() {
	return array(
		''   => __( 'English', 'onlinecompiler-io-code-runner' ),
		'pt' => __( 'Portuguese (Brazil)', 'onlinecompiler-io-code-runner' ),
		'es' => __( 'Spanish', 'onlinecompiler-io-code-runner' ),
	);
}

/**
 * Checks what the settings form sent.
 *
 * @param mixed $input The submitted values.
 * @return array The values to save.
 */
function onlinecompiler_sanitize_settings( $input ) {
	$current = onlinecompiler_settings();
	$input   = is_array( $input ) ? $input : array();
	$clean   = $current;

	$key = isset( $input['key'] ) ? trim( sanitize_text_field( wp_unslash( $input['key'] ) ) ) : '';
	if ( '' === $key || preg_match( '/^[A-Za-z0-9_-]{8,128}$/', $key ) ) {
		$clean['key'] = $key;
	} else {
		add_settings_error(
			ONLINECOMPILER_OPTION,
			'onlinecompiler_key',
			__( 'That doesn\'t look like a widget key: copy it from the Widgets page of your OnlineCompiler.io dashboard. The old key was kept.', 'onlinecompiler-io-code-runner' )
		);
	}

	$lang = isset( $input['lang'] ) ? sanitize_text_field( wp_unslash( $input['lang'] ) ) : '';
	if ( array_key_exists( $lang, onlinecompiler_languages() ) ) {
		$clean['lang'] = $lang;
	}

	$theme = isset( $input['theme'] ) ? sanitize_key( wp_unslash( $input['theme'] ) ) : '';
	if ( array_key_exists( $theme, onlinecompiler_themes() ) ) {
		$clean['theme'] = $theme;
	}

	$locale = isset( $input['locale'] ) ? sanitize_key( wp_unslash( $input['locale'] ) ) : '';
	if ( array_key_exists( $locale, onlinecompiler_locales() ) ) {
		$clean['locale'] = $locale;
	}

	return $clean;
}

/**
 * Registers the setting and its fields.
 */
function onlinecompiler_register_settings() {
	register_setting(
		'onlinecompiler',
		ONLINECOMPILER_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'onlinecompiler_sanitize_settings',
			'default'           => array(),
		)
	);

	add_settings_section( 'onlinecompiler_main', '', '__return_false', 'onlinecompiler' );

	add_settings_field( 'onlinecompiler_key', __( 'Widget key', 'onlinecompiler-io-code-runner' ), 'onlinecompiler_field_key', 'onlinecompiler', 'onlinecompiler_main', array( 'label_for' => 'onlinecompiler_key' ) );
	add_settings_field( 'onlinecompiler_lang', __( 'Default language', 'onlinecompiler-io-code-runner' ), 'onlinecompiler_field_lang', 'onlinecompiler', 'onlinecompiler_main', array( 'label_for' => 'onlinecompiler_lang' ) );
	add_settings_field( 'onlinecompiler_theme', __( 'Theme', 'onlinecompiler-io-code-runner' ), 'onlinecompiler_field_theme', 'onlinecompiler', 'onlinecompiler_main', array( 'label_for' => 'onlinecompiler_theme' ) );
	add_settings_field( 'onlinecompiler_locale', __( 'Buttons and messages in', 'onlinecompiler-io-code-runner' ), 'onlinecompiler_field_locale', 'onlinecompiler', 'onlinecompiler_main', array( 'label_for' => 'onlinecompiler_locale' ) );
}
add_action( 'admin_init', 'onlinecompiler_register_settings' );

/**
 * The widget key field.
 */
function onlinecompiler_field_key() {
	$settings = onlinecompiler_settings();
	$host     = wp_parse_url( home_url(), PHP_URL_HOST );
	printf(
		'<input type="text" id="onlinecompiler_key" name="%1$s[key]" value="%2$s" class="regular-text code" autocomplete="off" spellcheck="false" />',
		esc_attr( ONLINECOMPILER_OPTION ),
		esc_attr( $settings['key'] )
	);
	echo '<p class="description">';
	printf(
		/* translators: 1: link to the OnlineCompiler.io Widgets page, 2: this site's domain. */
		esc_html__( 'Create a free widget key on the %1$s of your OnlineCompiler.io dashboard (sign in with Google), and set its allowed domain to %2$s so only this site can use it. Widget keys are made to sit in public pages: they can run code in the widget, never call the REST API.', 'onlinecompiler-io-code-runner' ),
		'<a href="https://api.onlinecompiler.io/compilers/widgets/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Widgets page', 'onlinecompiler-io-code-runner' ) . '</a>',
		'<code>' . esc_html( (string) $host ) . '</code>'
	);
	echo '</p>';
}

/**
 * Prints a select for one setting.
 *
 * @param string               $name    The setting's key.
 * @param array<string,string> $options Value => label.
 * @param string               $help    A line under the select.
 */
function onlinecompiler_select( $name, $options, $help = '' ) {
	$settings = onlinecompiler_settings();
	printf( '<select id="onlinecompiler_%1$s" name="%2$s[%1$s]">', esc_attr( $name ), esc_attr( ONLINECOMPILER_OPTION ) );
	foreach ( $options as $value => $label ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $value ), selected( $settings[ $name ], $value, false ), esc_html( $label ) );
	}
	echo '</select>';
	if ( '' !== $help ) {
		echo '<p class="description">' . esc_html( $help ) . '</p>';
	}
}

/**
 * The default language field.
 */
function onlinecompiler_field_lang() {
	$options = array();
	foreach ( onlinecompiler_languages() as $id => $language ) {
		$options[ $id ] = $language[0];
	}
	onlinecompiler_select( 'lang', $options, __( 'For a code runner that doesn\'t name its language. Each block or shortcode can choose its own.', 'onlinecompiler-io-code-runner' ) );
}

/**
 * The theme field.
 */
function onlinecompiler_field_theme() {
	onlinecompiler_select( 'theme', onlinecompiler_themes() );
}

/**
 * The widget language field.
 */
function onlinecompiler_field_locale() {
	onlinecompiler_select( 'locale', onlinecompiler_locales(), __( 'The Run button, the console and the widget\'s messages. Your code is not translated.', 'onlinecompiler-io-code-runner' ) );
}

/**
 * Adds Settings, OnlineCompiler.io.
 */
function onlinecompiler_add_settings_page() {
	add_options_page(
		__( 'OnlineCompiler.io Code Runner', 'onlinecompiler-io-code-runner' ),
		__( 'OnlineCompiler.io', 'onlinecompiler-io-code-runner' ),
		'manage_options',
		'onlinecompiler',
		'onlinecompiler_render_settings_page'
	);
}
add_action( 'admin_menu', 'onlinecompiler_add_settings_page' );

/**
 * The settings page.
 */
function onlinecompiler_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap">';
	echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
	echo '<p>' . esc_html__( 'Add a Code runner block to any post or page, or use the shortcode in the classic editor:', 'onlinecompiler-io-code-runner' ) . '</p>';
	echo '<p><code>[onlinecompiler lang="python"]print("Hello")[/onlinecompiler]</code></p>';
	echo '<form action="options.php" method="post">';
	settings_fields( 'onlinecompiler' );
	do_settings_sections( 'onlinecompiler' );
	submit_button();
	echo '</form>';
	echo '<p>' . wp_kses(
		sprintf(
			/* translators: %s: link to the widget documentation. */
			__( 'Every option the widget has, such as tests readers can judge their code against, is in the %s.', 'onlinecompiler-io-code-runner' ),
			'<a href="https://onlinecompiler.io/docs/widget" target="_blank" rel="noopener noreferrer">' . esc_html__( 'widget documentation', 'onlinecompiler-io-code-runner' ) . '</a>'
		),
		array(
			'a' => array(
				'href'   => array(),
				'target' => array(),
				'rel'    => array(),
			),
		)
	) . '</p>';
	echo '</div>';
}

/**
 * A Settings link on the Plugins screen.
 *
 * @param string[] $links The plugin's action links.
 * @return string[]
 */
function onlinecompiler_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=onlinecompiler' ) ) . '">' . esc_html__( 'Settings', 'onlinecompiler-io-code-runner' ) . '</a>' );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( ONLINECOMPILER_DIR . 'onlinecompiler.php' ), 'onlinecompiler_action_links' );

/**
 * Asks an administrator for the widget key until there is one.
 */
function onlinecompiler_missing_key_notice() {
	if ( ! current_user_can( 'manage_options' ) || '' !== onlinecompiler_settings()['key'] ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'settings_page_onlinecompiler' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
		esc_html__( 'OnlineCompiler.io Code Runner needs a widget key before code runners can run.', 'onlinecompiler-io-code-runner' ),
		esc_url( admin_url( 'options-general.php?page=onlinecompiler' ) ),
		esc_html__( 'Add it in Settings', 'onlinecompiler-io-code-runner' )
	);
}
add_action( 'admin_notices', 'onlinecompiler_missing_key_notice' );
