<?php
namespace AvatorElement\Modules\ThemeBuilder\Conditions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class General extends Condition_Base {

	protected $sub_conditions = [
		'archive',
		'singular',
	];

	public static function get_type() {
		return 'general';
	}

	public function get_name() {
		return 'general';
	}

	public function get_label() {
		return __( 'General', 'avator-element' );
	}

	public function get_all_label() {
		return __( 'Entire Site', 'avator-element' );
	}

	public function check( $args ) {
		return true;
	}
}
