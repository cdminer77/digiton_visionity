<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */

// Footer sidebar
$paragon_footer_name    = paragon_get_theme_option( 'footer_widgets' );
$paragon_footer_present = ! paragon_is_off( $paragon_footer_name ) && is_active_sidebar( $paragon_footer_name );
if ( $paragon_footer_present ) {
	paragon_storage_set( 'current_sidebar', 'footer' );
	$paragon_footer_wide = paragon_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $paragon_footer_name ) ) {
		dynamic_sidebar( $paragon_footer_name );
	}
	$paragon_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $paragon_out ) ) {
		$paragon_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $paragon_out );
		$paragon_need_columns = true;   //or check: strpos($paragon_out, 'columns_wrap')===false;
		if ( $paragon_need_columns ) {
			$paragon_columns = max( 0, (int) paragon_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $paragon_columns ) {
				$paragon_columns = min( 4, max( 1, paragon_tags_count( $paragon_out, 'aside' ) ) );
			}
			if ( $paragon_columns > 1 ) {
				$paragon_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $paragon_columns ) . ' widget', $paragon_out );
			} else {
				$paragon_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $paragon_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'paragon_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $paragon_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $paragon_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'paragon_action_before_sidebar', 'footer' );
				paragon_show_layout( $paragon_out );
				do_action( 'paragon_action_after_sidebar', 'footer' );
				if ( $paragon_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $paragon_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'paragon_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
