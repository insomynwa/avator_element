<?php
namespace AvatorElement\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use AvatorElement\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Author_Name extends Tag {

	public function get_name() {
		return 'author-name';
	}

	public function get_title() {
		return __( 'Author Name', 'avator-element' );
	}

	public function get_group() {
		return Module::AUTHOR_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		echo wp_kses_post( get_the_author() );
	}
}
