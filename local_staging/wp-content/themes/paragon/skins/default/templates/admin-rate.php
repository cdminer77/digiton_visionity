<?php
/**
 * The template to display Admin notices
 *
 * @package PARAGON
 * @since PARAGON 1.0.1
 */

$paragon_theme_slug = get_template();
$paragon_theme_obj  = wp_get_theme( $paragon_theme_slug );

?>
<div class="paragon_admin_notice paragon_rate_notice notice notice-info is-dismissible" data-notice="rate">
	<?php
	// Theme image
	$paragon_theme_img = paragon_get_file_url( 'screenshot.jpg' );
	if ( '' != $paragon_theme_img ) {
		?>
		<div class="paragon_notice_image"><img src="<?php echo esc_url( $paragon_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'paragon' ); ?>"></div>
		<?php
	}

	// Title
	$paragon_theme_name = '"' . $paragon_theme_obj->get( 'Name' ) . ( PARAGON_THEME_FREE ? ' ' . __( 'Free', 'paragon' ) : '' ) . '"';
	?>
	<h3 class="paragon_notice_title"><a href="<?php echo esc_url( paragon_storage_get( 'theme_rate_url' ) ); ?>" target="_blank">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name to the 'Welcome' message
				__( 'Help Us Grow - Rate %s Today!', 'paragon' ),
				$paragon_theme_name
			)
		);
		?>
	</a></h3>
	<?php

	// Description
	?>
	<div class="paragon_notice_text">
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Thank you for choosing the %s theme for your website! We're excited to see how you've customized your site, and we hope you've enjoyed working with our theme.", 'paragon' ), $paragon_theme_name ) );
		?></p>
		<p><?php
			// Translators: Add theme name to the 'Welcome' message
			echo wp_kses_data( sprintf( __( "Your feedback really matters to us! If you've had a positive experience, we'd love for you to take a moment to rate %s and share your thoughts on the customer service you received.", 'paragon' ), $paragon_theme_name ) );
		?></p>
	</div>
	<?php

	// Buttons
	?>
	<div class="paragon_notice_buttons">
		<?php
		// Link to the theme download page
		?>
		<a href="<?php echo esc_url( paragon_storage_get( 'theme_rate_url' ) ); ?>" class="button button-primary" target="_blank"><i class="dashicons dashicons-star-filled"></i> 
			<?php
			// Translators: Add the theme name to the button caption
			echo esc_html( sprintf( __( 'Rate %s Now', 'paragon' ), $paragon_theme_name ) );
			?>
		</a>
		<?php
		// Link to the theme support
		?>
		<a href="<?php echo esc_url( paragon_storage_get( 'theme_support_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-sos"></i> 
			<?php
			esc_html_e( 'Support', 'paragon' );
			?>
		</a>
		<?php
		// Link to the theme documentation
		?>
		<a href="<?php echo esc_url( paragon_storage_get( 'theme_doc_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-book"></i> 
			<?php
			esc_html_e( 'Documentation', 'paragon' );
			?>
		</a>
	</div>
</div>
