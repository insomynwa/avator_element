<?php
namespace AvatorElement\Modules\Forms\Actions;

use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use AvatorElement\Modules\Forms\Classes\Action_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Redirect extends Action_Base {

	public function get_name() {
		return 'redirect';
	}

	public function get_label() {
		return __( 'Redirect', 'avator-element' );
	}

	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_redirect',
			[
				'label' => __( 'Redirect', 'avator-element' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'redirect_to',
			[
				'label' => __( 'Redirect To', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'avator-element' ),
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::TEXT_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'label_block' => true,
				'render_type' => 'none',
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$widget->end_controls_section();
	}

	public function on_export( $element ) {
		unset(
			$element['settings']['redirect_to']
		);

		return $element;
	}

	public function run( $record, $ajax_handler ) {
		$redirect_to = $record->get_form_settings( 'redirect_to' );

		$redirect_to = $record->replace_setting_shortcodes( $redirect_to, true );

		if ( ! empty( $redirect_to ) && filter_var( $redirect_to, FILTER_VALIDATE_URL ) ) {
			$ajax_handler->add_response_data( 'redirect_url', $redirect_to );
		}
	}
}
