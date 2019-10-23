<?php
namespace AvatorElement\Modules\Countdown\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use AvatorElement\Base\Base_Widget;
use AvatorElement\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Countdown extends Base_Widget {

	public function get_name() {
		return 'countdown';
	}

	public function get_title() {
		return __( 'Countdown', 'avator-element' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_keywords() {
		return [ 'countdown', 'number', 'timer', 'time', 'date', 'evergreen' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_countdown',
			[
				'label' => __( 'Countdown', 'avator-element' ),
			]
		);

		$this->add_control(
			'countdown_type',
			[
				'label' => __( 'Type', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'due_date' => __( 'Due Date', 'avator-element' ),
					'evergreen' => __( 'Evergreen Timer', 'avator-element' ),
				],
				'default' => 'due_date',
			]
		);

		$this->add_control(
			'due_date',
			[
				'label' => __( 'Due Date', 'avator-element' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				/* translators: %s: Time zone. */
				'description' => sprintf( __( 'Date set according to your timezone: %s.', 'avator-element' ), Utils::get_timezone_string() ),
				'condition' => [
					'countdown_type' => 'due_date',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_hours',
			[
				'label' => __( 'Hours', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 47,
				'placeholder' => __( 'Hours', 'avator-element' ),
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'evergreen_counter_minutes',
			[
				'label' => __( 'Minutes', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 59,
				'placeholder' => __( 'Minutes', 'avator-element' ),
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);

		$this->add_control(
			'label_display',
			[
				'label' => __( 'View', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'block' => __( 'Block', 'avator-element' ),
					'inline' => __( 'Inline', 'avator-element' ),
				],
				'default' => 'block',
				'prefix_class' => 'elementor-countdown--label-',
			]
		);

		$this->add_control(
			'show_days',
			[
				'label' => __( 'Days', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_hours',
			[
				'label' => __( 'Hours', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_minutes',
			[
				'label' => __( 'Minutes', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_seconds',
			[
				'label' => __( 'Seconds', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Show Label', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label' => __( 'Custom Label', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_days',
			[
				'label' => __( 'Days', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Days', 'avator-element' ),
				'placeholder' => __( 'Days', 'avator-element' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_days' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_hours',
			[
				'label' => __( 'Hours', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hours', 'avator-element' ),
				'placeholder' => __( 'Hours', 'avator-element' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_hours' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_minutes',
			[
				'label' => __( 'Minutes', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Minutes', 'avator-element' ),
				'placeholder' => __( 'Minutes', 'avator-element' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_minutes' => 'yes',
				],
			]
		);

		$this->add_control(
			'label_seconds',
			[
				'label' => __( 'Seconds', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Seconds', 'avator-element' ),
				'placeholder' => __( 'Seconds', 'avator-element' ),
				'condition' => [
					'show_labels!' => '',
					'custom_labels!' => '',
					'show_seconds' => 'yes',
				],
			]
		);

		$this->add_control(
			'expire_actions',
			[
				'label' => __( 'Actions After Expire', 'avator-element' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'redirect' => __( 'Redirect', 'avator-element' ),
					'hide' => __( 'Hide', 'avator-element' ),
					'message' => __( 'Show Message', 'avator-element' ),
				],
				'label_block' => true,
				'separator' => 'before',
				'render_type' => 'none',
				'multiple' => true,
			]
		);

		$this->add_control(
			'message_after_expire',
			[
				'label' => __( 'Message', 'avator-element' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);

		$this->add_control(
			'expire_redirect_url',
			[
				'label' => __( 'Redirect URL', 'avator-element' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'separator' => 'before',
				'show_external' => false,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'expire_actions' => 'redirect',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Boxes', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => __( 'Container Width', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label' => __( 'Background Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .elementor-countdown-item',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_spacing',
			[
				'label' => __( 'Space Between', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-countdown-item:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-countdown-item:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => __( 'Padding', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_digits',
			[
				'label' => __( 'Digits', 'avator-element' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'digits_color',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'digits_typography',
				'selector' => '{{WRAPPER}} .elementor-countdown-digits',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'avator-element' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-countdown-label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_expire_message_style',
			[
				'label' => __( 'Message', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'expire_actions' => 'message',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'avator-element' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'avator-element' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'avator-element' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'avator-element' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-countdown-expire--message',
			]
		);

		$this->add_responsive_control(
			'message_padding',
			[
				'label' => __( 'Padding', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-countdown-expire--message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function get_strftime( $instance ) {
		$string = '';
		if ( $instance['show_days'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_days', 'elementor-countdown-days' );
		}
		if ( $instance['show_hours'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_hours', 'elementor-countdown-hours' );
		}
		if ( $instance['show_minutes'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_minutes', 'elementor-countdown-minutes' );
		}
		if ( $instance['show_seconds'] ) {
			$string .= $this->render_countdown_item( $instance, 'label_seconds', 'elementor-countdown-seconds' );
		}

		return $string;
	}

	private $_default_countdown_labels;

	private function init_default_countdown_labels() {
		$this->_default_countdown_labels = [
			'label_months' => __( 'Months', 'avator-element' ),
			'label_weeks' => __( 'Weeks', 'avator-element' ),
			'label_days' => __( 'Days', 'avator-element' ),
			'label_hours' => __( 'Hours', 'avator-element' ),
			'label_minutes' => __( 'Minutes', 'avator-element' ),
			'label_seconds' => __( 'Seconds', 'avator-element' ),
		];
	}

	public function get_default_countdown_labels() {
		if ( ! $this->_default_countdown_labels ) {
			$this->init_default_countdown_labels();
		}

		return $this->_default_countdown_labels;
	}

	private function render_countdown_item( $instance, $label, $part_class ) {
		$string = '<div class="elementor-countdown-item"><span class="elementor-countdown-digits ' . $part_class . '"></span>';

		if ( $instance['show_labels'] ) {
			$default_labels = $this->get_default_countdown_labels();
			$label = ( $instance['custom_labels'] ) ? $instance[ $label ] : $default_labels[ $label ];
			$string .= ' <span class="elementor-countdown-label">' . $label . '</span>';
		}

		$string .= '</div>';

		return $string;
	}

	private function get_evergreen_interval( $instance ) {
		$hours = empty( $instance['evergreen_counter_hours'] ) ? 0 : ( $instance['evergreen_counter_hours'] * HOUR_IN_SECONDS );
		$minutes = empty( $instance['evergreen_counter_minutes'] ) ? 0 : ( $instance['evergreen_counter_minutes'] * MINUTE_IN_SECONDS );
		$evergreen_interval = $hours + $minutes;

		return $evergreen_interval;
	}

	private function get_actions( $settings ) {
		if ( empty( $settings['expire_actions'] ) || ! is_array( $settings['expire_actions'] ) ) {
			return false;
		}

		$actions = [];

		foreach ( $settings['expire_actions'] as $action ) {
			$action_to_run = [ 'type' => $action ];
			if ( 'redirect' === $action ) {
				if ( empty( $settings['expire_redirect_url']['url'] ) ) {
					continue;
				}
				$action_to_run['redirect_url'] = $settings['expire_redirect_url']['url'];
			}
			$actions[] = $action_to_run;
		}

		return $actions;
	}

	protected function render() {
		$instance = $this->get_settings_for_display();
		$due_date = $instance['due_date'];
		$string = $this->get_strftime( $instance );

		if ( 'evergreen' === $instance['countdown_type'] ) {
			$this->add_render_attribute( 'div', 'data-evergreen-interval', $this->get_evergreen_interval( $instance ) );
		} else {
			// Handle timezone ( we need to set GMT time )
			$gmt = get_gmt_from_date( $due_date . ':00' );
			$due_date = strtotime( $gmt );
		}

		$actions = false;

		if ( ! Plugin::elementor()->editor->is_edit_mode() ) {
			$actions = $this->get_actions( $instance );
		}

		if ( $actions ) {
			$this->add_render_attribute( 'div', 'data-expire-actions', json_encode( $actions ) );
		}

		$this->add_render_attribute( 'div', [
			'class' => 'elementor-countdown-wrapper',
			'data-date' => $due_date,
		] );

		?>
		<div <?php echo $this->get_render_attribute_string( 'div' ); ?>>
			<?php echo $string; ?>
		</div>
		<?php
		if ( $actions && is_array( $actions ) ) {
			foreach ( $actions as $action ) {
				if ( 'message' !== $action['type'] ) {
					continue;
				}
				echo '<div class="elementor-countdown-expire--message">' . $instance['message_after_expire'] . '</div>';
			}
		}
	}
}
