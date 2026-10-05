<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_template_args = get_query_var( 'paragon_template_args' );
$paragon_columns = 1;
if ( is_array( $paragon_template_args ) ) {
	$paragon_columns    = empty( $paragon_template_args['columns'] ) ? 1 : max( 1, $paragon_template_args['columns'] );
	$paragon_blog_style = array( $paragon_template_args['type'], $paragon_columns );
	if ( ! empty( $paragon_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $paragon_columns > 1 ) {
	    $paragon_columns_class = paragon_get_column_class( 1, $paragon_columns, ! empty( $paragon_template_args['columns_tablet']) ? $paragon_template_args['columns_tablet'] : '', ! empty($paragon_template_args['columns_mobile']) ? $paragon_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $paragon_columns_class ); ?>">
		<?php
	}
} else {
	$paragon_template_args = array();
}
$paragon_expanded    = ! paragon_sidebar_present() && paragon_get_theme_option( 'expand_content' ) == 'expand';
$paragon_post_format = get_post_format();
$paragon_post_format = empty( $paragon_post_format ) ? 'standard' : str_replace( 'post-format-', '', $paragon_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $paragon_post_format ) );
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
								: array_map( 'trim', explode( ',', $paragon_template_args['meta_parts'] ) )
								)
							: paragon_array_get_keys_by_value( paragon_get_theme_option( 'meta_parts' ) );
	paragon_show_post_featured( apply_filters( 'paragon_filter_args_featured',
		array(
			'no_links'   => ! empty( $paragon_template_args['no_links'] ),
			'hover'      => $paragon_hover,
			'meta_parts' => $paragon_components,
			'thumb_size' => ! empty( $paragon_template_args['thumb_size'] )
							? $paragon_template_args['thumb_size']
							: paragon_get_thumb_size( strpos( paragon_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $paragon_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$paragon_template_args
	) );

	// Title and post meta
	$paragon_show_title = get_the_title() != '';
	$paragon_show_meta  = count( $paragon_components ) > 0 && ! in_array( $paragon_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $paragon_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'paragon_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'paragon_action_before_post_title' );
				if ( empty( $paragon_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'paragon_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'paragon_filter_show_blog_excerpt', empty( $paragon_template_args['hide_excerpt'] ) && paragon_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'paragon_filter_show_blog_meta', $paragon_show_meta, $paragon_components, 'excerpt' ) ) {
				if ( count( $paragon_components ) > 0 ) {
					do_action( 'paragon_action_before_post_meta' );
					paragon_show_post_meta(
						apply_filters(
							'paragon_filter_post_meta_args', array(
								'components' => join( ',', $paragon_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'paragon_action_after_post_meta' );
				}
			}

			if ( paragon_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'paragon_action_before_full_post_content' );
					the_content( '' );
					do_action( 'paragon_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'paragon' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'paragon' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				paragon_show_post_content( $paragon_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'paragon_filter_show_blog_readmore',  ! isset( $paragon_template_args['more_button'] ) || ! empty( $paragon_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $paragon_template_args['no_links'] ) ) {
					do_action( 'paragon_action_before_post_readmore' );
					if ( paragon_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						paragon_show_post_more_link( $paragon_template_args, '<p>', '</p>' );
					} else {
						paragon_show_post_comments_link( $paragon_template_args, '<p>', '</p>' );
					}
					do_action( 'paragon_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $paragon_template_args ) ) {
	if ( ! empty( $paragon_template_args['slider'] ) || $paragon_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
