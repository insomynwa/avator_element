<?php
namespace AvatorElement\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use AvatorElement\Classes\Utils;
use AvatorElement\Modules\Forms\Classes\Ajax_Handler;
use AvatorElement\Modules\Forms\Classes\Form_Base;
use AvatorElement\Modules\Forms\Classes\Recaptcha_Handler;
use AvatorElement\Modules\Forms\Classes\Recaptcha_V3_Handler;
use AvatorElement\Modules\Forms\Module;
use AvatorElement\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Form extends Form_Base {

	public function get_name() {
		return 'form';
	}

	public function get_title() {
		return __( 'Form', 'avator-element' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_keywords() {
		return [ 'form', 'forms', 'field', 'button', 'mailchimp', 'drip', 'mailpoet', 'convertkit', 'getresponse', 'recaptcha', 'zapier', 'webhook', 'activecampaign', 'slack', 'discord', 'mailerlite' ];
	}

	protected function _register_controls() {
		$repeater = new Repeater();

		$field_types = [
			'text' => __( 'Text', 'avator-element' ),
			'email' => __( 'Email', 'avator-element' ),
			'textarea' => __( 'Textarea', 'avator-element' ),
			'url' => __( 'URL', 'avator-element' ),
			'tel' => __( 'Tel', 'avator-element' ),
			'radio' => __( 'Radio', 'avator-element' ),
			'select' => __( 'Select', 'avator-element' ),
			'checkbox' => __( 'Checkbox', 'avator-element' ),
			'acceptance' => __( 'Acceptance', 'avator-element' ),
			'number' => __( 'Number', 'avator-element' ),
			'date' => __( 'Date', 'avator-element' ),
			'time' => __( 'Time', 'avator-element' ),
			'upload' => __( 'File Upload', 'avator-element' ),
			'password' => __( 'Password', 'avator-element' ),
			'html' => __( 'HTML', 'avator-element' ),
			'hidden' => __( 'Hidden', 'avator-element' ),
		];

		/**
		 * Forms field types.
		 *
		 * Filters the list of field types displayed in the form `field_type` control.
		 *
		 * @since 1.0.0
		 *
		 * @param array $field_types Field types.
		 */
		$field_types = apply_filters( 'avator_element/forms/field_types', $field_types );

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab( 'form_fields_content_tab', [
			'label' => __( 'Content', 'avator-element' ),
		] );

		$repeater->add_control(
			'field_type',
			[
				'label' => __( 'Type', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => __( 'Label', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
								'password',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => __( 'Required', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'checkbox',
								'recaptcha',
								'recaptcha_v3',
								'hidden',
								'html',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => __( 'Options', 'avator-element' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'avator-element' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select',
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'allow_multiple',
			[
				'label' => __( 'Multiple Selection', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_size',
			[
				'label' => __( 'Rows', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 2,
				'step' => 1,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'select',
						],
						[
							'name' => 'allow_multiple',
							'value' => 'true',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'inline_list',
			[
				'label' => __( 'Inline List', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'elementor-subgroup-inline',
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'checkbox',
								'radio',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_html',
			[
				'label' => __( 'HTML', 'avator-element' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'html',
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'avator-element' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'hidden',
								'recaptcha',
								'recaptcha_v3',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size', [
				'label' => __( 'Size', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'avator-element' ),
					'compact' => __( 'Compact', 'avator-element' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => __( 'Style', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'avator-element' ),
					'dark' => __( 'Dark', 'avator-element' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_badge', [
				'label' => __( 'Badge', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottomright',
				'options' => [
					'bottomright' => __( 'Bottom Right', 'avator-element' ),
					'bottomleft' => __( 'Bottom Left', 'avator-element' ),
					'inline' => __( 'Inline', 'avator-element' ),
				],
				'description' => __( 'To view the validation badge, switch to preview mode', 'avator-element' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha_v3',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'avator-element' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'avator-element' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			[
				'label' => __( 'Advanced', 'avator-element' ),
				'condition' => [
					'field_type!' => 'html',
				],
			]
		);

		$repeater->add_control(
			'field_value',
			[
				'label' => __( 'Default Value', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
								'date',
								'time',
								'hidden',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'custom_id',
			[
				'label' => __( 'ID', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere in this form. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'avator-element' ),
				'render_type' => 'none',
			]
		);

		$repeater->add_control(
			'shortcode',
			[
				'label' => __( 'Shortcode', 'avator-element' ),
				'type' => Controls_Manager::RAW_HTML,
				'classes' => 'forms-field-shortcode',
				'raw' => '<input class="elementor-form-field-shortcode" readonly />',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'avator-element' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => __( 'Form Name', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New Form', 'avator-element' ),
				'placeholder' => __( 'Form Name', 'avator-element' ),
			]
		);

		$this->add_control(
			'form_fields',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'custom_id' => 'name',
						'field_type' => 'text',
						'field_label' => __( 'Name', 'avator-element' ),
						'placeholder' => __( 'Name', 'avator-element' ),
						'width' => '100',
					],
					[
						'custom_id' => 'email',
						'field_type' => 'email',
						'required' => 'true',
						'field_label' => __( 'Email', 'avator-element' ),
						'placeholder' => __( 'Email', 'avator-element' ),
						'width' => '100',
					],
					[
						'custom_id' => 'message',
						'field_type' => 'textarea',
						'field_label' => __( 'Message', 'avator-element' ),
						'placeholder' => __( 'Message', 'avator-element' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'avator-element' ),
					'sm' => __( 'Small', 'avator-element' ),
					'md' => __( 'Medium', 'avator-element' ),
					'lg' => __( 'Large', 'avator-element' ),
					'xl' => __( 'Extra Large', 'avator-element' ),
				],
				'default' => 'sm',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Label', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'return_value' => 'true',
				'default' => 'true',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => __( 'Required Mark', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'avator-element' ),
				'label_off' => __( 'Hide', 'avator-element' ),
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => __( 'Label Position', 'avator-element' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => __( 'Above', 'avator-element' ),
					'inline' => __( 'Inline', 'avator-element' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button',
			[
				'label' => __( 'Submit Button', 'avator-element' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Send', 'avator-element' ),
				'placeholder' => __( 'Send', 'avator-element' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Column Width', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'avator-element' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'default' => '100',
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'avator-element' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'avator-element' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'avator-element' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'avator-element' ),
						'icon' => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'avator-element' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'stretch',
				'prefix_class' => 'elementor%s-button-align-',
			]
		);

		$this->add_control(
			'selected_button_icon',
			[
				'label' => __( 'Icon', 'avator-element' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
				'label_block' => true,
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Icon Position', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'avator-element' ),
					'right' => __( 'After', 'avator-element' ),
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'selected_button_icon[value]!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_css_id',
			[
				'label' => __( 'Button ID', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'avator-element' ),
				'label_block' => false,
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'avator-element' ),
				'separator' => 'before',

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_integration',
			[
				'label' => __( 'Actions After Submit', 'avator-element' ),
			]
		);

		$actions = Module::instance()->get_form_actions();

		$actions_options = [];

		foreach ( $actions as $action ) {
			$actions_options[ $action->get_name() ] = $action->get_label();
		}

		$this->add_control(
			'submit_actions',
			[
				'label' => __( 'Add Action', 'avator-element' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $actions_options,
				'render_type' => 'none',
				'label_block' => true,
				'default' => [
					'email',
				],
				'description' => __( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add its setting below.', 'avator-element' ),
			]
		);

		$this->end_controls_section();

		foreach ( $actions as $action ) {
			$action->register_settings_section( $this );
		}

		$this->start_controls_section(
			'section_form_options',
			[
				'label' => __( 'Additional Options', 'avator-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'Form ID', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'new_form_id',
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'avator-element' ),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'custom_messages',
			[
				'label' => __( 'Custom Messages', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'separator' => 'before',
				'render_type' => 'none',
			]
		);

		$default_messages = Ajax_Handler::get_default_messages();

		$this->add_control(
			'success_message',
			[
				'label' => __( 'Success Message', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::SUCCESS ],
				'placeholder' => $default_messages[ Ajax_Handler::SUCCESS ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => __( 'Error Message', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::ERROR ],
				'placeholder' => $default_messages[ Ajax_Handler::ERROR ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'required_field_message',
			[
				'label' => __( 'Required Message', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
				'placeholder' => $default_messages[ Ajax_Handler::FIELD_REQUIRED ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label' => __( 'Invalid Message', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'placeholder' => $default_messages[ Ajax_Handler::INVALID_FORM ],
				'label_block' => true,
				'condition' => [
					'custom_messages!' => '',
				],
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Form', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomleft, {{WRAPPER}} .elementor-field-group.recaptcha_v3-bottomright' => 'margin-bottom: 0;',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
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
			'label_spacing',
			[
				'label' => __( 'Spacing', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .elementor-labels-above .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group > label, {{WRAPPER}} .elementor-field-subgroup label' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => __( 'Mark Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group > label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'heading_html',
			[
				'label' => __( 'HTML Field', 'avator-element' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'html_spacing',
			[
				'label' => __( 'Spacing', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-type-html' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'html_color',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-type-html' => 'color: {{VALUE}};',
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
				'name' => 'html_typography',
				'selector' => '{{WRAPPER}} .elementor-field-type-html',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Field', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
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
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'avator-element' ),
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-button svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'avator-element' ),
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'avator-element' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_messages_style',
			[
				'label' => __( 'Messages', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'message_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-message',
			]
		);

		$this->add_control(
			'success_message_color',
			[
				'label' => __( 'Success Message Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-success' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'error_message_color',
			[
				'label' => __( 'Error Message Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-message-danger' => 'color: {{COLOR}};',
				],
			]
		);

		$this->add_control(
			'inline_message_color',
			[
				'label' => __( 'Inline Message Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-message.elementor-help-inline' => 'color: {{COLOR}};',
				],
			]
		);

		$this->end_controls_section();

	}

	private function render_icon_with_fallback( $settings ) {
		$migrated = isset( $settings['__fa4_migrated']['selected_button_icon'] );
		$is_new = empty( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( $is_new || $migrated ) {
			Icons_Manager::render_icon( $settings['selected_button_icon'], [ 'aria-hidden' => 'true' ] );
		} else {
			?><i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i><?php
		}
	}

	protected function render() {
		$instance = $this->get_settings_for_display();

		if ( ! Plugin::elementor()->editor->is_edit_mode() ) {
			/**
			 * Elementor form Pre render.
			 *
			 * Fires before the from is rendered in the frontend
			 *
			 * @since 2.4.0
			 *
			 * @param array $instance current form settings
			 * @param Form $this current form widget instance
			 */
			do_action( 'avator-element/forms/pre_render', $instance, $this );
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
						'elementor-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
					],
				],
				'button' => [
					'class' => 'elementor-button',
				],
				'icon-align' => [
					'class' => [
						empty( $instance['button_icon_align'] ) ? '' :
							'elementor-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
			]
		);

		if ( empty( $instance['button_width'] ) ) {
			$instance['button_width'] = '100';
		}

		$this->add_render_attribute( 'submit-group', 'class', 'elementor-col-' . $instance['button_width'] );

		if ( ! empty( $instance['button_width_tablet'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-md-' . $instance['button_width_tablet'] );
		}

		if ( ! empty( $instance['button_width_mobile'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-sm-' . $instance['button_width_mobile'] );
		}

		if ( ! empty( $instance['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $instance['button_size'] );
		}

		if ( ! empty( $instance['button_type'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-button-' . $instance['button_type'] );
		}

		if ( $instance['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
		}

		if ( ! empty( $instance['form_id'] ) ) {
			$this->add_render_attribute( 'form', 'id', $instance['form_id'] );
		}

		if ( ! empty( $instance['form_name'] ) ) {
			$this->add_render_attribute( 'form', 'name', $instance['form_name'] );
		}

		if ( ! empty( $instance['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $instance['button_css_id'] );
		}

		?>
		<form class="elementor-form" method="post" <?php echo $this->get_render_attribute_string( 'form' ); ?>>
			<input type="hidden" name="post_id" value="<?php echo Utils::get_current_post_id(); ?>"/>
			<input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>"/>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php
				foreach ( $instance['form_fields'] as $item_index => $item ) :
					$item['input_size'] = $instance['input_size'];
					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$field_type = $item['field_type'];

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor Forms.
					 *
					 * @since 1.0.0
					 *
					 * @param array $item       The field value.
					 * @param int   $item_index The field index.
					 * @param Form  $this       An instance of the form.
					 */
					$item = apply_filters( 'avator_element/forms/render/item', $item, $item_index, $this );

					/**
					 * Render form field.
					 *
					 * Filters the field rendered by Elementor Forms.
					 *
					 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
					 *
					 * @since 1.0.0
					 *
					 * @param array $item       The field value.
					 * @param int   $item_index The field index.
					 * @param Form  $this       An instance of the form.
					 */
					$item = apply_filters( "avator_element/forms/render/item/{$field_type}", $item, $item_index, $this );

					if ( 'hidden' === $item['field_type'] ) {
						$item['field_label'] = false;
					}
					?>
				<div <?php echo $this->get_render_attribute_string( 'field-group' . $item_index ); ?>>
					<?php
					if ( $item['field_label'] && 'html' !== $item['field_type'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'label' . $item_index ) . '>' . $item['field_label'] . '</label>';
					}

					switch ( $item['field_type'] ) :
						case 'html':
							echo do_shortcode( $item['field_html'] );
							break;
						case 'textarea':
							echo $this->make_textarea_field( $item, $item_index );
							break;

						case 'select':
							echo $this->make_select_field( $item, $item_index );
							break;

						case 'radio':
						case 'checkbox':
							echo $this->make_radio_checkbox_field( $item, $item_index, $item['field_type'] );
							break;
						case 'text':
						case 'email':
						case 'url':
						case 'password':
						case 'hidden':
						case 'search':
							$this->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' );
							echo '<input size="1" ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';
							break;
						default:
							$field_type = $item['field_type'];

							/**
							 * Elementor form field render.
							 *
							 * Fires when a field is rendered.
							 *
							 * The dynamic portion of the hook name, `$field_type`, refers to the field type.
							 *
							 * @since 1.0.0
							 *
							 * @param array $item       The field value.
							 * @param int   $item_index The field index.
							 * @param Form  $this       An instance of the form.
							 */
							do_action( "avator_element/forms/render_field/{$field_type}", $item, $item_index, $this );
					endswitch;
					?>
				</div>
				<?php endforeach; ?>
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
							<?php if ( ! empty( $instance['button_icon'] ) || ! empty( $instance['selected_button_icon'] ) ) : ?>
								<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
									<?php $this->render_icon_with_fallback( $instance ); ?>
									<?php if ( empty( $instance['button_text'] ) ) : ?>
										<span class="elementor-screen-only"><?php _e( 'Submit', 'avator-element' ); ?></span>
									<?php endif; ?>
								</span>
							<?php endif; ?>
							<?php if ( ! empty( $instance['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $instance['button_text']; ?></span>
							<?php endif; ?>
						</span>
					</button>
				</div>
			</div>
		</form>
		<?php
	}

	protected function _content_template() {
		$submit_text = esc_html__( 'Submit', 'avator-element' );
		?>
		<form class="elementor-form" id="{{settings.form_id}}" name="{{settings.form_name}}">
			<div class="elementor-form-fields-wrapper elementor-labels-{{settings.label_position}}">
				<#
					for ( var i in settings.form_fields ) {
						var item = settings.form_fields[ i ];
						item = elementor.hooks.applyFilters( 'avator_element/forms/content_template/item', item, i, settings );

						var options = item.field_options ? item.field_options.split( '\n' ) : [],
							itemClasses = _.escape( item.css_classes ),
							labelVisibility = '',
							placeholder = '',
							required = '',
							inputField = '',
							multiple = '',
							fieldGroupClasses = 'elementor-field-group elementor-column elementor-field-type-' + item.field_type;

						fieldGroupClasses += ' elementor-col-' + ( ( '' !== item.width ) ? item.width : '100' );

						if ( item.width_tablet ) {
							fieldGroupClasses += ' elementor-md-' + item.width_tablet;
						}

						if ( item.width_mobile ) {
							fieldGroupClasses += ' elementor-sm-' + item.width_mobile;
						}

						if ( ! settings.show_labels ) {
							item.field_label = false;
						}

						if ( item.required ) {
							required = 'required';
							fieldGroupClasses += ' elementor-field-required';

							if ( settings.mark_required ) {
								fieldGroupClasses += ' elementor-mark-required';
							}
						}

						if ( item.placeholder ) {
							placeholder = 'placeholder="' + _.escape( item.placeholder ) + '"';
						}

						if ( item.allow_multiple ) {
							multiple = ' multiple';
							fieldGroupClasses += ' elementor-field-type-' + item.field_type + '-multiple';
						}

						switch ( item.field_type ) {
							case 'html':
								item.field_label = false;
								inputField = item.field_html;
								break;

							case 'textarea':
								inputField = '<textarea class="elementor-field elementor-field-textual elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" rows="' + item.rows + '" ' + required + ' ' + placeholder + '>' + item.field_value + '</textarea>';
								break;

							case 'select':
								if ( options ) {
									var size = '';
									if ( item.allow_multiple && item.select_size ) {
										size = ' size="' + item.select_size + '"';
									}
									inputField = '<div class="elementor-field elementor-select-wrapper ' + itemClasses + '">';
									inputField += '<select class="elementor-field-textual elementor-size-' + settings.input_size + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + multiple + size + ' >';
									for ( var x in options ) {
										var option_value = options[ x ];
										var option_label = options[ x ];
										var option_id = 'form_field_option' + i + x;

										if ( options[ x ].indexOf( '|' ) > -1 ) {
											var label_value = options[ x ].split( '|' );
											option_label = label_value[0];
											option_value = label_value[1];
										}

										view.addRenderAttribute( option_id, 'value', option_value );
										if ( option_value ===  item.field_value ) {
											view.addRenderAttribute( option_id, 'selected', 'selected' );
										}
										inputField += '<option ' + view.getRenderAttributeString( option_id ) + '>' + option_label + '</option>';
									}
									inputField += '</select></div>';
								}
								break;

							case 'radio':
							case 'checkbox':
								if ( options ) {
									var multiple = '';

									if ( 'checkbox' === item.field_type && options.length > 1 ) {
										multiple = '[]';
									}

									inputField = '<div class="elementor-field-subgroup ' + itemClasses + ' ' + item.inline_list + '">';

									for ( var x in options ) {
										var option_value = options[ x ];
										var option_label = options[ x ];
										var option_id = 'form_field_' + item.field_type + i + x;
										if ( options[x].indexOf( '|' ) > -1 ) {
											var label_value = options[x].split( '|' );
											option_label = label_value[0];
											option_value = label_value[1];
										}

										view.addRenderAttribute( option_id, {
											value: option_value,
											type: item.field_type,
											id: 'form_field_' + i + '-' + x,
											name: 'form_field_' + i + multiple
										} );

										if ( option_value ===  item.field_value ) {
											view.addRenderAttribute( option_id, 'checked', 'checked' );
										}

										inputField += '<span class="elementor-field-option"><input ' + view.getRenderAttributeString( option_id ) + ' ' + required + '> ';
										inputField += '<label for="form_field_' + i + '-' + x + '">' + option_label + '</label></span>';

									}

									inputField += '</div>';
								}
								break;

							case 'text':
							case 'email':
							case 'url':
							case 'password':
							case 'number':
							case 'search':
								itemClasses = 'elementor-field-textual ' + itemClasses;
								inputField = '<input size="1" type="' + item.field_type + '" value="' + item.field_value + '" class="elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' >';
								break;
							default:
								inputField = elementor.hooks.applyFilters( 'avator_element/forms/content_template/field/' + item.field_type, '', item, i, settings );
						}

						if ( inputField ) {
							#>
							<div class="{{ fieldGroupClasses }}">

								<# if ( item.field_label ) { #>
									<label class="elementor-field-label" for="form_field_{{ i }}" {{{ labelVisibility }}}>{{{ item.field_label }}}</label>
								<# } #>

								{{{ inputField }}}
							</div>
							<#
						}
					}


					var buttonClasses = 'elementor-field-group elementor-column elementor-field-type-submit';

					buttonClasses += ' elementor-col-' + ( ( '' !== settings.button_width ) ? settings.button_width : '100' );

					if ( settings.button_width_tablet ) {
						buttonClasses += ' elementor-md-' + settings.button_width_tablet;
					}

					if ( settings.button_width_mobile ) {
						buttonClasses += ' elementor-sm-' + settings.button_width_mobile;
					}

					var iconHTML = elementor.helpers.renderIcon( view, settings.selected_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
						migrated = elementor.helpers.isIconMigrated( settings, 'selected_button_icon' );

					#>

					<div class="{{ buttonClasses }}">
						<button id="{{ settings.button_css_id }}" type="submit" class="elementor-button elementor-size-{{ settings.button_size }} elementor-button-{{ settings.button_type }} elementor-animation-{{ settings.button_hover_animation }}">
							<span>
								<# if ( settings.button_icon || settings.selected_button_icon ) { #>
									<span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.button_icon || migrated ) ) { #>
											{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
										<# } #>
										<span class="elementor-screen-only"><?php echo $submit_text; ?></span>
									</span>
								<# } #>

								<# if ( settings.button_text ) { #>
									<span class="elementor-button-text">{{{ settings.button_text }}}</span>
								<# } #>
							</span>
						</button>
					</div>
			</div>
		</form>
		<?php
	}
}
