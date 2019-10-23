<?php
namespace AvatorElement\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag;
use AvatorElement\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return __( 'User Info', 'avator-element' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'avator-element' ),
					'id' => __( 'ID', 'avator-element' ),
					'display_name' => __( 'Display Name', 'avator-element' ),
					'login' => __( 'Username', 'avator-element' ),
					'first_name' => __( 'First Name', 'avator-element' ),
					'last_name' => __( 'Last Name', 'avator-element' ),
					'description' => __( 'Bio', 'avator-element' ),
					'email' => __( 'Email', 'avator-element' ),
					'url' => __( 'Website', 'avator-element' ),
					'meta' => __( 'User Meta', 'avator-element' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'avator-element' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}
}
