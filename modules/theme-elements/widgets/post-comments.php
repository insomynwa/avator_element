<?php
namespace AvatorElement\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use AvatorElement\Modules\QueryControl\Module as QueryControlModule;
use AvatorElement\Modules\ThemeElements\Module;
use AvatorElement\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Comments extends Base {

	public function get_name() {
		return 'post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'avator-element' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'comments', 'post', 'response', 'form' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments', 'avator-element' ),
			]
		);

		$this->add_control(
			'_skin',
			[
				'type' => Controls_Manager::HIDDEN,
			]
		);

		$this->add_control(
			'skin_temp',
			[
				'label' => __( 'Skin', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Theme Comments', 'avator-element' ),
				],
				'description' => __( 'The Theme Comments skin uses the currently active theme comments design and layout to display the comment form and comments.', 'avator-element' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::SOURCE_TYPE_CURRENT_POST => __( 'Current Post', 'avator-element' ),
					Module::SOURCE_TYPE_CUSTOM => __( 'Custom', 'avator-element' ),
				],
				'default' => Module::SOURCE_TYPE_CURRENT_POST,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source_custom',
			[
				'label' => __( 'Search & Select', 'avator-element' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'label_block' => true,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_POST,
				],
				'condition' => [
					'source_type' => Module::SOURCE_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			$post_id = (int) $settings['source_custom'];
			Plugin::elementor()->db->switch_to_post( $post_id );
		}

		if ( ! comments_open() && ( Plugin::elementor()->preview->is_preview_mode() || Plugin::elementor()->editor->is_edit_mode() ) ) :
			?>
			<div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'avator-element' ); ?>
				</span>
				<span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'avator-element' ); ?>
				</span>
			</div>
			<?php
		else :
			comments_template();
		endif;

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			Plugin::elementor()->db->restore_current_post();
		}
	}
}
