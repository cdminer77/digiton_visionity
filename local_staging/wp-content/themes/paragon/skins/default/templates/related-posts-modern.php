<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_link        = get_permalink();
$paragon_post_format = get_post_format();
$paragon_post_format = empty( $paragon_post_format ) ? 'standard' : str_replace( 'post-format-', '', $paragon_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $paragon_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	paragon_show_post_featured(
		array(
			'thumb_size'    => apply_filters( 'paragon_filter_related_thumb_size', paragon_get_thumb_size( (int) paragon_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'big' ) ),
			'post_info'     => '<div class="post_header entry-header">'
									. '<div class="post_categories">' . wp_kses( paragon_get_post_categories( '' ), 'paragon_kses_content' ) . '</div>'
									. '<h6 class="post_title entry-title"><a href="' . esc_url( $paragon_link ) . '">'
										. wp_kses_data( '' == get_the_title() ? esc_html__( '- No title -', 'paragon' ) : get_the_title() )
									. '</a></h6>'
									. ( in_array( get_post_type(), array( 'post', 'attachment' ) )
											? '<div class="post_meta"><a href="' . esc_url( $paragon_link ) . '" class="post_meta_item post_date">' . wp_kses_data( paragon_get_date() ) . '</a></div>'
											: '' )
								. '</div>',
		)
	);
	?>
</div>
