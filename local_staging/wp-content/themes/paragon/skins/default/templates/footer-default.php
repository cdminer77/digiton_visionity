<?php
/**
 * The template to display default site footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$paragon_footer_scheme = paragon_get_theme_option( 'footer_scheme' );
if ( ! empty( $paragon_footer_scheme ) && ! paragon_is_inherit( $paragon_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $paragon_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
