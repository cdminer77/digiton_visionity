<?php
/**
 * The template to display the site logo in the footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */

// Logo
if ( paragon_is_on( paragon_get_theme_option( 'logo_in_footer' ) ) ) {
	$paragon_logo_image = paragon_get_logo_image( 'footer' );
	$paragon_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $paragon_logo_image['logo'] ) || ! empty( $paragon_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $paragon_logo_image['logo'] ) ) {
					$paragon_attr = paragon_getimagesize( $paragon_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $paragon_logo_image['logo'] ) . '"'
								. ( ! empty( $paragon_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $paragon_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'paragon' ) . '"'
								. ( ! empty( $paragon_attr[3] ) ? ' ' . wp_kses_data( $paragon_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $paragon_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $paragon_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
