<?php
/**
 * The Portfolio template to display the content
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

$paragon_post_format = get_post_format();
$paragon_post_format = empty( $paragon_post_format ) ? 'standard' : str_replace( 'post-format-', '', $paragon_post_format );

?><div class="
<?php
if ( ! empty( $paragon_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( paragon_is_blog_style_use_masonry( $paragon_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $paragon_columns ) : esc_attr( $paragon_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $paragon_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $paragon_columns )
		. ( 'portfolio' != $paragon_blog_style[0] ? ' ' . esc_attr( $paragon_blog_style[0] )  . '_' . esc_attr( $paragon_columns ) : '' )
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

	$paragon_hover   = ! empty( $paragon_template_args['hover'] ) && ! paragon_is_inherit( $paragon_template_args['hover'] )
								? $paragon_template_args['hover']
								: paragon_get_theme_option( 'image_hover' );

	if ( 'dots' == $paragon_hover ) {
		$paragon_post_link = empty( $paragon_template_args['no_links'] )
								? ( ! empty( $paragon_template_args['link'] )
									? $paragon_template_args['link']
									: get_permalink()
									)
								: '';
		$paragon_target    = ! empty( $paragon_post_link ) && paragon_is_external_url( $paragon_post_link )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$paragon_components = ! empty( $paragon_template_args['meta_parts'] )
							? ( is_array( $paragon_template_args['meta_parts'] )
								? $paragon_template_args['meta_parts']
								: explode( ',', $paragon_template_args['meta_parts'] )
								)
							: paragon_array_get_keys_by_value( paragon_get_theme_option( 'meta_parts' ) );

	// Featured image
	paragon_show_post_featured( apply_filters( 'paragon_filter_args_featured',
        array(
			'hover'         => $paragon_hover,
			'no_links'      => ! empty( $paragon_template_args['no_links'] ),
			'thumb_size'    => ! empty( $paragon_template_args['thumb_size'] )
								? $paragon_template_args['thumb_size']
								: paragon_get_thumb_size(
									paragon_is_blog_style_use_masonry( $paragon_blog_style[0] )
										? (	strpos( paragon_get_theme_option( 'body_style' ), 'full' ) !== false || $paragon_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( paragon_get_theme_option( 'body_style' ), 'full' ) !== false || $paragon_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => paragon_is_blog_style_use_masonry( $paragon_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $paragon_components,
			'class'         => 'dots' == $paragon_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $paragon_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $paragon_post_link )
												? '<a href="' . esc_url( $paragon_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $paragon_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $paragon_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $paragon_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!