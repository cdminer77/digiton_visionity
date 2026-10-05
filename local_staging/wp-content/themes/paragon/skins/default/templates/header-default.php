<?php
/**
 * The template to display default site header
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_header_css   = '';
$paragon_header_image = get_header_image();
$paragon_header_video = paragon_get_header_video();
if ( ! empty( $paragon_header_image ) && paragon_trx_addons_featured_image_override( is_singular() || paragon_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$paragon_header_image = paragon_get_current_mode_image( $paragon_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $paragon_header_image ) || ! empty( $paragon_header_video ) ? ' with_bg_image' : ' without_bg_image';
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

	// Main menu
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( paragon_is_on( paragon_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
