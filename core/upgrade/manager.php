<?php
namespace AvatorElement\Core\Upgrade;

use Elementor\Core\Upgrade\Manager as Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends Upgrades_Manager {

	public function get_action() {
		return 'avator_element_updater';
	}

	public function get_plugin_name() {
		return 'avator-element';
	}

	public function get_plugin_label() {
		return __( 'Avator Element', 'avator-element' );
	}

	public function get_new_version() {
		return AVATOR_ELEMENT_VERSION;
	}

	public function get_version_option_name() {
		return 'avator_element_version';
	}

	public function get_upgrades_class() {
		return 'AvatorElement\Core\Upgrade\Upgrades';
	}
}
