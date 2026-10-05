<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package PARAGON
 * @since PARAGON 1.0.50
 */

$paragon_template_args = get_query_var( 'paragon_template_args' );
if ( is_array( $paragon_template_args ) ) {
	$paragon_columns    = empty( $paragon_template_args['columns'] ) ? 2 : max( 1, $paragon_template_args['columns'] );
	$paragon_blog_style = array( $paragon_template_args['type'], $paragon_columns );
} else {
	$paragon_template_args = array();
	$paragon_blog_style = explode( '_', paragon_get_theme_option( 'blog_style' ) );
	$paragon_columns    = empty( $paragon_blog_style[1] ) ? 2 : max( 1, $paragon_blog_style[1] );
}
$paragon_blog_id       = paragon_get_custom_blog_id( join( '_', $paragon_blog_style ) );
$paragon_blog_style[0] = str_replace( 'blog-custom-', '', $paragon_blog_style[0] );
$paragon_expanded      = ! paragon_sidebar_present() && paragon_get_theme_option( 'expand_content' ) == 'expand';
$paragon_components    = ! empty( $paragon_template_args['meta_parts'] )
							? ( is_array( $paragon_template_args['meta_parts'] )
								? join( ',', $paragon_template_args['meta_parts'] )
								: $paragon_template_args['meta_parts']
								)
							: paragon_array_get_keys_by_value( paragon_get_theme_option( 'meta_parts' ) );
$paragon_post_format   = get_post_format();
$paragon_post_format   = empty( $paragon_post_format ) ? 'standard' : str_replace( 'post-format-', '', $paragon_post_format );

$paragon_blog_meta     = paragon_get_custom_layout_meta( $paragon_blog_id );
$paragon_custom_style  = ! empty( $paragon_blog_meta['scripts_required'] ) ? $paragon_blog_meta['scripts_required'] : 'none';

if ( ! empty( $paragon_template_args['slider'] ) || $paragon_columns > 1 || ! paragon_is_off( $paragon_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $paragon_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( paragon_is_off( $paragon_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $paragon_custom_style ) ) . "-1_{$paragon_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $paragon_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $paragon_columns )
					. ' post_layout_' . esc_attr( $paragon_blog_style[0] )
					. ' post_layout_' . esc_attr( $paragon_blog_style[0] ) . '_' . esc_attr( $paragon_columns )
					. ( ! paragon_is_off( $paragon_custom_style )
						? ' post_layout_' . esc_attr( $paragon_custom_style )
							. ' post_layout_' . esc_attr( $paragon_custom_style ) . '_' . esc_attr( $paragon_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'paragon_action_show_layout', $paragon_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $paragon_template_args['slider'] ) || $paragon_columns > 1 || ! paragon_is_off( $paragon_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
