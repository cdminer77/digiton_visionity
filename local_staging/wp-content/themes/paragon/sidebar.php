<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

if ( paragon_sidebar_present() ) {
	
	$paragon_sidebar_type = paragon_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $paragon_sidebar_type && ! paragon_is_layouts_available() ) {
		$paragon_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $paragon_sidebar_type ) {
		// Default sidebar with widgets
		$paragon_sidebar_name = paragon_get_theme_option( 'sidebar_widgets' );
		paragon_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $paragon_sidebar_name ) ) {
			dynamic_sidebar( $paragon_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$paragon_sidebar_id = paragon_get_custom_sidebar_id();
		do_action( 'paragon_action_show_layout', $paragon_sidebar_id );
	}
	$paragon_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $paragon_out ) ) {
		$paragon_sidebar_position    = paragon_get_theme_option( 'sidebar_position' );
		$paragon_sidebar_position_ss = paragon_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $paragon_sidebar_position );
			echo ' sidebar_' . esc_attr( $paragon_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $paragon_sidebar_type );

			$paragon_sidebar_scheme = apply_filters( 'paragon_filter_sidebar_scheme', paragon_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $paragon_sidebar_scheme ) && ! paragon_is_inherit( $paragon_sidebar_scheme ) && 'custom' != $paragon_sidebar_type ) {
				echo ' scheme_' . esc_attr( $paragon_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="paragon_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'paragon_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $paragon_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$paragon_title = apply_filters( 'paragon_filter_sidebar_control_title', 'float' == $paragon_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'paragon' ) : '' );
				$paragon_text  = apply_filters( 'paragon_filter_sidebar_control_text', 'above' == $paragon_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'paragon' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $paragon_title ); ?>"><?php echo esc_html( $paragon_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'paragon_action_before_sidebar', 'sidebar' );
				paragon_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $paragon_out ) );
				do_action( 'paragon_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'paragon_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
