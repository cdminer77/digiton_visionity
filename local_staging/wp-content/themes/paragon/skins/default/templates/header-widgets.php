<?php
/**
 * The template to display the widgets area in the header
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

// Header sidebar
$paragon_header_name    = paragon_get_theme_option( 'header_widgets' );
$paragon_header_present = ! paragon_is_off( $paragon_header_name ) && is_active_sidebar( $paragon_header_name );
if ( $paragon_header_present ) {
	paragon_storage_set( 'current_sidebar', 'header' );
	$paragon_header_wide = paragon_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $paragon_header_name ) ) {
		dynamic_sidebar( $paragon_header_name );
	}
	$paragon_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $paragon_widgets_output ) ) {
		$paragon_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $paragon_widgets_output );
		$paragon_need_columns   = strpos( $paragon_widgets_output, 'columns_wrap' ) === false;
		if ( $paragon_need_columns ) {
			$paragon_columns = max( 0, (int) paragon_get_theme_option( 'header_columns' ) );
			if ( 0 == $paragon_columns ) {
				$paragon_columns = min( 6, max( 1, paragon_tags_count( $paragon_widgets_output, 'aside' ) ) );
			}
			if ( $paragon_columns > 1 ) {
				$paragon_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $paragon_columns ) . ' widget', $paragon_widgets_output );
			} else {
				$paragon_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $paragon_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'paragon_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $paragon_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $paragon_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'paragon_action_before_sidebar', 'header' );
				paragon_show_layout( $paragon_widgets_output );
				do_action( 'paragon_action_after_sidebar', 'header' );
				if ( $paragon_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $paragon_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'paragon_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
