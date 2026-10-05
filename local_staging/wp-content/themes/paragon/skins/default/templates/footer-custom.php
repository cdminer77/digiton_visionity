<?php
/**
 * The template to display default site footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */

$paragon_footer_id = paragon_get_custom_footer_id();
$paragon_footer_meta = get_post_meta( $paragon_footer_id, 'trx_addons_options', true );
if ( ! empty( $paragon_footer_meta['margin'] ) ) {
	paragon_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( paragon_prepare_css_value( $paragon_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $paragon_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $paragon_footer_id ) ) ); ?>
						<?php
						$paragon_footer_scheme = paragon_get_theme_option( 'footer_scheme' );
						if ( ! empty( $paragon_footer_scheme ) && ! paragon_is_inherit( $paragon_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $paragon_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'paragon_action_show_layout', $paragon_footer_id );
	?>
</footer><!-- /.footer_wrap -->
