<?php
namespace AvatorElement\Modules\ThemeElements\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Info extends Base {

	public function get_name() {
		return 'post-info';
	}

	public function get_title() {
		return __( 'Post Info', 'avator-element' );
	}

	public function get_icon() {
		return 'eicon-post-info';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'post', 'info', 'date', 'time', 'author', 'taxonomy', 'comments', 'terms', 'avatar' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => __( 'Meta Data', 'avator-element' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'avator-element' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'inline',
				'options' => [
					'traditional' => [
						'title' => __( 'Default', 'avator-element' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'avator-element' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'render_type' => 'template',
				'classes' => 'elementor-control-start-end',
				'label_block' => false,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'type',
			[
				'label' => __( 'Type', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'author' => __( 'Author', 'avator-element' ),
					'date' => __( 'Date', 'avator-element' ),
					'time' => __( 'Time', 'avator-element' ),
					'comments' => __( 'Comments', 'avator-element' ),
					'terms' => __( 'Terms', 'avator-element' ),
					'custom' => __( 'Custom', 'avator-element' ),
				],
			]
		);

		$repeater->add_control(
			'date_format',
			[
				'label' => __( 'Date Format', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => _x( 'March 6, 2018 (F j, Y)', 'Date Format', 'avator-element' ),
					'1' => '2018-03-06 (Y-m-d)',
					'2' => '03/06/2018 (m/d/Y)',
					'3' => '06/03/2018 (d/m/Y)',
					'custom' => __( 'Custom', 'avator-element' ),
				],
				'condition' => [
					'type' => 'date',
				],
			]
		);

		$repeater->add_control(
			'custom_date_format',
			[
				'label' => __( 'Custom Date Format', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'F j, Y',
				'label_block' => false,
				'condition' => [
					'type' => 'date',
					'date_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
					__( 'Use the letters: %s', 'avator-element' ),
					'l D d j S F m M n Y y'
				),
			]
		);

		$repeater->add_control(
			'time_format',
			[
				'label' => __( 'Time Format', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'default',
				'options' => [
					'default' => 'Default',
					'0' => '3:31 pm (g:i a)',
					'1' => '3:31 PM (g:i A)',
					'2' => '15:31 (H:i)',
					'custom' => __( 'Custom', 'avator-element' ),
				],
				'condition' => [
					'type' => 'time',
				],
			]
		);
		$repeater->add_control(
			'custom_time_format',
			[
				'label' => __( 'Custom Time Format', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'g:i a',
				'placeholder' => 'g:i a',
				'label_block' => false,
				'condition' => [
					'type' => 'time',
					'time_format' => 'custom',
				],
				'description' => sprintf(
					/* translators: %s: Allowed time letters (see: http://php.net/manual/en/function.time.php). */
					__( 'Use the letters: %s', 'avator-element' ),
					'g G H i a A'
				),
			]
		);

		$repeater->add_control(
			'taxonomy',
			[
				'label' => __( 'Taxonomy', 'avator-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'type' => 'terms',
				],
			]
		);

		$repeater->add_control(
			'text_prefix',
			[
				'label' => __( 'Before', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'condition' => [
					'type!' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_avatar',
			[
				'label' => __( 'Avatar', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'type' => 'author',
				],
			]
		);

		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label' => __( 'Size', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_avatar' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'comments_custom_strings',
			[
				'label' => __( 'Custom Format', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => false,
				'condition' => [
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_no_comments',
			[
				'label' => __( 'No Comments', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __( 'No Comments', 'avator-element' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_one_comment',
			[
				'label' => __( 'One Comment', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __( 'One Comment', 'avator-element' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'string_comments',
			[
				'label' => __( 'Comments', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'placeholder' => __( '%s Comments', 'avator-element' ),
				'condition' => [
					'comments_custom_strings' => 'yes',
					'type' => 'comments',
				],
			]
		);

		$repeater->add_control(
			'custom_text',
			[
				'label' => __( 'Custom', 'avator-element' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'type!' => 'time',
				],
			]
		);

		$repeater->add_control(
			'custom_url',
			[
				'label' => __( 'Custom URL', 'avator-element' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'show_icon',
			[
				'label' => __( 'Icon', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'avator-element' ),
					'default' => __( 'Default', 'avator-element' ),
					'custom' => __( 'Custom', 'avator-element' ),
				],
				'default' => 'default',
				'condition' => [
					'show_avatar!' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => __( 'Choose Icon', 'avator-element' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'condition' => [
					'show_icon' => 'custom',
					'show_avatar!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'type' => 'author',
						'selected_icon' => [
							'value' => 'far fa-user-circle',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'date',
						'selected_icon' => [
							'value' => 'fas fa-calendar',
							'library' => 'fa-solid',
						],
					],
					[
						'type' => 'time',
						'selected_icon' => [
							'value' => 'far fa-clock',
							'library' => 'fa-regular',
						],
					],
					[
						'type' => 'comments',
						'selected_icon' => [
							'value' => 'far fa-comment-dots',
							'library' => 'fa-regular',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} <span style="text-transform: capitalize;">{{{ type }}}</span>',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => __( 'List', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body.rtl {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
					'body:not(.rtl) {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
			[
				'label' => __( 'Alignment', 'avator-element' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Start', 'avator-element' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'avator-element' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'End', 'avator-element' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);

		$this->add_control(
			'divider',
			[
				'label' => __( 'Divider', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'avator-element' ),
				'label_on' => __( 'On', 'avator-element' ),
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'content: ""',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'avator-element' ),
					'double' => __( 'Double', 'avator-element' ),
					'dotted' => __( 'Dotted', 'avator-element' ),
					'dashed' => __( 'Dashed', 'avator-element' ),
				],
				'default' => 'solid',
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-style: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child):after' => 'border-top-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-inline-items .elementor-icon-list-item:not(:last-child):after' => 'border-left-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Width', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view!' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_height',
			[
				'label' => __( 'Height', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'condition' => [
					'divider' => 'yes',
					'view' => 'inline',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ddd',
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'divider' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:not(:last-child):after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => __( 'Icon', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => __( 'Text', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_indent',
			[
				'label' => __( 'Indent', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-icon-list-text' => 'padding-left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-icon-list-text' => 'padding-right: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .elementor-icon-list-text, {{WRAPPER}} .elementor-icon-list-text a' => 'color: {{VALUE}}',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-list-item',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [
			'show_in_nav_menus' => true,
		], 'objects' );

		$options = [
			'' => __( 'Choose', 'avator-element' ),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_meta_data( $repeater_item ) {
		$item_data = [];

		switch ( $repeater_item['type'] ) {
			case 'author':
				$item_data['text'] = get_the_author_meta( 'display_name' );
				$item_data['icon'] = 'fa fa-user-circle-o'; // Default icon.
				$item_data['selected_icon'] = [
					'value' => 'far fa-user-circle',
					'library' => 'fa-regular',
				]; // Default icons.
				$item_data['itemprop'] = 'author';

				if ( 'yes' === $repeater_item['link'] ) {
					$item_data['url'] = [
						'url' => get_author_posts_url( get_the_author_meta( 'ID' ) ),
					];
				}

				if ( 'yes' === $repeater_item['show_avatar'] ) {
					$item_data['image'] = get_avatar_url( get_the_author_meta( 'ID' ), 96 );
				}

				break;

			case 'date':
				$custom_date_format = empty( $repeater_item['custom_date_format'] ) ? 'F j, Y' : $repeater_item['custom_date_format'];

				$format_options = [
					'default' => 'F j, Y',
					'0' => 'F j, Y',
					'1' => 'Y-m-d',
					'2' => 'm/d/Y',
					'3' => 'd/m/Y',
					'custom' => $custom_date_format,
				];

				$item_data['text'] = get_the_time( $format_options[ $repeater_item['date_format'] ] );
				$item_data['icon'] = 'fa fa-calendar'; // Default icon
				$item_data['selected_icon'] = [
					'value' => 'fas fa-calendar',
					'library' => 'fa-solid',
				]; // Default icons.
				$item_data['itemprop'] = 'datePublished';

				if ( 'yes' === $repeater_item['link'] ) {
					$item_data['url'] = [
						'url' => get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) ),
					];
				}
				break;

			case 'time':
				$custom_time_format = empty( $repeater_item['custom_time_format'] ) ? 'g:i a' : $repeater_item['custom_time_format'];

				$format_options = [
					'default' => 'g:i a',
					'0' => 'g:i a',
					'1' => 'g:i A',
					'2' => 'H:i',
					'custom' => $custom_time_format,
				];
				$item_data['text'] = get_the_time( $format_options[ $repeater_item['time_format'] ] );
				$item_data['icon'] = 'fa fa-clock-o'; // Default icon
				$item_data['selected_icon'] = [
					'value' => 'far fa-clock',
					'library' => 'fa-regular',
				]; // Default icons.
				break;

			case 'comments':
				if ( comments_open() ) {
					$default_strings = [
						'string_no_comments' => __( 'No Comments', 'avator-element' ),
						'string_one_comment' => __( 'One Comment', 'avator-element' ),
						'string_comments' => __( '%s Comments', 'avator-element' ),
					];

					if ( 'yes' === $repeater_item['comments_custom_strings'] ) {
						if ( ! empty( $repeater_item['string_no_comments'] ) ) {
							$default_strings['string_no_comments'] = $repeater_item['string_no_comments'];
						}

						if ( ! empty( $repeater_item['string_one_comment'] ) ) {
							$default_strings['string_one_comment'] = $repeater_item['string_one_comment'];
						}

						if ( ! empty( $repeater_item['string_comments'] ) ) {
							$default_strings['string_comments'] = $repeater_item['string_comments'];
						}
					}

					$num_comments = (int) get_comments_number(); // get_comments_number returns only a numeric value

					if ( 0 === $num_comments ) {
						$item_data['text'] = $default_strings['string_no_comments'];
					} else {
						$item_data['text'] = sprintf( _n( $default_strings['string_one_comment'], $default_strings['string_comments'], $num_comments, 'avator-element' ), $num_comments );
					}

					if ( 'yes' === $repeater_item['link'] ) {
						$item_data['url'] = [
							'url' => get_comments_link(),
						];
					}
					$item_data['icon'] = 'fa fa-commenting-o'; // Default icon
					$item_data['selected_icon'] = [
						'value' => 'far fa-comment-dots',
						'library' => 'fa-regular',
					]; // Default icons.
					$item_data['itemprop'] = 'commentCount';
				}
				break;

			case 'terms':
				$item_data['icon'] = 'fa fa-tags'; // Default icon
				$item_data['selected_icon'] = [
					'value' => 'fas fa-tags',
					'library' => 'fa-solid',
				]; // Default icons.
				$item_data['itemprop'] = 'about';

				$taxonomy = $repeater_item['taxonomy'];
				$terms = wp_get_post_terms( get_the_ID(), $taxonomy );
				foreach ( $terms as $term ) {
					$item_data['terms_list'][ $term->term_id ]['text'] = $term->name;
					if ( 'yes' === $repeater_item['link'] ) {
						$item_data['terms_list'][ $term->term_id ]['url'] = get_term_link( $term );
					}
				}
				break;

			case 'custom':
				$item_data['text'] = $repeater_item['custom_text'];
				$item_data['icon'] = 'fa fa-info-circle'; // Default icon.
				$item_data['selected_icon'] = [
					'value' => 'far fa-tags',
					'library' => 'fa-regular',
				]; // Default icons.

				if ( 'yes' === $repeater_item['link'] && ! empty( $repeater_item['custom_url'] ) ) {
					$item_data['url'] = $repeater_item['custom_url'];
				}

				break;
		}

		$item_data['type'] = $repeater_item['type'];

		if ( ! empty( $repeater_item['text_prefix'] ) ) {
			$item_data['text_prefix'] = esc_html( $repeater_item['text_prefix'] );
		}

		return $item_data;
	}

	protected function render_item( $repeater_item ) {
		$item_data = $this->get_meta_data( $repeater_item );
		$repeater_index = $repeater_item['_id'];

		if ( empty( $item_data['text'] ) && empty( $item_data['terms_list'] ) ) {
			return;
		}

		$has_link = false;
		$link_key = 'link_' . $repeater_index;
		$item_key = 'item_' . $repeater_index;

		$this->add_render_attribute( $item_key, 'class',
			[
				'elementor-icon-list-item',
				'elementor-repeater-item-' . $repeater_item['_id'],
			]
		);

		$active_settings = $this->get_active_settings();

		if ( 'inline' === $active_settings['view'] ) {
			$this->add_render_attribute( $item_key, 'class', 'elementor-inline-item' );
		}

		if ( ! empty( $item_data['url']['url'] ) ) {
			$has_link = true;

			$url = $item_data['url'];
			$this->add_render_attribute( $link_key, 'href', $url['url'] );

			if ( ! empty( $url['is_external'] ) ) {
				$this->add_render_attribute( $link_key, 'target', '_blank' );
			}

			if ( ! empty( $url['nofollow'] ) ) {
				$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $item_data['itemprop'] ) ) {
			$this->add_render_attribute( $item_key, 'itemprop', $item_data['itemprop'] );
		}

		?>
		<li <?php echo $this->get_render_attribute_string( $item_key ); ?>>
			<?php if ( $has_link ) : ?>
			<a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
				<?php endif; ?>
				<?php $this->render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ); ?>
				<?php $this->render_item_text( $item_data, $repeater_index ); ?>
				<?php if ( $has_link ) : ?>
			</a>
		<?php endif; ?>
		</li>
		<?php
	}

	protected function render_item_icon_or_image( $item_data, $repeater_item, $repeater_index ) {
		// Set icon according to user settings.
		$migration_allowed = Icons_Manager::is_migration_allowed();
		if ( ! $migration_allowed ) {
			if ( 'custom' === $repeater_item['show_icon'] && ! empty( $repeater_item['icon'] ) ) {
				$item_data['icon'] = $repeater_item['icon'];
			} elseif ( 'none' === $repeater_item['show_icon'] ) {
				$item_data['icon'] = '';
			}
		} else {
			if ( 'custom' === $repeater_item['show_icon'] && ! empty( $repeater_item['selected_icon'] ) ) {
				$item_data['selected_icon'] = $repeater_item['selected_icon'];
			} elseif ( 'none' === $repeater_item['show_icon'] ) {
				$item_data['selected_icon'] = [];
			}
		}

		if ( empty( $item_data['icon'] ) && empty( $item_data['selected_icon'] ) && empty( $item_data['image'] ) ) {
			return;
		}

		$migrated = isset( $repeater_item['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $repeater_item['icon'] ) && $migration_allowed;
		$show_icon = 'none' !== $repeater_item['show_icon'];

		?>
		<span class="elementor-icon-list-icon">
			<?php
			if ( ! empty( $item_data['image'] ) ) :
				$image_data = 'image_' . $repeater_index;
				$this->add_render_attribute( $image_data, 'src', $item_data['image'] );
				$this->add_render_attribute( $image_data, 'alt', $item_data['text'] );
				?>
				<img class="elementor-avatar" <?php echo $this->get_render_attribute_string( $image_data ); ?>>
			<?php elseif ( $show_icon ) : ?>
				<?php if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $item_data['selected_icon'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $item_data['icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render_item_text( $item_data, $repeater_index ) {
		$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $repeater_index );

		$this->add_render_attribute( $repeater_setting_key, 'class', [ 'elementor-icon-list-text', 'elementor-post-info__item', 'elementor-post-info__item--type-' . $item_data['type'] ] );
		if ( ! empty( $item['terms_list'] ) ) {
			$this->add_render_attribute( $repeater_setting_key, 'class', 'elementor-terms-list' );
		}

		?>
		<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ); ?>>
			<?php if ( ! empty( $item_data['text_prefix'] ) ) : ?>
				<span class="elementor-post-info__item-prefix"><?php echo esc_html( $item_data['text_prefix'] ); ?></span>
			<?php endif; ?>
			<?php
			if ( ! empty( $item_data['terms_list'] ) ) :
				$terms_list = [];
				$item_class = 'elementor-post-info__terms-list-item';
				?>
				<span class="elementor-post-info__terms-list">
				<?php
				foreach ( $item_data['terms_list'] as $term ) :
					if ( ! empty( $term['url'] ) ) :
						$terms_list[] = '<a href="' . esc_attr( $term['url'] ) . '" class="' . $item_class . '">' . esc_html( $term['text'] ) . '</a>';
					else :
						$terms_list[] = '<span class="' . $item_class . '">' . esc_html( $term['text'] ) . '</span>';
					endif;
				endforeach;

				echo implode( ', ', $terms_list );
				?>
				</span>
			<?php else : ?>
				<?php
				echo wp_kses( $item_data['text'], [
					'a' => [
						'href' => [],
						'title' => [],
						'rel' => [],
					],
				] );
				?>
			<?php endif; ?>
		</span>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		ob_start();
		if ( ! empty( $settings['icon_list'] ) ) {
			foreach ( $settings['icon_list'] as $repeater_item ) {
				$this->render_item( $repeater_item );
			}
		}
		$items_html = ob_get_clean();

		if ( empty( $items_html ) ) {
			return;
		}

		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
		}

		$this->add_render_attribute( 'icon_list', 'class', [ 'elementor-icon-list-items', 'elementor-post-info' ] );
		?>
		<ul <?php echo $this->get_render_attribute_string( 'icon_list' ); ?>>
			<?php echo $items_html; ?>
		</ul>
		<?php
	}
}