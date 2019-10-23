<?php
namespace AvatorElement\Modules\NavMenu;

use AvatorElement\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Nav_Menu',
		];
	}

	public function get_name() {
		return 'nav-menu';
	}
}
