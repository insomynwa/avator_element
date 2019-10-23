<?php

namespace AvatorElement\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Triggers extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_triggers';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'triggers' );

		$this->start_settings_group( 'page_load', __( 'On Page Load', 'avator-element' ) );

		$this->add_settings_group_control(
			'delay',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'avator-element' ) . ' (sec)',
				'default' => 0,
				'min' => 0,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling', __( 'On Scroll', 'avator-element' ) );

		$this->add_settings_group_control(
			'direction',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Direction', 'avator-element' ),
				'default' => 'down',
				'options' => [
					'down' => __( 'Down', 'avator-element' ),
					'up' => __( 'Up', 'avator-element' ),
				],
			]
		);

		$this->add_settings_group_control(
			'offset',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'avator-element' ) . ' (%)',
				'default' => 50,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'direction' => 'down',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling_to', __( 'On Scroll To Element', 'avator-element' ) );

		$this->add_settings_group_control(
			'selector',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Selector', 'avator-element' ),
				'placeholder' => '.my-class',
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'click', __( 'On Click', 'avator-element' ) );

		$this->add_settings_group_control(
			'times',
			[
				'label' => __( 'Clicks', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'inactivity', __( 'After Inactivity', 'avator-element' ) );

		$this->add_settings_group_control(
			'time',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Within', 'avator-element' ) . ' (sec)',
				'default' => 30,
				'min' => 1,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'exit_intent', __( 'On Page Exit Intent', 'avator-element' ) );

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
