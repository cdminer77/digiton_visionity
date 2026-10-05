<?php
/**
 * The template to display Admin notices
 *
 * @package PARAGON
 * @since PARAGON 1.0.64
 */

$paragon_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$paragon_skins_args = get_query_var( 'paragon_skins_notice_args' );
?>
<div class="paragon_admin_notice paragon_skins_notice notice notice-info is-dismissible" data-notice="skins">
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
		<?php esc_html_e( 'New skins are available', 'paragon' ); ?>
	</h3>
	<?php

	// Description
	$paragon_total      = $paragon_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$paragon_skins_msg  = $paragon_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $paragon_total, 'paragon' ), $paragon_total ) . '</strong>'
							: '';
	$paragon_total      = $paragon_skins_args['free'];
	$paragon_skins_msg .= $paragon_total > 0
							? ( ! empty( $paragon_skins_msg ) ? ' ' . esc_html__( 'and', 'paragon' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $paragon_total, 'paragon' ), $paragon_total ) . '</strong>'
							: '';
	$paragon_total      = $paragon_skins_args['pay'];
	$paragon_skins_msg .= $paragon_skins_args['pay'] > 0
							? ( ! empty( $paragon_skins_msg ) ? ' ' . esc_html__( 'and', 'paragon' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $paragon_total, 'paragon' ), $paragon_total ) . '</strong>'
							: '';
	?>
	<div class="paragon_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'paragon' ), $paragon_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="paragon_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $paragon_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'paragon' );
			?>
		</a>
	</div>
</div>
