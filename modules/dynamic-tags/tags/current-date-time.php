<?php
namespace AvatorElement\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use AvatorElement\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Current_Date_Time extends Tag {
	public function get_name() {
		return 'current-date-time';
	}

	public function get_title() {
		return __( 'Current Date Time', 'avator-element' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	protected function _register_controls() {
		$this->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'avator-element' ),
					'' => __( 'None', 'avator-element' ),
					'F j, Y' => date( 'F j, Y' ),
					'Y-m-d' => date( 'Y-m-d' ),
					'm/d/Y' => date( 'm/d/Y' ),
					'd/m/Y' => date( 'd/m/Y' ),
					'custom' => __( 'Custom', 'avator-element' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'time_format',
			[
				'label' => __( 'Time Format', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', 'avator-element' ),
					'' => __( 'None', 'avator-element' ),
					'g:i a' => date( 'g:i a' ),
					'g:i A' => date( 'g:i A' ),
					'H:i' => date( 'H:i' ),
				],
				'default' => 'default',
				'condition' => [
					'date_format!' => 'custom',
				],
			]
		);

		$this->add_control(
			'custom_format',
			[
				'label' => __( 'Custom Format', 'avator-element' ),
				'default' => get_option( 'date_format' ) . ' ' . get_option( 'time_format' ),
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'avator-element' ) ),
				'condition' => [
					'date_format' => 'custom',
				],
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		if ( 'custom' === $settings['date_format'] ) {
			$format = $settings['custom_format'];
		} else {
			$date_format = $settings['date_format'];
			$time_format = $settings['time_format'];
			$format = '';

			if ( 'default' === $date_format ) {
				$date_format = get_option( 'date_format' );
			}

			if ( 'default' === $time_format ) {
				$time_format = get_option( 'time_format' );
			}

			if ( $date_format ) {
				$format = $date_format;
				$has_date = true;
			} else {
				$has_date = false;
			}

			if ( $time_format ) {
				if ( $has_date ) {
					$format .= ' ';
				}
				$format .= $time_format;
			}
		}

		$value = date_i18n( $format );

		echo wp_kses_post( $value );
	}
}
