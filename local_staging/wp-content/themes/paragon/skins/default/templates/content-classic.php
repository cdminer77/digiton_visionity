<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_template_args = get_query_var( 'paragon_template_args' );

if ( is_array( $paragon_template_args ) ) {
	$paragon_columns    = empty( $paragon_template_args['columns'] ) ? 2 : max( 1, $paragon_template_args['columns'] );
	$paragon_blog_style = array( $paragon_template_args['type'], $paragon_columns );
    $paragon_columns_class = paragon_get_column_class( 1, $paragon_columns, ! empty( $paragon_template_args['columns_tablet']) ? $paragon_template_args['columns_tablet'] : '', ! empty($paragon_template_args['columns_mobile']) ? $paragon_template_args['columns_mobile'] : '' );
} else {
	$paragon_template_args = array();
	$paragon_blog_style = explode( '_', paragon_get_theme_option( 'blog_style' ) );
	$paragon_columns    = empty( $paragon_blog_style[1] ) ? 2 : max( 1, $paragon_blog_style[1] );
    $paragon_columns_class = paragon_get_column_class( 1, $paragon_columns );
}
$paragon_expanded   = ! paragon_sidebar_present() && paragon_get_theme_option( 'expand_content' ) == 'expand';

$paragon_post_format = get_post_format();
$paragon_post_format = empty( $paragon_post_format ) ? 'standard' : str_replace( 'post-format-', '', $paragon_post_format );

?><div class="<?php
	if ( ! empty( $paragon_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( paragon_is_blog_style_use_masonry( $paragon_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $paragon_columns ) : esc_attr( $paragon_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $paragon_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $paragon_columns )
				. ' post_layout_' . esc_attr( $paragon_blog_style[0] )
				. ' post_layout_' . esc_attr( $paragon_blog_style[0] ) . '_' . esc_attr( $paragon_columns )
	);
	paragon_add_blog_animation( $paragon_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$paragon_hover      = ! empty( $paragon_template_args['hover'] ) && ! paragon_is_inherit( $paragon_template_args['hover'] )
							? $paragon_template_args['hover']
							: paragon_get_theme_option( 'image_hover' );

	$paragon_components = ! empty( $paragon_template_args['meta_parts'] )
							? ( is_array( $paragon_template_args['meta_parts'] )
								? $paragon_template_args['meta_parts']
								: explode( ',', $paragon_template_args['meta_parts'] )
								)
							: paragon_array_get_keys_by_value( paragon_get_theme_option( 'meta_parts' ) );

	paragon_show_post_featured( apply_filters( 'paragon_filter_args_featured',
		array(
			'thumb_size' => ! empty( $paragon_template_args['thumb_size'] )
				? $paragon_template_args['thumb_size']
				: paragon_get_thumb_size(
					'classic' == $paragon_blog_style[0]
						? ( strpos( paragon_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $paragon_columns > 2 ? 'big' : 'huge' )
								: ( $paragon_columns > 2
									? ( $paragon_expanded ? 'square' : 'square' )
									: ($paragon_columns > 1 ? 'square' : ( $paragon_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( paragon_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $paragon_columns > 2 ? 'masonry-big' : 'full' )
								: ($paragon_columns === 1 ? ( $paragon_expanded ? 'huge' : 'big' ) : ( $paragon_columns <= 2 && $paragon_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $paragon_hover,
			'meta_parts' => $paragon_components,
			'no_links'   => ! empty( $paragon_template_args['no_links'] ),
        ),
        'content-classic',
        $paragon_template_args
    ) );

	// Title and post meta
	$paragon_show_title = get_the_title() != '';
	$paragon_show_meta  = count( $paragon_components ) > 0 && ! in_array( $paragon_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $paragon_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'paragon_filter_show_blog_meta', $paragon_show_meta, $paragon_components, 'classic' ) ) {
				if ( count( $paragon_components ) > 0 ) {
					do_action( 'paragon_action_before_post_meta' );
					paragon_show_post_meta(
						apply_filters(
							'paragon_filter_post_meta_args', array(
							'components' => join( ',', $paragon_components ),
							'seo'        => false,
							'echo'       => true,
						), $paragon_blog_style[0], $paragon_columns
						)
					);
					do_action( 'paragon_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'paragon_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'paragon_action_before_post_title' );
				if ( empty( $paragon_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'paragon_action_after_post_title' );
			}

			if( !in_array( $paragon_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'paragon_filter_show_blog_readmore', ! $paragon_show_title || ! empty( $paragon_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $paragon_template_args['no_links'] ) ) {
						do_action( 'paragon_action_before_post_readmore' );
						paragon_show_post_more_link( $paragon_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'paragon_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $paragon_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('paragon_filter_show_blog_excerpt', empty($paragon_template_args['hide_excerpt']) && paragon_get_theme_option('excerpt_length') > 0, 'classic')) {
			paragon_show_post_content($paragon_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $paragon_template_args['more_button'] )) {
			if ( empty( $paragon_template_args['no_links'] ) ) {
				do_action( 'paragon_action_before_post_readmore' );
				paragon_show_post_more_link( $paragon_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'paragon_action_after_post_readmore' );
			}
		}
		$paragon_content = ob_get_contents();
		ob_end_clean();
		paragon_show_layout($paragon_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
