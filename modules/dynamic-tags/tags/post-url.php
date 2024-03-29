<?php
namespace AvatorElement\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Data_Tag;
use AvatorElement\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Post_URL extends Data_Tag {

	public function get_name() {
		return 'post-url';
	}

	public function get_title() {
		return __( 'Post URL', 'avator-element' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		return get_permalink();
	}
}
