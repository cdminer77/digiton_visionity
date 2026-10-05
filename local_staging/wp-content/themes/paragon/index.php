<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package PARAGON
 * @since PARAGON 1.0
 */

$paragon_template = apply_filters( 'paragon_filter_get_template_part', paragon_blog_archive_get_template() );

if ( ! empty( $paragon_template ) && 'index' != $paragon_template ) {

	get_template_part( $paragon_template );

} else {

	paragon_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$paragon_stickies   = is_home()
								|| ( in_array( paragon_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) paragon_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$paragon_post_type  = paragon_get_theme_option( 'post_type' );
		$paragon_args       = array(
								'blog_style'     => paragon_get_theme_option( 'blog_style' ),
								'post_type'      => $paragon_post_type,
								'taxonomy'       => paragon_get_post_type_taxonomy( $paragon_post_type ),
								'parent_cat'     => paragon_get_theme_option( 'parent_cat' ),
								'posts_per_page' => paragon_get_theme_option( 'posts_per_page' ),
								'sticky'         => paragon_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $paragon_stickies )
															&& count( $paragon_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		paragon_blog_archive_start();

		do_action( 'paragon_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'paragon_action_before_page_author' );
			get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'paragon_action_after_page_author' );
		}

		if ( paragon_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'paragon_action_before_page_filters' );
			paragon_show_filters( $paragon_args );
			do_action( 'paragon_action_after_page_filters' );
		} else {
			do_action( 'paragon_action_before_page_posts' );
			paragon_show_posts( array_merge( $paragon_args, array( 'cat' => $paragon_args['parent_cat'] ) ) );
			do_action( 'paragon_action_after_page_posts' );
		}

		do_action( 'paragon_action_blog_archive_end' );

		paragon_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'paragon_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
