<?php
/**
 * Skin Setup
 *
 * @package PARAGON
 * @since PARAGON 1.76.0
 */


//--------------------------------------------
// SKIN DEFAULTS
//--------------------------------------------

// Return theme's (skin's) default value for the specified parameter
if ( ! function_exists( 'paragon_theme_defaults' ) ) {
	function paragon_theme_defaults( $name='', $value='' ) {
		$defaults = array(
			'page_width'          => 1290,
			'page_boxed_extra'    => 60,
			'page_fullwide_max'   => 1920,
			'page_fullwide_extra' => 60,
			'sidebar_width'       => 410,
			'sidebar_gap'         => 40,
			'grid_gap'            => 30,
			'rad'                 => 0
		);
		if ( empty( $name ) ) {
			return $defaults;
		} else {
			if ( $value === '' && isset( $defaults[ $name ] ) ) {
				$value = $defaults[ $name ];
			}
			return $value;
		}
	}
}


// WOOCOMMERCE SETUP
//--------------------------------------------------

// Allow extended layouts for WooCommerce
if ( ! function_exists( 'paragon_skin_woocommerce_allow_extensions' ) ) {
	add_filter( 'paragon_filter_load_woocommerce_extensions', 'paragon_skin_woocommerce_allow_extensions' );
	function paragon_skin_woocommerce_allow_extensions( $allow ) {
		return false;
	}
}


// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)


//--------------------------------------------
// SKIN SETTINGS
//--------------------------------------------
if ( ! function_exists( 'paragon_skin_setup' ) ) {
	add_action( 'after_setup_theme', 'paragon_skin_setup', 1 );
	function paragon_skin_setup() {

		$GLOBALS['PARAGON_STORAGE'] = array_merge( $GLOBALS['PARAGON_STORAGE'], array(

			// Key validator: market[env|loc]-vendor[axiom|ancora|themerex]
			'theme_pro_key'       => 'env-axiom',
			
			'theme_doc_url'       => '//doc.themerex.net/paragon/',
			
			'theme_demofiles_url' => '//demofiles.axiomthemes.com/paragon/',
			
			'theme_rate_url'      => '//themeforest.net/downloads',
			
			'theme_custom_url'    => '//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall',
			
			'theme_support_url'   => '//themerex.net/support/',
			
			'theme_download_url'  => '//themeforest.net/user/axiomthemes/portfolio',         // Axiom
			
			'theme_video_url'     => '//www.youtube.com/channel/UCBjqhuwKj3MfE3B6Hg2oA8Q',   // Axiom
			
			'theme_privacy_url'   => '//axiomthemes.com/privacy-policy/',                    // Axiom
			
			'portfolio_url'       => '//themeforest.net/user/axiomthemes/portfolio',         // Axiom
			
			// Comma separated slugs of theme-specific categories (for get relevant news in the dashboard widget)
			// (i.e. 'children,kindergarten')
			'theme_categories'    => '',
		) );
	}
}


// Add/remove/change Theme Settings
if ( ! function_exists( 'paragon_skin_setup_settings' ) ) {
	add_action( 'after_setup_theme', 'paragon_skin_setup_settings', 1 );
	function paragon_skin_setup_settings() {
		// Example: enable (true) / disable (false) thumbs in the prev/next navigation
		paragon_storage_set_array( 'settings', 'thumbs_in_navigation', false );
		paragon_storage_set_array2( 'required_plugins', 'instagram-feed', 'install', false);
	}
}

// Update Theme Options elements
if ( ! function_exists( 'paragon_skin_options_theme_setup2' ) ) {
	add_action( 'after_setup_theme', 'paragon_skin_options_theme_setup2', 4 );
	function paragon_skin_options_theme_setup2() {
		paragon_storage_set_array2( 'options', 'color_scheme', 'std', 'light');
		paragon_storage_set_array2( 'options', 'sidebar_scheme', 'std', 'light');
	}
}


//--------------------------------------------
// SKIN FONTS
//--------------------------------------------
if ( ! function_exists( 'paragon_skin_setup_fonts' ) ) {
	add_action( 'after_setup_theme', 'paragon_skin_setup_fonts', 1 );
	function paragon_skin_setup_fonts() {
		// Fonts to load when theme start
		// It can be:
		// - Google fonts (specify name, family and styles)
		// - Adobe fonts (specify name, family and link URL)
		// - uploaded fonts (specify name, family), placed in the folder css/font-face/font-name inside the skin folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		paragon_storage_set(
			'load_fonts', array(
				// Google font
				array(
					'name'   => 'Kumbh Sans',
					'family' => 'sans-serif',
					'link'   => '',
					'styles' => 'wght@100;200;300;400;500;600;700;800;900',     // Parameter 'style' used only for the Google fonts
				),
			)
		);

		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		paragon_storage_set( 'load_fonts_subset', 'latin,latin-ext' );

		// Settings of the main tags.
		// Default value of 'font-family' may be specified as reference to the array $load_fonts (see above)
		// or as comma-separated string.
		// In the second case (if 'font-family' is specified manually as comma-separated string):
		//    1) Font name with spaces in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!
		//    2) If font-family inherit a value from the 'Main text' - specify 'inherit' as a value
		// example:
		// Correct:   'font-family' => paragon_get_load_fonts_family_string( $load_fonts[0] )
		// Correct:   'font-family' => 'Roboto,sans-serif'
		// Correct:   'font-family' => '"PT Serif",sans-serif'
		// Incorrect: 'font-family' => 'Roboto, sans-serif'
		// Incorrect: 'font-family' => 'PT Serif,sans-serif'

		$font_description = esc_html__( 'Font settings for the %s of the site. To ensure that the elements scale properly on mobile devices, please use only the following units: "rem", "em" or "ex"', 'paragon' );

		paragon_storage_set(
			'theme_fonts', array(
				'p'       => array(
					'title'           => esc_html__( 'Main text', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'main text', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '1rem',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.65em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0em',
					'margin-bottom'   => '1.57em',
				),
				'post'    => array(
					'title'           => esc_html__( 'Article text', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'article text', 'paragon' ) ),
					'font-family'     => '',			// Example: '"PR Serif",serif',
					'font-size'       => '',			// Example: '1.286rem',
					'font-weight'     => '',			// Example: '400',
					'font-style'      => '',			// Example: 'normal',
					'line-height'     => '',			// Example: '1.75em',
					'text-decoration' => '',			// Example: 'none',
					'text-transform'  => '',			// Example: 'none',
					'letter-spacing'  => '',			// Example: '',
					'margin-top'      => '',			// Example: '0em',
					'margin-bottom'   => '',			// Example: '1.4em',
				),
				'h1'      => array(
					'title'           => esc_html__( 'Heading 1', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H1', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '3.3529em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.05em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '-0.57px',
					'margin-top'      => '1.22em',
					'margin-bottom'   => '0.38em',
				),
				'h2'      => array(
					'title'           => esc_html__( 'Heading 2', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H2', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '2.7647em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.021em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.9em',
					'margin-bottom'   => '0.39em',
				),
				'h3'      => array(
					'title'           => esc_html__( 'Heading 3', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H3', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '2.0588em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.086em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.3em',
					'margin-bottom'   => '0.52em',
				),
				'h4'      => array(
					'title'           => esc_html__( 'Heading 4', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H4', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '1.647em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.214em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.5em',
					'margin-bottom'   => '0.63em',
				),
				'h5'      => array(
					'title'           => esc_html__( 'Heading 5', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H5', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '1.4117em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.417em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '1.5em',
					'margin-bottom'   => '0.76em',
				),
				'h6'      => array(
					'title'           => esc_html__( 'Heading 6', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'tag H6', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '1.117em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.474em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '2.4em',
					'margin-bottom'   => '0.8em',
				),
				'logo'    => array(
					'title'           => esc_html__( 'Logo text', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'text of the logo', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '1.7em',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.25em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'button'  => array(
					'title'           => esc_html__( 'Buttons', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'buttons', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '14px',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '21px',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'input'   => array(
					'title'           => esc_html__( 'Input fields', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'input fields, dropdowns and textareas', 'paragon' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '16px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',     // Attention! Firefox don't allow line-height less then 1.5em in the select
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0.1px',
				),
				'info'    => array(
					'title'           => esc_html__( 'Post meta', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'post meta (author, categories, publish date, counters, share, etc.)', 'paragon' ) ),
					'font-family'     => 'inherit',
					'font-size'       => '13px',  // Old value '13px' don't allow using 'font zoom' in the custom blog items
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
					'margin-top'      => '0.4em',
					'margin-bottom'   => '',
				),
				'menu'    => array(
					'title'           => esc_html__( 'Main menu', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'main menu items', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '16px',
					'font-weight'     => '700',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
				'submenu' => array(
					'title'           => esc_html__( 'Dropdown menu', 'paragon' ),
					'description'     => sprintf( $font_description, esc_html__( 'dropdown menu items', 'paragon' ) ),
					'font-family'     => '"Kumbh Sans",sans-serif',
					'font-size'       => '15px',
					'font-weight'     => '400',
					'font-style'      => 'normal',
					'line-height'     => '1.5em',
					'text-decoration' => 'none',
					'text-transform'  => 'none',
					'letter-spacing'  => '0px',
				),
			)
		);

		// Font presets
		paragon_storage_set(
			'font_presets', array(
				'karla' => array(
								'title'  => esc_html__( 'Karla', 'paragon' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Dancing Script',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
													// Google font
													array(
														'name'   => 'Sansita Swashed',
														'family' => 'fantasy',
														'link'   => '',
														'styles' => '300,400,700',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Dancing Script",fantasy',
														'font-size'       => '1.25rem',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
														'font-size'       => '4em',
													),
													'h2'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h3'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h4'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h5'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'h6'      => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'logo'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'button'  => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
													'submenu' => array(
														'font-family'     => '"Sansita Swashed",fantasy',
													),
												),
							),
				'roboto' => array(
								'title'  => esc_html__( 'Roboto', 'paragon' ),
								'load_fonts' => array(
													// Google font
													array(
														'name'   => 'Noto Sans JP',
														'family' => 'serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
													// Google font
													array(
														'name'   => 'Merriweather',
														'family' => 'sans-serif',
														'link'   => '',
														'styles' => '300,300italic,400,400italic,700,700italic',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Noto Sans JP",serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Merriweather,sans-serif',
													),
												),
							),
				'garamond' => array(
								'title'  => esc_html__( 'Garamond', 'paragon' ),
								'load_fonts' => array(
													// Adobe font
													array(
														'name'   => 'Europe',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
													// Adobe font
													array(
														'name'   => 'Sofia Pro',
														'family' => 'sans-serif',
														'link'   => 'https://use.typekit.net/qmj1tmx.css',
														'styles' => '',
													),
												),
								'theme_fonts' => array(
													'p'       => array(
														'font-family'     => '"Sofia Pro",sans-serif',
													),
													'post'    => array(
														'font-family'     => '',
													),
													'h1'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h2'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h3'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h4'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h5'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'h6'      => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'logo'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'button'  => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'input'   => array(
														'font-family'     => 'inherit',
													),
													'info'    => array(
														'font-family'     => 'inherit',
													),
													'menu'    => array(
														'font-family'     => 'Europe,sans-serif',
													),
													'submenu' => array(
														'font-family'     => 'Europe,sans-serif',
													),
												),
							),
			)
		);
	}
}


//--------------------------------------------
// COLOR SCHEMES
//--------------------------------------------
if ( ! function_exists( 'paragon_skin_setup_schemes' ) ) {
	add_action( 'after_setup_theme', 'paragon_skin_setup_schemes', 1 );
	function paragon_skin_setup_schemes() {

		// Theme colors for customizer
		// Attention! Inner scheme must be last in the array below
		paragon_storage_set(
			'scheme_color_groups', array(
				'main'    => array(
					'title'       => esc_html__( 'Main', 'paragon' ),
					'description' => esc_html__( 'Colors of the main content area', 'paragon' ),
				),
				'alter'   => array(
					'title'       => esc_html__( 'Alter', 'paragon' ),
					'description' => esc_html__( 'Colors of the alternative blocks (sidebars, etc.)', 'paragon' ),
				),
				'extra'   => array(
					'title'       => esc_html__( 'Extra', 'paragon' ),
					'description' => esc_html__( 'Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'paragon' ),
				),
				'inverse' => array(
					'title'       => esc_html__( 'Inverse', 'paragon' ),
					'description' => esc_html__( 'Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'paragon' ),
				),
				'input'   => array(
					'title'       => esc_html__( 'Input', 'paragon' ),
					'description' => esc_html__( 'Colors of the form fields (text field, textarea, select, etc.)', 'paragon' ),
				),
			)
		);

		paragon_storage_set(
			'scheme_color_names', array(
				'bg_color'    => array(
					'title'       => esc_html__( 'Background color', 'paragon' ),
					'description' => esc_html__( 'Background color of this block in the normal state', 'paragon' ),
				),
				'bg_hover'    => array(
					'title'       => esc_html__( 'Background hover', 'paragon' ),
					'description' => esc_html__( 'Background color of this block in the hovered state', 'paragon' ),
				),
				'bd_color'    => array(
					'title'       => esc_html__( 'Border color', 'paragon' ),
					'description' => esc_html__( 'Border color of this block in the normal state', 'paragon' ),
				),
				'bd_hover'    => array(
					'title'       => esc_html__( 'Border hover', 'paragon' ),
					'description' => esc_html__( 'Border color of this block in the hovered state', 'paragon' ),
				),
				'text'        => array(
					'title'       => esc_html__( 'Text', 'paragon' ),
					'description' => esc_html__( 'Color of the text inside this block', 'paragon' ),
				),
				'text_dark'   => array(
					'title'       => esc_html__( 'Text dark', 'paragon' ),
					'description' => esc_html__( 'Color of the dark text (bold, header, etc.) inside this block', 'paragon' ),
				),
				'text_light'  => array(
					'title'       => esc_html__( 'Text light', 'paragon' ),
					'description' => esc_html__( 'Color of the light text (post meta, etc.) inside this block', 'paragon' ),
				),
				'text_link'   => array(
					'title'       => esc_html__( 'Link', 'paragon' ),
					'description' => esc_html__( 'Color of the links inside this block', 'paragon' ),
				),
				'text_hover'  => array(
					'title'       => esc_html__( 'Link hover', 'paragon' ),
					'description' => esc_html__( 'Color of the hovered state of links inside this block', 'paragon' ),
				),
				'text_link2'  => array(
					'title'       => esc_html__( 'Accent 2', 'paragon' ),
					'description' => esc_html__( 'Color of the accented texts (areas) inside this block', 'paragon' ),
				),
				'text_hover2' => array(
					'title'       => esc_html__( 'Accent 2 hover', 'paragon' ),
					'description' => esc_html__( 'Color of the hovered state of accented texts (areas) inside this block', 'paragon' ),
				),
				'text_link3'  => array(
					'title'       => esc_html__( 'Accent 3', 'paragon' ),
					'description' => esc_html__( 'Color of the other accented texts (buttons) inside this block', 'paragon' ),
				),
				'text_hover3' => array(
					'title'       => esc_html__( 'Accent 3 hover', 'paragon' ),
					'description' => esc_html__( 'Color of the hovered state of other accented texts (buttons) inside this block', 'paragon' ),
				),
			)
		);

		// Default values for each color scheme
		$schemes = array(
			// Color scheme: 'default'
			'default' => array(
				'title'    => esc_html__( 'Default', 'paragon' ),
				'internal' => true,
				'colors'   => array(
					
					// Whole block border and background
					'bg_color'         => '#EFF1EF',
					'bd_color'         => '#DDDDDD',
					
					// Text and links colors
					'text'             => '#707677',
					'text_light'       => '#B5B7B7',
					'text_dark'        => '#232727',
					'text_link'        => '#407D88',
					'text_hover'       => '#33717C',
					'text_link2'       => '#4262C5',
					'text_hover2'      => '#264AB9',
					'text_link3'       => '#A374D5',
					'text_hover3'      => '#8A55C1',
					
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#F9F9F9',
					'alter_bg_hover'   => '#E1E7E1',
					'alter_bd_color'   => '#DDDDDD',
					'alter_bd_hover'   => '#D2D2D2',
					'alter_text'       => '#707677',
					'alter_light'      => '#B5B7B7',
					'alter_dark'       => '#232727',
					'alter_link'       => '#407D88',
					'alter_hover'      => '#33717C',
					'alter_link2'      => '#4262C5',
					'alter_hover2'     => '#264AB9',
					'alter_link3'      => '#A374D5',
					'alter_hover3'     => '#8A55C1',
					
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#192C2F',
					'extra_bg_hover'   => '#22373A',
					'extra_bd_color'   => '#22393D',
					'extra_bd_hover'   => '#2A4246',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#407D88',
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#4262C5',
					'extra_hover2'     => '#264AB9',
					'extra_link3'      => '#A374D5',
					'extra_hover3'     => '#8A55C1',
					
					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#DDDDDD',
					'input_bd_hover'   => '#D2D2D2',
					'input_text'       => '#707677',
					'input_light'      => '#B5B7B7',
					'input_dark'       => '#232727',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#67bcc1',
					'inverse_bd_hover' => '#5aa4a9',
					'inverse_text'     => '#232727',
					'inverse_light'    => '#333333',
					'inverse_dark'     => '#232727',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#FFFFFF',
					
					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			
			// Color scheme: 'dark'
			'dark'    => array(
				'title'    => esc_html__( 'Dark', 'paragon' ),
				'internal' => true,
				'colors'   => array(
					
					// Whole block border and background
					'bg_color'         => '#16292D',
					'bd_color'         => '#22393D',
					
					// Text and links colors
					'text'             => '#C5C5C5',
					'text_light'       => '#989A9A',
					'text_dark'        => '#FFFFFF',
					'text_link'        => '#407D88',
					'text_hover'       => '#33717C',
					'text_link2'       => '#4262C5',
					'text_hover2'      => '#264AB9',
					'text_link3'       => '#A374D5',
					'text_hover3'      => '#8A55C1',
					
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#0C1E21',
					'alter_bg_hover'   => '#152B2F',
					'alter_bd_color'   => '#22393D',
					'alter_bd_hover'   => '#2A4246',
					'alter_text'       => '#C5C5C5',
					'alter_light'      => '#989A9A',
					'alter_dark'       => '#FFFFFF',
					'alter_link'       => '#407D88',
					'alter_hover'      => '#33717C',
					'alter_link2'      => '#4262C5',
					'alter_hover2'     => '#264AB9',
					'alter_link3'      => '#A374D5',
					'alter_hover3'     => '#8A55C1',
					
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#192C2F',
					'extra_bg_hover'   => '#22373A',
					'extra_bd_color'   => '#22393D',
					'extra_bd_hover'   => '#2A4246',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#407D88',
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#4262C5',
					'extra_hover2'     => '#264AB9',
					'extra_link3'      => '#A374D5',
					'extra_hover3'     => '#8A55C1',
					
					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#22393D',
					'input_bd_hover'   => '#2A4246',
					'input_text'       => '#C5C5C5',
					'input_light'      => '#989A9A',
					'input_dark'       => '#FFFFFF',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#e36650',
					'inverse_bd_hover' => '#cb5b47',
					'inverse_text'     => '#FFFFFF',
					'inverse_light'    => '#6f6f6f',
					'inverse_dark'     => '#232727',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#232727',
					
					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
			
			// Color scheme: 'light'
			'light' => array(
				'title'    => esc_html__( 'Light', 'paragon' ),
				'internal' => true,
				'colors'   => array(
					
					// Whole block border and background
					'bg_color'         => '#F9F9F9',
					'bd_color'         => '#DDDDDD',
					
					// Text and links colors
					'text'             => '#707677',
					'text_light'       => '#B5B7B7',
					'text_dark'        => '#232727',
					'text_link'        => '#407D88',
					'text_hover'       => '#33717C',
					'text_link2'       => '#4262C5',
					'text_hover2'      => '#264AB9',
					'text_link3'       => '#A374D5',
					'text_hover3'      => '#8A55C1',
					
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#EFF1EF',
					'alter_bg_hover'   => '#E1E7E1',
					'alter_bd_color'   => '#DDDDDD',
					'alter_bd_hover'   => '#D2D2D2',
					'alter_text'       => '#707677',
					'alter_light'      => '#B5B7B7',
					'alter_dark'       => '#232727',
					'alter_link'       => '#407D88',
					'alter_hover'      => '#33717C',
					'alter_link2'      => '#4262C5',
					'alter_hover2'     => '#264AB9',
					'alter_link3'      => '#A374D5',
					'alter_hover3'     => '#8A55C1',
					
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#192C2F',
					'extra_bg_hover'   => '#22373A',
					'extra_bd_color'   => '#22393D',
					'extra_bd_hover'   => '#2A4246',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#407D88',
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#4262C5',
					'extra_hover2'     => '#264AB9',
					'extra_link3'      => '#A374D5',
					'extra_hover3'     => '#8A55C1',
					
					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#DDDDDD',
					'input_bd_hover'   => '#D2D2D2',
					'input_text'       => '#707677',
					'input_light'      => '#B5B7B7',
					'input_dark'       => '#232727',
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#67bcc1',
					'inverse_bd_hover' => '#5aa4a9',
					'inverse_text'     => '#232727',
					'inverse_light'    => '#333333',
					'inverse_dark'     => '#232727',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#FFFFFF',
					
					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

			// Color scheme: 'orange'
			'orange' => array(
				'title'    => esc_html__( 'Orange', 'paragon' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FAF5F3',
					'bd_color'         => '#E2DCDA',

					// Text and links colors
					'text'             => '#808080',
					'text_light'       => '#B6B6B6',
					'text_dark'        => '#1B1B1B',
					'text_link'        => '#EC733B',
					'text_hover'       => '#D65B22',
					'text_link2'       => '#3BB4EC',
					'text_hover2'      => '#259DD4',
					'text_link3'       => '#EC3B4A',
					'text_hover3'      => '#D41F2E',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FEFEFE',
					'alter_bg_hover'   => '#FAEFEA',
					'alter_bd_color'   => '#E2DCDA',
					'alter_bd_hover'   => '#DAD2CF',
					'alter_text'       => '#808080',
					'alter_light'      => '#B6B6B6',
					'alter_dark'       => '#1B1B1B',
					'alter_link'       => '#EC733B',
					'alter_hover'      => '#D65B22',
					'alter_link2'      => '#3BB4EC',
					'alter_hover2'     => '#259DD4',
					'alter_link3'      => '#EC3B4A',
					'alter_hover3'     => '#D41F2E',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#272727',
					'extra_bg_hover'   => '#323232',
					'extra_bd_color'   => '#3D3D3D',
					'extra_bd_hover'   => '#494949',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#EC733B',
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#3BB4EC',
					'extra_hover2'     => '#259DD4',
					'extra_link3'      => '#EC3B4A',
					'extra_hover3'     => '#D41F2E',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#E2DCDA',
					'input_bd_hover'   => '#DAD2CF',
					'input_text'       => '#808080',
					'input_light'      => '#B6B6B6',
					'input_dark'       => '#1B1B1B',

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#67bcc1',
					'inverse_bd_hover' => '#5aa4a9',
					'inverse_text'     => '#1B1B1B',
					'inverse_light'    => '#333333',
					'inverse_dark'     => '#1B1B1B',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#FFFFFF',

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

			// Color scheme: 'orange_dark'
			'orange_dark'    => array(
				'title'    => esc_html__( 'Orange Dark', 'paragon' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#1C1B1B',
					'bd_color'         => '#3D3D3D',

					// Text and links colors
					'text'             => '#C5C5C5',
					'text_light'       => '#989A9A',
					'text_dark'        => '#FFFFFF',
					'text_link'        => '#EC733B',
					'text_hover'       => '#D65B22',
					'text_link2'       => '#3BB4EC',
					'text_hover2'      => '#259DD4',
					'text_link3'       => '#EC3B4A',
					'text_hover3'      => '#D41F2E',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#0E0D0D',
					'alter_bg_hover'   => '#1F1E1E',
					'alter_bd_color'   => '#3D3D3D',
					'alter_bd_hover'   => '#494949',
					'alter_text'       => '#C5C5C5',
					'alter_light'      => '#989A9A',
					'alter_dark'       => '#FFFFFF',
					'alter_link'       => '#EC733B',
					'alter_hover'      => '#D65B22',
					'alter_link2'      => '#3BB4EC',
					'alter_hover2'     => '#259DD4',
					'alter_link3'      => '#EC3B4A',
					'alter_hover3'     => '#D41F2E',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#272727',
					'extra_bg_hover'   => '#323232',
					'extra_bd_color'   => '#3D3D3D',
					'extra_bd_hover'   => '#494949',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#EC733B',
				 	'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#3BB4EC',
					'extra_hover2'     => '#259DD4',
					'extra_link3'      => '#EC3B4A',
					'extra_hover3'     => '#D41F2E',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#3D3D3D',
					'input_bd_hover'   => '#494949',
					'input_text'       => '#C5C5C5',
					'input_light'      => '#989A9A',
					'input_dark'       => '#FFFFFF',

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#e36650',
					'inverse_bd_hover' => '#cb5b47',
					'inverse_text'     => '#FFFFFF',
					'inverse_light'    => '#6f6f6f',
					'inverse_dark'     => '#1B1B1B',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#1B1B1B',

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),

			// Color scheme: 'orange_light'
			'orange_light' => array(
				'title'    => esc_html__( 'Orange Light', 'paragon' ),
				'internal' => true,
				'colors'   => array(

					// Whole block border and background
					'bg_color'         => '#FEFEFE',
					'bd_color'         => '#E2DCDA',

					// Text and links colors
					'text'             => '#808080',
					'text_light'       => '#B6B6B6',
					'text_dark'        => '#1B1B1B',
					'text_link'        => '#EC733B',
					'text_hover'       => '#D65B22',
					'text_link2'       => '#3BB4EC',
					'text_hover2'      => '#259DD4',
					'text_link3'       => '#EC3B4A',
					'text_hover3'      => '#D41F2E',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'   => '#FAF5F3',
					'alter_bg_hover'   => '#FAEFEA',
					'alter_bd_color'   => '#E2DCDA',
					'alter_bd_hover'   => '#DAD2CF',
					'alter_text'       => '#808080',
					'alter_light'      => '#B6B6B6',
					'alter_dark'       => '#1B1B1B',
					'alter_link'       => '#EC733B',
					'alter_hover'      => '#D65B22',
					'alter_link2'      => '#3BB4EC',
					'alter_hover2'     => '#259DD4',
					'alter_link3'      => '#EC3B4A',
					'alter_hover3'     => '#D41F2E',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'   => '#272727',
					'extra_bg_hover'   => '#323232',
					'extra_bd_color'   => '#3D3D3D',
					'extra_bd_hover'   => '#494949',
					'extra_text'       => '#C5C5C5',
					'extra_light'      => '#989A9A',
					'extra_dark'       => '#FFFFFF',
					'extra_link'       => '#EC733B',
					'extra_hover'      => '#FFFFFF',
					'extra_link2'      => '#3BB4EC',
					'extra_hover2'     => '#259DD4',
					'extra_link3'      => '#EC3B4A',
					'extra_hover3'     => '#D41F2E',

					// Input fields (form's fields and textarea)
					'input_bg_color'   => 'transparent',
					'input_bg_hover'   => 'transparent',
					'input_bd_color'   => '#E2DCDA',
					'input_bd_hover'   => '#DAD2CF',
					'input_text'       => '#808080',
					'input_light'      => '#B6B6B6',
					'input_dark'       => '#1B1B1B',

					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color' => '#67bcc1',
					'inverse_bd_hover' => '#5aa4a9',
					'inverse_text'     => '#1B1B1B',
					'inverse_light'    => '#333333',
					'inverse_dark'     => '#1B1B1B',
					'inverse_link'     => '#FFFFFF',
					'inverse_hover'    => '#FFFFFF',

					// Additional (skin-specific) colors.
					// Attention! Set of colors must be equal in all color schemes.
					//---> For example:
					//---> 'new_color1'         => '#rrggbb',
					//---> 'alter_new_color1'   => '#rrggbb',
					//---> 'inverse_new_color1' => '#rrggbb',
				),
			),
		);
		paragon_storage_set( 'schemes', $schemes );
		paragon_storage_set( 'schemes_original', $schemes );

		// Add names of additional colors
		//---> For example:
		//---> paragon_storage_set_array( 'scheme_color_names', 'new_color1', array(
		//---> 	'title'       => __( 'New color 1', 'paragon' ),
		//---> 	'description' => __( 'Description of the new color 1', 'paragon' ),
		//---> ) );


		// Additional colors for each scheme
		// Parameters:	'color' - name of the color from the scheme that should be used as source for the transformation
		//				'alpha' - to make color transparent (0.0 - 1.0)
		//				'hue', 'saturation', 'brightness' - inc/dec value for each color's component
		paragon_storage_set(
			'scheme_colors_add', array(
				'bg_color_0'        => array(
					'color' => 'bg_color',
					'alpha' => 0,
				),
				'bg_color_02'       => array(
					'color' => 'bg_color',
					'alpha' => 0.2,
				),
				'bg_color_07'       => array(
					'color' => 'bg_color',
					'alpha' => 0.7,
				),
				'bg_color_08'       => array(
					'color' => 'bg_color',
					'alpha' => 0.8,
				),
				'bg_color_09'       => array(
					'color' => 'bg_color',
					'alpha' => 0.9,
				),
				'alter_bg_color_07' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.7,
				),
				'alter_bg_color_08' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.8,
				),
				'alter_bg_color_04' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.4,
				),
				'alter_bg_color_00' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0,
				),
				'alter_bg_color_02' => array(
					'color' => 'alter_bg_color',
					'alpha' => 0.2,
				),
				'alter_bd_color_02' => array(
					'color' => 'alter_bd_color',
					'alpha' => 0.2,
				),
                'alter_dark_015'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.15,
                ),
                'alter_dark_02'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.2,
                ),
                'alter_dark_05'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.5,
                ),
                'alter_dark_08'     => array(
                    'color' => 'alter_dark',
                    'alpha' => 0.8,
                ),
				'alter_link_02'     => array(
					'color' => 'alter_link',
					'alpha' => 0.2,
				),
				'alter_link_07'     => array(
					'color' => 'alter_link',
					'alpha' => 0.7,
				),
				'extra_bg_color_05' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.5,
				),
				'extra_bg_color_07' => array(
					'color' => 'extra_bg_color',
					'alpha' => 0.7,
				),
				'extra_link_02'     => array(
					'color' => 'extra_link',
					'alpha' => 0.2,
				),
				'extra_link_07'     => array(
					'color' => 'extra_link',
					'alpha' => 0.7,
				),
                'text_dark_003'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.03,
                ),
                'text_dark_005'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.05,
                ),
                'text_dark_008'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.08,
                ),
				'text_dark_015'      => array(
					'color' => 'text_dark',
					'alpha' => 0.15,
				),
				'text_dark_02'      => array(
					'color' => 'text_dark',
					'alpha' => 0.2,
				),
                'text_dark_03'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.3,
                ),
                'text_dark_05'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.5,
                ),
				'text_dark_07'      => array(
					'color' => 'text_dark',
					'alpha' => 0.7,
				),
                'text_dark_08'      => array(
                    'color' => 'text_dark',
                    'alpha' => 0.8,
                ),
                'text_link_007'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.07,
                ),
				'text_link_02'      => array(
					'color' => 'text_link',
					'alpha' => 0.2,
				),
                'text_link_03'      => array(
                    'color' => 'text_link',
                    'alpha' => 0.3,
                ),
				'text_link_04'      => array(
					'color' => 'text_link',
					'alpha' => 0.4,
				),
				'text_link_07'      => array(
					'color' => 'text_link',
					'alpha' => 0.7,
				),
		'text_link2_08'      => array(
			'color' => 'text_link2',
			'alpha' => 0.8,
                ),
                'text_link2_007'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.07,
                ),
				'text_link2_02'      => array(
					'color' => 'text_link2',
					'alpha' => 0.2,
				),
                'text_link2_03'      => array(
                    'color' => 'text_link2',
                    'alpha' => 0.3,
                ),
				'text_link2_05'      => array(
					'color' => 'text_link2',
					'alpha' => 0.5,
				),
                'text_link3_007'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.07,
                ),
				'text_link3_02'      => array(
					'color' => 'text_link3',
					'alpha' => 0.2,
				),
                'text_link3_03'      => array(
                    'color' => 'text_link3',
                    'alpha' => 0.3,
                ),
                'inverse_text_03'      => array(
                    'color' => 'inverse_text',
                    'alpha' => 0.3,
                ),
                'inverse_link_08'      => array(
                    'color' => 'inverse_link',
                    'alpha' => 0.8,
                ),
                'inverse_hover_08'      => array(
                    'color' => 'inverse_hover',
                    'alpha' => 0.8,
                ),
				'text_dark_blend'   => array(
					'color'      => 'text_dark',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'text_link_blend'   => array(
					'color'      => 'text_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
				'alter_link_blend'  => array(
					'color'      => 'alter_link',
					'hue'        => 2,
					'saturation' => -5,
					'brightness' => 5,
				),
			)
		);

		// Simple scheme editor: lists the colors to edit in the "Simple" mode.
		// For each color you can set the array of 'slave' colors and brightness factors that are used to generate new values,
		// when 'main' color is changed
		// Leave 'slave' arrays empty if your scheme does not have a color dependency
		paragon_storage_set(
			'schemes_simple', array(
				'text_link'        => array(),
				'text_hover'       => array(),
				'text_link2'       => array(),
				'text_hover2'      => array(),
				'text_link3'       => array(),
				'text_hover3'      => array(),
				'alter_link'       => array(),
				'alter_hover'      => array(),
				'alter_link2'      => array(),
				'alter_hover2'     => array(),
				'alter_link3'      => array(),
				'alter_hover3'     => array(),
				'extra_link'       => array(),
				'extra_hover'      => array(),
				'extra_link2'      => array(),
				'extra_hover2'     => array(),
				'extra_link3'      => array(),
				'extra_hover3'     => array(),
			)
		);

		// Parameters to set order of schemes in the css
		paragon_storage_set(
			'schemes_sorted', array(
				'color_scheme',
				'header_scheme',
				'menu_scheme',
				'sidebar_scheme',
				'footer_scheme',
			)
		);

		// Color presets
		paragon_storage_set(
			'color_presets', array(
				'autumn' => array(
								'title'  => esc_html__( 'Autumn', 'paragon' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	),
												'dark' => array(
																	'text_link'  => '#d83938',
																	'text_hover' => '#f2b232',
																	)
												)
							),
				'green' => array(
								'title'  => esc_html__( 'Natural Green', 'paragon' ),
								'colors' => array(
												'default' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	),
												'dark' => array(
																	'text_link'  => '#75ac78',
																	'text_hover' => '#378e6d',
																	)
												)
							),
			)
		);
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if ( ! function_exists( 'paragon_clone_theme_setup3' ) ) {
	add_action( 'after_setup_theme', 'paragon_clone_theme_setup3', 3 );
	function paragon_clone_theme_setup3() {
		paragon_storage_set_array_after( 'options', 'remove_margins', array(
				'extra_bg_image' => array(
					'title'      => esc_html__( 'Extra background image', 'paragon' ),
					'desc'       => wp_kses_data( __( 'Select or upload background-image to display it in the page. Does not work for boxed body style.', 'paragon' ) ),
					'override'   => array(
						'mode'    => 'page',
						'section' => esc_html__( 'Content', 'paragon' )
					),
					'dependency' => array(
						'body_style' => array( 'wide', 'fullwide', 'fullscreen' ),
					),
					'std'        => '',
					'pro_only'   => PARAGON_THEME_FREE,
					'type'       => 'image'
				),
			)
		);
	}
}

if ( ! function_exists( 'paragon_clone_filter_page_wrap_class' ) ) {
	add_action( 'paragon_filter_page_wrap_class', 'paragon_clone_filter_page_wrap_class' );
	function paragon_clone_filter_page_wrap_class( $class ) {
		$extra_bg_image   = paragon_get_theme_option( 'extra_bg_image' );
		$body_boxed_style = paragon_get_theme_option( 'body_style' ) == 'boxed';
		if ( ! empty( $extra_bg_image ) && ! $body_boxed_style ) {
			$custom_bg = ' ' . 'with_bg' . ' ' . paragon_add_inline_css_class( 'background-image: url(' . esc_url( $extra_bg_image ) . ');' );
			$class     = $class . $custom_bg;
		}

		return $class;
	}
}

// Enqueue extra styles for frontend
if ( ! function_exists( 'paragon_clone_frontend_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'paragon_clone_frontend_scripts', 1150 );
	function paragon_clone_frontend_scripts() {
		$paragon_url = paragon_get_file_url( paragon_skins_get_current_skin_dir() . 'extra-styles.css' );
		if ( '' != $paragon_url ) {
			wp_enqueue_style( 'paragon-extra-skin-' . esc_attr( paragon_skins_get_current_skin_name() ), $paragon_url, array(), null );
		}
	}
}

// Activation methods
if ( ! function_exists( 'paragon_clone_filter_activation_methods2' ) ) {
	add_filter( 'trx_addons_filter_activation_methods', 'paragon_clone_filter_activation_methods2', 11, 1 );
	function paragon_clone_filter_activation_methods2( $args ) {
		$args['elements_key'] = true;
		return $args;
	}
}