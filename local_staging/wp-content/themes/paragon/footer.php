<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

							do_action( 'paragon_action_page_content_end_text' );
							
							// Widgets area below the content
							paragon_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'paragon_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'paragon_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'paragon_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'paragon_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$paragon_body_style = paragon_get_theme_option( 'body_style' );
					$paragon_widgets_name = paragon_get_theme_option( 'widgets_below_page', 'hide' );
					$paragon_show_widgets = ! paragon_is_off( $paragon_widgets_name ) && is_active_sidebar( $paragon_widgets_name );
					$paragon_show_related = paragon_is_single() && paragon_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $paragon_show_widgets || $paragon_show_related ) {
						if ( 'fullscreen' != $paragon_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $paragon_show_related ) {
							do_action( 'paragon_action_related_posts' );
						}

						// Widgets area below page content
						if ( $paragon_show_widgets ) {
							paragon_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $paragon_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'paragon_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'paragon_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! paragon_is_singular( 'post' ) && ! paragon_is_singular( 'attachment' ) ) || ! in_array ( paragon_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="paragon_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'paragon_action_before_footer' );

				// Footer
				$paragon_footer_type = paragon_get_theme_option( 'footer_type' );
				if ( 'custom' == $paragon_footer_type && ! paragon_is_layouts_available() ) {
					$paragon_footer_type = 'default';
				}
				get_template_part( apply_filters( 'paragon_filter_get_template_part', "templates/footer-" . sanitize_file_name( $paragon_footer_type ) ) );

				do_action( 'paragon_action_after_footer' );

			}
			?>

			<?php do_action( 'paragon_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'paragon_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'paragon_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>