<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

// Page (category, tag, archive, author) title

if ( paragon_need_page_title() ) {
	paragon_sc_layouts_showed( 'title', true );
	paragon_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								paragon_show_post_meta(
									apply_filters(
										'paragon_filter_post_meta_args', array(
											'components' => join( ',', paragon_array_get_keys_by_value( paragon_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', paragon_array_get_keys_by_value( paragon_get_theme_option( 'counters' ) ) ),
											'seo'        => paragon_is_on( paragon_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$paragon_blog_title           = paragon_get_blog_title();
							$paragon_blog_title_text      = '';
							$paragon_blog_title_class     = '';
							$paragon_blog_title_link      = '';
							$paragon_blog_title_link_text = '';
							if ( is_array( $paragon_blog_title ) ) {
								$paragon_blog_title_text      = $paragon_blog_title['text'];
								$paragon_blog_title_class     = ! empty( $paragon_blog_title['class'] ) ? ' ' . $paragon_blog_title['class'] : '';
								$paragon_blog_title_link      = ! empty( $paragon_blog_title['link'] ) ? $paragon_blog_title['link'] : '';
								$paragon_blog_title_link_text = ! empty( $paragon_blog_title['link_text'] ) ? $paragon_blog_title['link_text'] : '';
							} else {
								$paragon_blog_title_text = $paragon_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $paragon_blog_title_class ); ?>">
								<?php
								$paragon_top_icon = paragon_get_term_image_small();
								if ( ! empty( $paragon_top_icon ) ) {
									$paragon_attr = paragon_getimagesize( $paragon_top_icon );
									?>
									<img src="<?php echo esc_url( $paragon_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'paragon' ); ?>"
										<?php
										if ( ! empty( $paragon_attr[3] ) ) {
											paragon_show_layout( $paragon_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $paragon_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $paragon_blog_title_link ) && ! empty( $paragon_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $paragon_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $paragon_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'paragon_action_breadcrumbs' );
						$paragon_breadcrumbs = ob_get_contents();
						ob_end_clean();
						paragon_show_layout( $paragon_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
