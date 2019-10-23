<?php
namespace AvatorElement\Modules\DynamicTags\ACF\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;
use AvatorElement\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return __( 'ACF', 'avator-element' ) . ' ' . __( 'File Field', 'avator-element' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	protected function get_supported_fields() {
		return [
			'file',
		];
	}
}
