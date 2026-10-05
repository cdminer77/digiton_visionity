<?php
/**
 * The Header: Logo and main menu
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( paragon_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'paragon_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'paragon_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('paragon_action_body_wrap_attributes'); ?>>

		<?php do_action( 'paragon_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'paragon_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('paragon_action_page_wrap_attributes'); ?>>

			<?php do_action( 'paragon_action_page_wrap_start' ); ?>

			<?php
			$paragon_full_post_loading = ( paragon_is_singular( 'post' ) || paragon_is_singular( 'attachment' ) ) && paragon_get_value_gp( 'action' ) == 'full_post_loading';
			$paragon_prev_post_loading = ( paragon_is_singular( 'post' ) || paragon_is_singular( 'attachment' ) ) && paragon_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $paragon_full_post_loading && ! $paragon_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="paragon_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'paragon_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'paragon' ); ?></a>
				<?php if ( paragon_sidebar_present() ) { ?>
				<a class="paragon_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'paragon_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'paragon' ); ?></a>
				<?php } ?>
				<a class="paragon_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'paragon_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'paragon' ); ?></a>

				<?php
				do_action( 'paragon_action_before_header' );

				// Header
				$paragon_header_type = paragon_get_theme_option( 'header_type' );
				if ( 'custom' == $paragon_header_type && ! paragon_is_layouts_available() ) {
					$paragon_header_type = 'default';
				}
				get_template_part( apply_filters( 'paragon_filter_get_template_part', "templates/header-" . sanitize_file_name( $paragon_header_type ) ) );

				// Side menu
				if ( in_array( paragon_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'paragon_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'paragon_action_after_header' );

			}
			?>

			<?php do_action( 'paragon_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( paragon_is_off( paragon_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $paragon_header_type ) ) {
						$paragon_header_type = paragon_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $paragon_header_type && paragon_is_layouts_available() ) {
						$paragon_header_id = paragon_get_custom_header_id();
						if ( $paragon_header_id > 0 ) {
							$paragon_header_meta = paragon_get_custom_layout_meta( $paragon_header_id );
							if ( ! empty( $paragon_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$paragon_footer_type = paragon_get_theme_option( 'footer_type' );
					if ( 'custom' == $paragon_footer_type && paragon_is_layouts_available() ) {
						$paragon_footer_id = paragon_get_custom_footer_id();
						if ( $paragon_footer_id ) {
							$paragon_footer_meta = paragon_get_custom_layout_meta( $paragon_footer_id );
							if ( ! empty( $paragon_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'paragon_action_page_content_wrap_class', $paragon_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'paragon_filter_is_prev_post_loading', $paragon_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( paragon_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'paragon_action_page_content_wrap_data', $paragon_prev_post_loading );
			?>>
				<?php
				do_action( 'paragon_action_page_content_wrap', $paragon_full_post_loading || $paragon_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'paragon_filter_single_post_header', paragon_is_singular( 'post' ) || paragon_is_singular( 'attachment' ) ) ) {
					if ( $paragon_prev_post_loading ) {
						if ( paragon_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'paragon_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$paragon_path = apply_filters( 'paragon_filter_get_template_part', 'templates/single-styles/' . paragon_get_theme_option( 'single_style' ) );
					if ( paragon_get_file_dir( $paragon_path . '.php' ) != '' ) {
						get_template_part( $paragon_path );
					}
				}

				// Widgets area above page
				$paragon_body_style   = paragon_get_theme_option( 'body_style' );
				$paragon_widgets_name = paragon_get_theme_option( 'widgets_above_page', 'hide' );
				$paragon_show_widgets = ! paragon_is_off( $paragon_widgets_name ) && is_active_sidebar( $paragon_widgets_name );
				if ( $paragon_show_widgets ) {
					if ( 'fullscreen' != $paragon_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					paragon_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $paragon_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'paragon_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $paragon_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'paragon_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'paragon_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="paragon_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( paragon_is_singular( 'post' ) || paragon_is_singular( 'attachment' ) )
							&& $paragon_prev_post_loading 
							&& paragon_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'paragon_action_between_posts' );
						}

						// Widgets area above content
						paragon_create_widgets_area( 'widgets_above_content' );

						do_action( 'paragon_action_page_content_start_text' );
