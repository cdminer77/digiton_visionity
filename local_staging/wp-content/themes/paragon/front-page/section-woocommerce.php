<?php
$paragon_woocommerce_sc = paragon_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $paragon_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$paragon_scheme = paragon_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $paragon_scheme ) && ! paragon_is_inherit( $paragon_scheme ) ) {
			echo ' scheme_' . esc_attr( $paragon_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( paragon_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( paragon_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$paragon_css      = '';
			$paragon_bg_image = paragon_get_theme_option( 'front_page_woocommerce_bg_image' );
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
		$paragon_anchor_icon = paragon_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$paragon_anchor_text = paragon_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $paragon_anchor_icon ) || ! empty( $paragon_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $paragon_anchor_icon ) ? ' icon="' . esc_attr( $paragon_anchor_icon ) . '"' : '' )
											. ( ! empty( $paragon_anchor_text ) ? ' title="' . esc_attr( $paragon_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( paragon_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' paragon-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$paragon_css      = '';
				$paragon_bg_mask  = paragon_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$paragon_bg_color_type = paragon_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $paragon_bg_color_type ) {
					$paragon_bg_color = paragon_get_theme_option( 'front_page_woocommerce_bg_color' );
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
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$paragon_caption     = paragon_get_theme_option( 'front_page_woocommerce_caption' );
				$paragon_description = paragon_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $paragon_caption ) || ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $paragon_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $paragon_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $paragon_caption, 'paragon_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $paragon_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $paragon_description ), 'paragon_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $paragon_woocommerce_sc ) {
						$paragon_woocommerce_sc_ids      = paragon_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$paragon_woocommerce_sc_per_page = count( explode( ',', $paragon_woocommerce_sc_ids ) );
					} else {
						$paragon_woocommerce_sc_per_page = max( 1, (int) paragon_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$paragon_woocommerce_sc_columns = max( 1, min( $paragon_woocommerce_sc_per_page, (int) paragon_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$paragon_woocommerce_sc}"
										. ( 'products' == $paragon_woocommerce_sc
												? ' ids="' . esc_attr( $paragon_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $paragon_woocommerce_sc
												? ' category="' . esc_attr( paragon_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $paragon_woocommerce_sc
												? ' orderby="' . esc_attr( paragon_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( paragon_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $paragon_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $paragon_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
