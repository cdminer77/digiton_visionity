<div class="front_page_section front_page_section_contacts<?php
	$paragon_scheme = paragon_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $paragon_scheme ) && ! paragon_is_inherit( $paragon_scheme ) ) {
		echo ' scheme_' . esc_attr( $paragon_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( paragon_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( paragon_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$paragon_css      = '';
		$paragon_bg_image = paragon_get_theme_option( 'front_page_contacts_bg_image' );
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
	$paragon_anchor_icon = paragon_get_theme_option( 'front_page_contacts_anchor_icon' );
	$paragon_anchor_text = paragon_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $paragon_anchor_icon ) || ! empty( $paragon_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $paragon_anchor_icon ) ? ' icon="' . esc_attr( $paragon_anchor_icon ) . '"' : '' )
									. ( ! empty( $paragon_anchor_text ) ? ' title="' . esc_attr( $paragon_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( paragon_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' paragon-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$paragon_css      = '';
			$paragon_bg_mask  = paragon_get_theme_option( 'front_page_contacts_bg_mask' );
			$paragon_bg_color_type = paragon_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $paragon_bg_color_type ) {
				$paragon_bg_color = paragon_get_theme_option( 'front_page_contacts_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$paragon_caption     = paragon_get_theme_option( 'front_page_contacts_caption' );
			$paragon_description = paragon_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $paragon_caption ) || ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $paragon_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $paragon_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $paragon_caption, 'paragon_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $paragon_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $paragon_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $paragon_description ), 'paragon_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$paragon_content = paragon_get_theme_option( 'front_page_contacts_content' );
			$paragon_layout  = paragon_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $paragon_layout && ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $paragon_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $paragon_content, 'paragon_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $paragon_layout && ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$paragon_sc = paragon_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $paragon_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $paragon_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					paragon_show_layout( do_shortcode( $paragon_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $paragon_layout && ( ! empty( $paragon_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
