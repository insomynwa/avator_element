<?php
namespace AvatorElement\Modules\Posts;

use AvatorElement\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'posts';
	}

	public function get_widgets() {
		return [
			'Posts',
			'Portfolio',
		];
	}
}
