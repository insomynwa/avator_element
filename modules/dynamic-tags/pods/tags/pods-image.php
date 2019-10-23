<?php
namespace AvatorElement\Modules\DynamicTags\Pods\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;
use AvatorElement\Modules\DynamicTags\Pods\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pods_Image extends Data_Tag {

	public function get_name() {
		return 'pods-image';
	}

	public function get_title() {
		return __( 'Pods', 'avator-element' ) . ' ' . __( 'Image Field', 'avator-element' );
	}

	public function get_group() {
		return Module::PODS_GROUP;
	}

	public function get_categories() {
		return [ Module::IMAGE_CATEGORY ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		$key = $this->get_settings( 'key' );

		$image_data = $this->get_settings( 'fallback' );

		if ( empty( $key ) ) {
			return $image_data;
		}

		list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );
		/**
		 * @var \Pods
		 */
		$pod = pods( $pod_name, get_the_ID() );

		if ( false === $pod ) {
			return [];
		}

		$image = $pod->field( $meta_key );

		$image_data = [
			'id' => empty( $image['ID'] ) ? $image_data['id'] : $image['ID'],
			'url' => empty( $image['guid'] ) ? $image_data['url'] : $image['guid'],
		];

		return $image_data;
	}

	protected function _register_controls() {
		$this->add_control(
			'key',
			[
				'label' => __( 'Key', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'groups' => Module::get_control_options( $this->get_supported_fields() ),
			]
		);

		$this->add_control(
			'fallback',
			[
				'label' => __( 'Fallback', 'avator-element' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}

	protected function get_supported_fields() {
		return [
			'pods_image',
		];
	}
}