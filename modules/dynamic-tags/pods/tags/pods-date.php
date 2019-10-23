<?php
namespace AvatorElement\Modules\DynamicTags\Pods\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Date extends Pods_Base {

	public function get_name() {
		return 'pods-date';
	}

	public function get_title() {
		return __( 'Pods', 'avator-element' ) . ' ' . __( 'Date Field', 'avator-element' );
	}

	public function render() {
		$field_data = $this->get_field();
		$field = $field_data['field'];
		$value = empty( $field_data['value'] ) ? '' : $field_data['value'];

		if ( $field && ! empty( $field['type'] ) && in_array( $field['type'], [ 'date', 'datetime' ] ) ) {

			$format = $this->get_settings( 'format' );

			$timestamp = strtotime( $value );

			if ( 'human' === $format ) {
				$value = human_time_diff( $timestamp );
			} else {
				switch ( $format ) {
					case 'default':
						$date_format = get_option( 'date_format' );
						break;
					case 'custom':
						$date_format = $this->get_settings( 'custom_format' );
						break;
					default:
						$date_format = $format;
						break;
				}

				$value = date( $date_format, $timestamp );
			}
		}
		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->add_control(
			'format',
			[
				'label' => __( 'Format', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'avator-element' ),
					'F j, Y' => date( 'F j, Y' ),
					'Y-m-d' => date( 'Y-m-d' ),
					'm/d/Y' => date( 'm/d/Y' ),
					'd/m/Y' => date( 'd/m/Y' ),
					'human' => __( 'Human Readable', 'avator-element' ),
					'custom' => __( 'Custom', 'avator-element' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => __( 'Custom Format', 'avator-element' ),
				'default' => '',
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'avator-element' ) ),
				'condition' => [
					'format' => 'custom',
				],
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'datetime',
			'date',
		];
	}
}
