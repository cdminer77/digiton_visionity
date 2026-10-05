<div class="front_page_section front_page_section_googlemap<?php
	$paragon_scheme = paragon_get_theme_option( 'front_page_googlemap_scheme' );
	if ( ! empty( $paragon_scheme ) && ! paragon_is_inherit( $paragon_scheme ) ) {
		echo ' scheme_' . esc_attr( $paragon_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( paragon_get_theme_option( 'front_page_googlemap_paddings' ) );
	if ( paragon_get_theme_option( 'front_page_googlemap_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$paragon_css      = '';
		$paragon_bg_image = paragon_get_theme_option( 'front_page_googlemap_bg_image' );
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
	$paragon_anchor_icon = paragon_get_theme_option( 'front_page_googlemap_anchor_icon' );
	$paragon_anchor_text = paragon_get_theme_option( 'front_page_googlemap_anchor_text' );
if ( ( ! empty( $paragon_anchor_icon ) || ! empty( $paragon_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_googlemap"'
									. ( ! empty( $paragon_anchor_icon ) ? ' icon="' . esc_attr( $paragon_anchor_icon ) . '"' : '' )
									. ( ! empty( $paragon_anchor_text ) ? ' title="' . esc_attr( $paragon_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_googlemap_inner
		<?php
		$paragon_layout = paragon_get_theme_option( 'front_page_googlemap_layout' );
		echo ' front_page_section_layout_' . esc_attr( $paragon_layout );
		if ( paragon_get_theme_option( 'front_page_googlemap_fullheight' ) ) {
			echo ' paragon-full-height sc_layouts_flex sc_layouts_columns_middle';
		}
		?>
		"
			<?php
			$paragon_css      = '';
			$paragon_bg_mask  = paragon_get_theme_option( 'front_page_googlemap_bg_mask' );
			$paragon_bg_color_type = paragon_get_theme_option( 'front_page_googlemap_bg_color_type' );
			if ( 'custom' == $paragon_bg_color_type ) {
				$paragon_bg_color = paragon_get_theme_option( 'front_page_googlemap_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_googlemap_content_wrap
		<?php
		if ( 'fullwidth' != $paragon_layout ) {
			echo ' content_wrap';
		}
		?>
		">
			<?php
			// Content wrap with title and description
			$paragon_caption     = paragon_get_theme_option( 'front_page_googlemap_caption' );
			$paragon_description = paragon_get_theme_option( 'front_page_googlemap_description' );
			if ( ! empty( $paragon_caption ) || ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'fullwidth' == $paragon_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}
					// Caption
				if ( ! empty( $paragon_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_googlemap_caption front_page_block_<?php echo ! empty( $paragon_caption ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $paragon_caption, 'paragon_kses_content' );
					?>
					</h2>
					<?php
				}

					// Description (text)
				if ( ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_googlemap_description front_page_block_<?php echo ! empty( $paragon_description ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( wpautop( $paragon_description ), 'paragon_kses_content' );
					?>
					</div>
					<?php
				}
				if ( 'fullwidth' == $paragon_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$paragon_content = paragon_get_theme_option( 'front_page_googlemap_content' );
			if ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				if ( 'columns' == $paragon_layout ) {
					?>
					<div class="front_page_section_columns front_page_section_googlemap_columns columns_wrap">
						<div class="column-1_3">
					<?php
				} elseif ( 'fullwidth' == $paragon_layout ) {
					?>
					<div class="content_wrap">
					<?php
				}

				?>
				<div class="front_page_section_content front_page_section_googlemap_content front_page_block_<?php echo ! empty( $paragon_content ) ? 'filled' : 'empty'; ?>">
				<?php
					echo wp_kses( $paragon_content, 'paragon_kses_content' );
				?>
				</div>
				<?php

				if ( 'columns' == $paragon_layout ) {
					?>
					</div><div class="column-2_3">
					<?php
				} elseif ( 'fullwidth' == $paragon_layout ) {
					?>
					</div>
					<?php
				}
			}

			// Widgets output
			?>
			<div class="front_page_section_output front_page_section_googlemap_output">
				<?php
				if ( is_active_sidebar( 'front_page_googlemap_widgets' ) ) {
					dynamic_sidebar( 'front_page_googlemap_widgets' );
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					if ( ! paragon_exists_trx_addons() ) {
						paragon_customizer_need_trx_addons_message();
					} else {
						paragon_customizer_need_widgets_message( 'front_page_googlemap_caption', 'ThemeREX Addons - Google map' );
					}
				}
				?>
			</div>
			<?php

			if ( 'columns' == $paragon_layout && ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>
		</div>
	</div>
</div>
