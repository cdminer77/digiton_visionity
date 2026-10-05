<?php
/**
 * The template to display the background video in the header
 *
 * @package PARAGON
 * @since PARAGON 1.0.14
 */
$paragon_header_video = paragon_get_header_video();
$paragon_embed_video  = '';
if ( ! empty( $paragon_header_video ) && ! paragon_is_from_uploads( $paragon_header_video ) ) {
	if ( paragon_is_youtube_url( $paragon_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $paragon_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php paragon_show_layout( paragon_get_embed_video( $paragon_header_video ) ); ?></div>
		<?php
	}
}
