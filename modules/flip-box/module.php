<?php
namespace AvatorElement\Modules\FlipBox;

use AvatorElement\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Flip_Box',
		];
	}

	public function get_name() {
		return 'flip-box';
	}
}
