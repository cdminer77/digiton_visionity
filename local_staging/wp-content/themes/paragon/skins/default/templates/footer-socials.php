<?php
/**
 * The template to display the socials in the footer
 *
 * @package PARAGON
 * @since PARAGON 1.0.10
 */


// Socials
if ( paragon_is_on( paragon_get_theme_option( 'socials_in_footer' ) ) ) {
	$paragon_output = paragon_get_socials_links();
	if ( '' != $paragon_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php paragon_show_layout( $paragon_output ); ?>
			</div>
		</div>
		<?php
	}
}
