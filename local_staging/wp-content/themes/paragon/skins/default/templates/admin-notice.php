<?php
/**
 * The template to display Admin notices
 *
 * @package PARAGON
 * @since PARAGON 1.0.1
 */

$paragon_theme_slug = get_option( 'template' );
$paragon_theme_obj  = wp_get_theme( $paragon_theme_slug );
?>
<div class="paragon_admin_notice paragon_welcome_notice notice notice-info is-dismissible" data-notice="admin">
	<?php
	// Theme image
	$paragon_theme_img = paragon_get_file_url( 'screenshot.jpg' );
	if ( '' != $paragon_theme_img ) {
		?>
		<div class="paragon_notice_image"><img src="<?php echo esc_url( $paragon_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'paragon' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="paragon_notice_title">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'paragon' ),
				$paragon_theme_obj->get( 'Name' ) . ( PARAGON_THEME_FREE ? ' ' . __( 'Free', 'paragon' ) : '' ),
				$paragon_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="paragon_notice_text">
		<p class="paragon_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $paragon_theme_obj->description ) );
			?>
		</p>
		<p class="paragon_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'paragon' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="paragon_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=paragon_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'paragon' );
			?>
		</a>
	</div>
</div>
