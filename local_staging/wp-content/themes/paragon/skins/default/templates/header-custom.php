<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package PARAGON
 * @since PARAGON 1.0.06
 */

$paragon_header_css   = '';
$paragon_header_image = get_header_image();
$paragon_header_video = paragon_get_header_video();
if ( ! empty( $paragon_header_image ) && paragon_trx_addons_featured_image_override( is_singular() || paragon_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$paragon_header_image = paragon_get_current_mode_image( $paragon_header_image );
}

$paragon_header_id = paragon_get_custom_header_id();
$paragon_header_meta = get_post_meta( $paragon_header_id, 'trx_addons_options', true );
if ( ! empty( $paragon_header_meta['margin'] ) ) {
	paragon_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( paragon_prepare_css_value( $paragon_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $paragon_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $paragon_header_id ) ) ); ?>
				<?php
				echo ! empty( $paragon_header_image ) || ! empty( $paragon_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $paragon_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $paragon_header_image ) {
					echo ' ' . esc_attr( paragon_add_inline_css_class( 'background-image: url(' . esc_url( $paragon_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( paragon_is_on( paragon_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight paragon-full-height';
				}
				$paragon_header_scheme = paragon_get_theme_option( 'header_scheme' );
				if ( ! empty( $paragon_header_scheme ) && ! paragon_is_inherit( $paragon_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $paragon_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $paragon_header_video ) ) {
		get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'paragon_action_show_layout', $paragon_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
