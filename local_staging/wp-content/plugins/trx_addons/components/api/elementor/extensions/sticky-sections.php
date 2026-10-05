<?php
/**
 * Elementor extension: Sticky sections support
 *
 * @package ThemeREX Addons
 * @since v2.36.0
 */

// Don't load directly
if ( ! defined( 'TRX_ADDONS_VERSION' ) ) {
	exit;
}

if ( ! function_exists( 'trx_addons_elm_add_params_sticky_section' ) ) {
	add_action( 'elementor/element/before_section_start', 'trx_addons_elm_add_params_sticky_section', 10, 3 );
	/**
	 * Add parameters 'Sticky section' to the Elementor sections and containers
	 * to add the ability to fix sections at the top of the window on the page scroll.
	 * 
	 * @hooked elementor/element/before_section_end
	 *
	 * @param object $element     Elementor element
	 * @param string $section_id  Section ID
	 * @param array $args         Section arguments
	 */
	function trx_addons_elm_add_params_sticky_section( $element, $section_id, $args ) {

		if ( ! is_object( $element ) ) {
			return;
		}
		
		$el_name = $element->get_name();

		// Add 'Sticky section' to the sections and containers
		if ( in_array( $el_name, array( 'section', 'container' ) ) && $section_id == '_section_responsive' ) {
			
			// Register controls
			$element->start_controls_section( 'section_sticky_section', array(
																		'label' => __( 'Sticky section', 'trx_addons' ),
																		'tab' => \Elementor\Controls_Manager::TAB_ADVANCED
																	) );
			
			$element->add_control( 'row_fixed', array(
									'label' => __("Make this row sticky", 'trx_addons'),
									'label_block' => false,
									'type' => \Elementor\Controls_Manager::SELECT,
									'options' => array(
										"" => esc_html__("Disable", 'trx_addons'),
										"fixed" => esc_html__("On large screen ", 'trx_addons'),
										"fixed sc_layouts_row_fixed_always" => esc_html__("Always", 'trx_addons')
									),
									'default' => '',
									'prefix_class' => 'sc_layouts_row_'
									) );

			$element->add_control( 'row_fixed_delay', array(
									'label' => __("Sticky delay", 'trx_addons'),
									'label_block' => false,
									'description' => __( 'Enable this option to delay the sticky section activation until the specified offset is reached.', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'condition' => array(
										'row_fixed!' => ''
									),
									'default' => '',
									'return_value' => 'delay_fixed',
									'separator' => 'before',
									'prefix_class' => 'sc_layouts_row_'
									) );

			$element->add_control( 'row_fixed_delay_offset', array(
									'label' => __( 'Offset (%)', 'trx_addons' ),
									'description' => __( 'Offset from the top of the window (in % of window height) before the sticky section becomes active. If set to 0 or left empty, the default offset is used.', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SLIDER,
									'default' => array(
										'size' => 75,
										'unit' => '%'
									),
									'size_units' => array( '%' ),
									'range' => array(
										'%' => array(
											'min' => 0,
											'max' => 200,
											'step' => 1
										),
									),
									'condition' => array(
										'row_fixed_delay' => 'delay_fixed',
									),
								) );

			$element->add_control( 'row_hide_unfixed', array(
									'label' => __("Hide if not sticky", 'trx_addons'),
									'label_block' => false,
									'description' => __( 'Keeps this row hidden at the top of the page. It will appear only after scrolling, when sticky behavior activates.', 'trx_addons' ),
									'type' => \Elementor\Controls_Manager::SWITCHER,
									'condition' => array(
										'row_fixed!' => ''
									),
									'default' => '',
									'return_value' => 'hide_unfixed',
									'prefix_class' => 'sc_layouts_row_'
									) );

			if ( apply_filters( 'trx_addons_filter_allow_sc_styles_in_elementor', false, 'sc_layouts_fixed_row' ) ) {
				$element->add_control( 'row_fixed_bg_heading', array(
					'label' => __( 'Sticky Appearance', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'row_fixed!' => ''
					),
				) );

				$element->add_group_control(
					\Elementor\Group_Control_Background::get_type(),
					array(
						'name'     => 'row_fixed_bg',
						'types'    => array( 'classic', 'gradient' ),
						'selector' => 'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on',
						'condition' => array(
							'row_fixed!' => ''
						),
					) );

				$element->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					array(
						'name'        => 'row_fixed_border',
						'label'       => __( 'Border', 'trx_addons' ),
						'placeholder' => '1px',
						'default'     => '1px',
						'selector'    => 'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on',
					'condition' => array(
						'row_fixed!' => ''
					),
					)
				);

				// $element->add_responsive_control(
				// 	'row_fixed_border_radius',
				// 	array(
				// 		'label'      => __( 'Border Radius', 'trx_addons' ),
				// 		'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				// 		'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				// 		'selectors'  => array(
				// 			'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				// 		),
				// 		'condition' => array(
				// 			'row_fixed!' => ''
				// 		),
				// 	)
				// );

				$element->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					array(
						'name'     => 'row_fixed_shadow',
						'selector' => 'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on',
						'condition' => array(
							'row_fixed!' => ''
						),
					)
				);

				$element->add_responsive_control(
					'row_fixed_padding',
					array(
						'label'      => __( 'Padding', 'trx_addons' ),
						'type'       => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
						'selectors'  => array(
							'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on.elementor-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'body.trx_addons_page_scrolled {{WRAPPER}}.sc_layouts_row_fixed_on.e-con' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
						),
						'condition' => array(
							'row_fixed!' => ''
						),
					)
				);
			}
	
			$element->end_controls_section();
		}
	}
}

if ( ! function_exists( 'trx_addons_elm_add_params_sticky_section_before_render' ) ) {
	// Before Elementor 2.1.0
	add_action( 'elementor/frontend/element/before_render',  'trx_addons_elm_add_params_sticky_section_before_render', 10, 1 );
	// After Elementor 2.1.0
	add_action( 'elementor/frontend/section/before_render', 'trx_addons_elm_add_params_sticky_section_before_render', 10, 1 );
	// After Elementor 3.16.0
	add_action( 'elementor/frontend/container/before_render', 'trx_addons_elm_add_params_sticky_section_before_render', 10, 1 );
	/**
	 * Add data-attributes to the wrapper of the Elementor sections and containers with a sticky settings if 'Sticky section' enabled.
	 * 
	 * @hooked elementor/frontend/element/before_render
	 * @hooked elementor/frontend/section/before_render
	 * @hooked elementor/frontend/container/before_render
	 * 
	 * @param object $element Current element
	 */
	function trx_addons_elm_add_params_sticky_section_before_render( $element ) {
		if ( is_object( $element ) ) {
			$el_name = $element->get_name();
			if ( 'section' == $el_name || 'container' == $el_name ) {
				$delay_enabled = $element->get_settings( 'row_fixed_delay' );
				if ( ! empty( $delay_enabled ) ) {
					// Add data-attributes to the wrapper
					$settings = $element->get_settings();
					if ( isset( $settings['row_fixed_delay_offset']['size'] ) && $settings['row_fixed_delay_offset']['size'] !== '' ) {
						$element->add_render_attribute( '_wrapper', 'data-fixed-row-delay', max( 0, $settings['row_fixed_delay_offset']['size'] / 100 ) );
					}
				}
			}
		}
	}
}


/*
 * --------------------------------------------------------------------------------------
 * Add a widget-specific parameters to modify its behavior when used in a sticky section.
 * ---------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'trx_addons_elm_add_params_sticky_to_layouts_logo' ) ) {
	add_action( 'elementor/element/after_section_end', 'trx_addons_elm_add_params_sticky_to_layouts_logo', 10, 3 );
	/**
	 * Add parameters 'Sticky' to the widget 'Layouts Logo' to add the ability to customize its behavior when used in a sticky section.
	 * 
	 * @hooked elementor/element/after_section_end
	 *
	 * @param object $element     Elementor element
	 * @param string $section_id  Section ID
	 * @param array $args         Section arguments
	 */
	function trx_addons_elm_add_params_sticky_to_layouts_logo( $element, $section_id, $args ) {

		if ( ! is_object( $element ) ) {
			return;
		}
		
		$el_name = $element->get_name();

		// Add 'Sticky section' to the sections and containers
		if ( $el_name == 'trx_sc_layouts_logo' && $section_id == 'section_sc_layouts_logo' ) {
			
			// Register controls
			$element->start_controls_section( 'section_sc_layouts_logo_sticky', array(
																		'label' => __( 'Sticky behaviour', 'trx_addons' )	// . trx_addons_get_theme_doc_link( '#elementor_extension_sticky_sections' ),
																	) );

			$element->add_responsive_control(
				'logo_height_sticky',
				[
					'label' => __( 'Max height', 'trx_addons' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => '',
						'unit' => 'px'
					],
					'size_units' => ['px', 'em', 'rem', 'vw', 'vh', 'custom'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200
						],
						'em' => [
							'min' => 0,
							'max' => 20
						],
						'rem' => [
							'min' => 0,
							'max' => 20
						]
					],
					'selectors' => [
						'body.trx_addons_page_scrolled .sc_layouts_row_fixed.sc_layouts_row_fixed_on .elementor-element-{{ID}} .logo_image' => 'max-height: {{SIZE}}{{UNIT}};',
					]
				]
			);
	
			$element->end_controls_section();
		}
	}
}
