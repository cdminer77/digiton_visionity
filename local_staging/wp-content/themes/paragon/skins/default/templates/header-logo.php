<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_args = get_query_var( 'paragon_logo_args' );

// Site logo
$paragon_logo_type   = isset( $paragon_args['type'] ) ? $paragon_args['type'] : '';
$paragon_logo_image  = paragon_get_logo_image( $paragon_logo_type );
$paragon_logo_text   = paragon_is_on( paragon_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$paragon_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $paragon_logo_image['logo'] ) || ! empty( $paragon_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $paragon_logo_image['logo'] ) ) {
			if ( empty( $paragon_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($paragon_logo_image['logo']) && (int) $paragon_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$paragon_attr = paragon_getimagesize( $paragon_logo_image['logo'] );
				echo '<img src="' . esc_url( $paragon_logo_image['logo'] ) . '"'
						. ( ! empty( $paragon_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $paragon_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $paragon_logo_text ) . '"'
						. ( ! empty( $paragon_attr[3] ) ? ' ' . wp_kses_data( $paragon_attr[3] ) : '' )
						. '>';
			}
		} else {
			paragon_show_layout( paragon_prepare_macros( $paragon_logo_text ), '<span class="logo_text">', '</span>' );
			paragon_show_layout( paragon_prepare_macros( $paragon_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
