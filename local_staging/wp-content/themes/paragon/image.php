<?php
/**
 * The template to display the attachment
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */


get_header();

while ( have_posts() ) {
	the_post();

	// Display post's content
	get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/content', 'single-' . paragon_get_theme_option( 'single_style' ) ), 'single-' . paragon_get_theme_option( 'single_style' ) );

	// Parent post navigation.
	$paragon_posts_navigation = paragon_get_theme_option( 'posts_navigation' );
	if ( 'links' == $paragon_posts_navigation ) {
		?>
		<div class="nav-links-single<?php
			if ( ! paragon_is_off( paragon_get_theme_option( 'posts_navigation_fixed', 0 ) ) ) {
				echo ' nav-links-fixed fixed';
			}
		?>">
			<?php
			the_post_navigation( apply_filters( 'paragon_filter_post_navigation_args', array(
					'prev_text' => '<span class="nav-arrow"></span>'
						. '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Published in', 'paragon' ) . '</span> '
						. '<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'paragon' ) . '</span> '
						. '<h5 class="post-title">%title</h5>'
						. '<span class="post_date">%date</span>',
			), 'image' ) );
			?>
		</div>
		<?php
	}

	// Comments
	do_action( 'paragon_action_before_comments' );
	comments_template();
	do_action( 'paragon_action_after_comments' );
}

get_footer();
