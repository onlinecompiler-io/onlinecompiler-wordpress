/**
 * The Code runner block in the editor. Plain JavaScript with WordPress's own globals, so the
 * plugin needs no build step. The page itself is rendered by PHP (includes/block.php).
 */
( function ( blocks, element, blockEditor, components, i18n ) {
	'use strict';

	var el = element.createElement;
	var __ = i18n.__;
	var sprintf = i18n.sprintf;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var PlainText = blockEditor.PlainText;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;
	var Notice = components.Notice;

	var data = window.onlinecompilerBlock || { languages: [], defaultLang: 'python-3.14', hasKey: true, settingsUrl: '' };

	function labelFor( id ) {
		for ( var i = 0; i < data.languages.length; i++ ) {
			if ( data.languages[ i ].value === id ) {
				return data.languages[ i ].label;
			}
		}
		return id;
	}

	var common = { __nextHasNoMarginBottom: true, __next40pxDefaultSize: true };

	function withCommon( props ) {
		return Object.assign( {}, common, props );
	}

	blocks.registerBlockType( 'onlinecompiler/code-runner', {
		edit: function ( props ) {
			var a = props.attributes;
			var set = props.setAttributes;
			var lang = a.lang || data.defaultLang;
			var problem = ( a.problem || '' ).trim();

			var languageOptions = [ {
				value: '',
				/* translators: %s: the site's default language, such as Python 3.14. */
				label: sprintf( __( 'Site default (%s)', 'onlinecompiler' ), labelFor( data.defaultLang ) ),
			} ].concat( data.languages );

			var inspector = el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Code runner', 'onlinecompiler' ), initialOpen: true },
					el( SelectControl, withCommon( {
						label: __( 'Language', 'onlinecompiler' ),
						value: a.lang,
						options: languageOptions,
						onChange: function ( value ) { set( { lang: value } ); },
					} ) ),
					el( SelectControl, withCommon( {
						label: __( 'Theme', 'onlinecompiler' ),
						value: a.theme,
						options: [
							{ value: '', label: __( 'Site default', 'onlinecompiler' ) },
							{ value: 'auto', label: __( 'Follow the reader\'s system', 'onlinecompiler' ) },
							{ value: 'light', label: __( 'Light', 'onlinecompiler' ) },
							{ value: 'dark', label: __( 'Dark', 'onlinecompiler' ) },
						],
						onChange: function ( value ) { set( { theme: value } ); },
					} ) ),
					el( TextControl, withCommon( {
						label: __( 'Editor height in pixels', 'onlinecompiler' ),
						help: __( 'Leave empty to fit the code (6 to 20 lines).', 'onlinecompiler' ),
						type: 'number',
						min: 0,
						value: a.height ? String( a.height ) : '',
						onChange: function ( value ) { set( { height: Math.max( 0, parseInt( value, 10 ) || 0 ) } ); },
					} ) ),
					el( ToggleControl, {
						__nextHasNoMarginBottom: true,
						label: __( 'Read-only code', 'onlinecompiler' ),
						help: __( 'Readers can run the code but not change it.', 'onlinecompiler' ),
						checked: !! a.readOnly,
						onChange: function ( value ) { set( { readOnly: value } ); },
					} ),
					el( SelectControl, withCommon( {
						label: __( 'Language menu', 'onlinecompiler' ),
						value: a.langSelect,
						options: [
							{ value: '', label: __( 'Only when there is no code', 'onlinecompiler' ) },
							{ value: 'true', label: __( 'Show', 'onlinecompiler' ) },
							{ value: 'false', label: __( 'Hide', 'onlinecompiler' ) },
						],
						onChange: function ( value ) { set( { langSelect: value } ); },
					} ) )
				),
				el(
					PanelBody,
					{ title: __( 'Problem', 'onlinecompiler' ), initialOpen: !! problem },
					el( TextControl, withCommon( {
						label: __( 'Problem id', 'onlinecompiler' ),
						help: __( 'A problem stored in your OnlineCompiler.io account: readers see its statement and judge their code against its tests. It replaces the code and input here.', 'onlinecompiler' ),
						value: a.problem,
						onChange: function ( value ) { set( { problem: value } ); },
					} ) )
				)
			);

			var bar = el(
				'div',
				{ className: 'onlinecompiler-block__bar' },
				el( 'span', null, problem
					/* translators: %s: a problem id. */
					? sprintf( __( 'Problem %s', 'onlinecompiler' ), problem )
					: labelFor( lang ) ),
				el( 'span', null, __( 'Runnable on the page', 'onlinecompiler' ) )
			);

			var body = problem
				? el( 'div', { className: 'onlinecompiler-block__note' }, __( 'Readers will see this problem, with its statement, starting code and tests, loaded from your account.', 'onlinecompiler' ) )
				: [
					el( PlainText, {
						key: 'code',
						className: 'onlinecompiler-block__code',
						value: a.code,
						placeholder: __( 'Write or paste the code readers start from', 'onlinecompiler' ),
						'aria-label': __( 'Code', 'onlinecompiler' ),
						spellCheck: false,
						onChange: function ( value ) { set( { code: value } ); },
					} ),
					el( 'div', { key: 'stdin', className: 'onlinecompiler-block__stdin' },
						el( TextareaControl, {
							__nextHasNoMarginBottom: true,
							label: __( 'Standard input (optional)', 'onlinecompiler' ),
							value: a.stdin,
							rows: 2,
							onChange: function ( value ) { set( { stdin: value } ); },
						} )
					),
				];

			var notice = data.hasKey ? null : el(
				Notice,
				{ status: 'warning', isDismissible: false },
				__( 'Add your widget key in Settings, OnlineCompiler.io, so readers can run this code.', 'onlinecompiler' ),
				data.settingsUrl ? el( 'a', { href: data.settingsUrl, style: { marginLeft: '4px' } }, __( 'Open settings', 'onlinecompiler' ) ) : null
			);

			return el(
				'div',
				useBlockProps( { className: 'onlinecompiler-block' } ),
				inspector,
				notice,
				bar,
				body
			);
		},
		save: function () {
			return null;
		},
	} );
}( window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n ) );
