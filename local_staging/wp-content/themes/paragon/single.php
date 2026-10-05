<?php
/**
 * The template to display single post
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

// Full post loading
$full_post_loading          = paragon_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = paragon_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = paragon_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$paragon_related_position   = paragon_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$paragon_posts_navigation   = paragon_get_theme_option( 'posts_navigation' );
$paragon_prev_post          = false;
$paragon_prev_post_same_cat = (int)paragon_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( paragon_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	paragon_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'paragon_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $paragon_posts_navigation ) {
		$paragon_prev_post = get_previous_post( $paragon_prev_post_same_cat );  // Get post from same category
		if ( ! $paragon_prev_post && $paragon_prev_post_same_cat ) {
			$paragon_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $paragon_prev_post ) {
			$paragon_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $paragon_prev_post ) ) {
		paragon_sc_layouts_showed( 'featured', false );
		paragon_sc_layouts_showed( 'title', false );
		paragon_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $paragon_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/content', 'single-' . paragon_get_theme_option( 'single_style' ) ), 'single-' . paragon_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $paragon_related_position, 'inside' ) === 0 ) {
		$paragon_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'paragon_action_related_posts' );
		$paragon_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $paragon_related_content ) ) {
			$paragon_related_position_inside = max( 0, min( 9, paragon_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $paragon_related_position_inside ) {
				$paragon_related_position_inside = mt_rand( 1, 9 );
			}

			$paragon_p_number         = 0;
			$paragon_related_inserted = false;
			$paragon_in_block         = false;
			$paragon_content_start    = strpos( $paragon_content, '<div class="post_content' );
			$paragon_content_end      = strrpos( $paragon_content, '</div>' );

			for ( $i = max( 0, $paragon_content_start ); $i < min( strlen( $paragon_content ) - 3, $paragon_content_end ); $i++ ) {
				if ( $paragon_content[ $i ] != '<' ) {
					continue;
				}
				if ( $paragon_in_block ) {
					if ( strtolower( substr( $paragon_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$paragon_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $paragon_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $paragon_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$paragon_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $paragon_content[ $i + 1 ] && in_array( $paragon_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$paragon_p_number++;
					if ( $paragon_related_position_inside == $paragon_p_number ) {
						$paragon_related_inserted = true;
						$paragon_content = ( $i > 0 ? substr( $paragon_content, 0, $i ) : '' )
											. $paragon_related_content
											. substr( $paragon_content, $i );
					}
				}
			}
			if ( ! $paragon_related_inserted ) {
				if ( $paragon_content_end > 0 ) {
					$paragon_content = substr( $paragon_content, 0, $paragon_content_end ) . $paragon_related_content . substr( $paragon_content, $paragon_content_end );
				} else {
					$paragon_content .= $paragon_related_content;
				}
			}
		}

		paragon_show_layout( $paragon_content );
	}

	// Comments
	do_action( 'paragon_action_before_comments' );
	comments_template();
	do_action( 'paragon_action_after_comments' );

	// Related posts
	if ( 'below_content' == $paragon_related_position
		&& ( 'scroll' != $paragon_posts_navigation || (int)paragon_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)paragon_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'paragon_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $paragon_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $paragon_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $paragon_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $paragon_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'paragon_action_nav_links_single_scroll_data', $paragon_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
