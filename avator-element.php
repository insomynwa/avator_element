<?php
/**
 * Plugin Name: Avator Element
 * Description: Elementor add-on plugin
 * Author: Mr.Lorem
 * Version: 2.7.2
 *
 * Text Domain: avator-element
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'AVATOR_ELEMENT_VERSION', '2.7.2' );
define( 'AVATOR_ELEMENT_PREVIOUS_STABLE_VERSION', '2.6.5' );

define( 'AVATOR_ELEMENT__FILE__', __FILE__ );
define( 'AVATOR_ELEMENT_PLUGIN_BASE', plugin_basename( AVATOR_ELEMENT__FILE__ ) );
define( 'AVATOR_ELEMENT_PATH', plugin_dir_path( AVATOR_ELEMENT__FILE__ ) );
define( 'AVATOR_ELEMENT_ASSETS_PATH', AVATOR_ELEMENT_PATH . 'assets/' );
define( 'AVATOR_ELEMENT_MODULES_PATH', AVATOR_ELEMENT_PATH . 'modules/' );
define( 'AVATOR_ELEMENT_URL', plugins_url( '/', AVATOR_ELEMENT__FILE__ ) );
define( 'AVATOR_ELEMENT_ASSETS_URL', AVATOR_ELEMENT_URL . 'assets/' );
define( 'AVATOR_ELEMENT_MODULES_URL', AVATOR_ELEMENT_URL . 'modules/' );

/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function avator_element_load_plugin() {
	load_plugin_textdomain( 'avator-element' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'avator_element_fail_load' );

		return;
	}

	$elementor_version_required = '2.7.3';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'avator_element_fail_load_out_of_date' );

		return;
	}

	$elementor_version_recommendation = '2.7.3';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_recommendation, '>=' ) ) {
		add_action( 'admin_notices', 'avator_element_admin_notice_upgrade_recommendation' );
	}

	require AVATOR_ELEMENT_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'avator_element_load_plugin' );

/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0.0
 *
 * @return void
 */
function avator_element_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

		$message = '<p>' . __( 'Avator Element is not working because you need to activate the Elementor plugin.', 'avator-element' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'avator-element' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

		$message = '<p>' . __( 'Avator Element is not working because you need to install the Elementor plugin.', 'avator-element' ) . '</p>';
		$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'avator-element' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>';
}

function avator_element_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Avator Element is not working because you are using an old version of Elementor.', 'avator-element' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'avator-element' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

function avator_element_admin_notice_upgrade_recommendation() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'A new version of Elementor is available. For better performance and compatibility of Avator Element, we recommend updating to the latest version.', 'avator-element' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'avator-element' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}

if ( ! function_exists( '_is_elementor_installed' ) ) {

	function _is_elementor_installed() {
		$file_path = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
