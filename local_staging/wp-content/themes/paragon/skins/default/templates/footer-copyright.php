<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$paragon_copyright_scheme = paragon_get_theme_option( 'copyright_scheme' );
if ( ! empty( $paragon_copyright_scheme ) && ! paragon_is_inherit( $paragon_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $paragon_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$paragon_copyright = paragon_get_theme_option( 'copyright' );
			if ( ! empty( $paragon_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$paragon_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $paragon_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$paragon_copyright = paragon_prepare_macros( $paragon_copyright );
				// Display copyright
				echo wp_kses( nl2br( $paragon_copyright ), 'paragon_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
