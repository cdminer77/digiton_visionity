<div class="front_page_section front_page_section_blog<?php
	$paragon_scheme = paragon_get_theme_option( 'front_page_blog_scheme' );
	if ( ! empty( $paragon_scheme ) && ! paragon_is_inherit( $paragon_scheme ) ) {
		echo ' scheme_' . esc_attr( $paragon_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( paragon_get_theme_option( 'front_page_blog_paddings' ) );
	if ( paragon_get_theme_option( 'front_page_blog_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$paragon_css      = '';
		$paragon_bg_image = paragon_get_theme_option( 'front_page_blog_bg_image' );
		if ( ! empty( $paragon_bg_image ) ) {
			$paragon_css .= 'background-image: url(' . esc_url( paragon_get_attachment_url( $paragon_bg_image ) ) . ');';
		}
		if ( ! empty( $paragon_css ) ) {
			echo ' style="' . esc_attr( $paragon_css ) . '"';
		}
		?>
>
<?php
	// Add anchor
	$paragon_anchor_icon = paragon_get_theme_option( 'front_page_blog_anchor_icon' );
	$paragon_anchor_text = paragon_get_theme_option( 'front_page_blog_anchor_text' );
if ( ( ! empty( $paragon_anchor_icon ) || ! empty( $paragon_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_blog"'
									. ( ! empty( $paragon_anchor_icon ) ? ' icon="' . esc_attr( $paragon_anchor_icon ) . '"' : '' )
									. ( ! empty( $paragon_anchor_text ) ? ' title="' . esc_attr( $paragon_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_blog_inner
	<?php
	if ( paragon_get_theme_option( 'front_page_blog_fullheight' ) ) {
		echo ' paragon-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$paragon_css      = '';
			$paragon_bg_mask  = paragon_get_theme_option( 'front_page_blog_bg_mask' );
			$paragon_bg_color_type = paragon_get_theme_option( 'front_page_blog_bg_color_type' );
			if ( 'custom' == $paragon_bg_color_type ) {
				$paragon_bg_color = paragon_get_theme_option( 'front_page_blog_bg_color' );
			} elseif ( 'scheme_bg_color' == $paragon_bg_color_type ) {
				$paragon_bg_color = paragon_get_scheme_color( 'bg_color', $paragon_scheme );
			} else {
				$paragon_bg_color = '';
			}
			if ( ! empty( $paragon_bg_color ) && $paragon_bg_mask > 0 ) {
				$paragon_css .= 'background-color: ' . esc_attr(
					1 == $paragon_bg_mask ? $paragon_bg_color : paragon_hex2rgba( $paragon_bg_color, $paragon_bg_mask )
				) . ';';
			}
			if ( ! empty( $paragon_css ) ) {
				echo ' style="' . esc_attr( $paragon_css ) . '"';
			}
			?>
	>
		<div class="front_page_section_content_wrap front_page_section_blog_content_wrap content_wrap">
			<?php
			// Caption
			$paragon_caption = paragon_get_theme_option( 'front_page_blog_caption' );
			if ( ! empty( $paragon_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<h2 class="front_page_section_caption front_page_section_blog_caption front_page_block_<?php echo ! empty( $paragon_caption ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( $paragon_caption, 'paragon_kses_content' ); ?></h2>
				<?php
			}

			// Description (text)
			$paragon_description = paragon_get_theme_option( 'front_page_blog_description' );
			if ( ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_description front_page_section_blog_description front_page_block_<?php echo ! empty( $paragon_description ) ? 'filled' : 'empty'; ?>"><?php echo wp_kses( wpautop( $paragon_description ), 'paragon_kses_content' ); ?></div>
				<?php
			}

			// Content (widgets)
			?>
			<div class="front_page_section_output front_page_section_blog_output">
				<?php
				if ( is_active_sidebar( 'front_page_blog_widgets' ) ) {
					dynamic_sidebar( 'front_page_blog_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! paragon_exists_trx_addons() ) {
						paragon_customizer_need_trx_addons_message();
					} else {
						paragon_customizer_need_widgets_message( 'front_page_blog_caption', 'ThemeREX Addons - Blogger' );
					}
				}
				?>
			</div>
		</div>
	</div>
</div>
