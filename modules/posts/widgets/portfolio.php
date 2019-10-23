<?php
namespace AvatorElement\Modules\Posts\Widgets;

use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use AvatorElement\Base\Base_Widget;
use AvatorElement\Modules\QueryControl\Module as Module_Query;
use AvatorElement\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Portfolio
 */
class Portfolio extends Base_Widget {

	/**
	 * @var \WP_Query
	 */
	private $_query = null;

	protected $_has_template_content = false;

	public function get_name() {
		return 'portfolio';
	}

	public function get_title() {
		return __( 'Portfolio', 'avator-element' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_keywords() {
		return [ 'posts', 'cpt', 'item', 'loop', 'query', 'portfolio', 'custom post type' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	public function get_query() {
		return $this->_query;
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
	}

	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'avator-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
				'selectors' => [
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'width: calc( 100% / {{SIZE}} )',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'avator-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size',
				'exclude' => [ 'custom' ],
				'default' => 'medium',
				'prefix_class' => 'elementor-portfolio--thumbnail-size-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label' => __( 'Masonry', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'avator-element' ),
				'label_on' => __( 'On', 'avator-element' ),
				'condition' => [
					'columns!' => '1',
				],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'item_ratio',
			[
				'label' => __( 'Item Ratio', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.66,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'padding-bottom: calc( {{SIZE}} * 100% )',
					'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
				],
				'condition' => [
					'masonry' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show Title', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Off', 'avator-element' ),
				'label_on' => __( 'On', 'avator-element' ),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'avator-element' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'avator-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => 'posts',
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', //use the one from Layout section
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'filter_bar',
			[
				'label' => __( 'Filter Bar', 'avator-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_filter_bar',
			[
				'label' => __( 'Show', 'avator-element' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'avator-element' ),
				'label_on' => __( 'On', 'avator-element' ),
			]
		);

		$this->add_control(
			'taxonomy',
			[
				'label' => __( 'Taxonomy', 'avator-element' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => [],
				'options' => $this->get_taxonomies(),
				'condition' => [
					'show_filter_bar' => 'yes',
					'posts_post_type!' => 'by_id',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => __( 'Items', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		/*
		 * The `item_gap` control is replaced by `column_gap` and `row_gap` controls since v 2.1.6
		 * It is left (hidden) in the widget, to provide compatibility with older installs
		 */

		$this->add_control(
			'item_gap',
			[
				'label' => __( 'Item Gap', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}; grid-column-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio' => 'margin: 0 -{{SIZE}}px',
					'(desktop).elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'width: calc( 100% / {{columns.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(tablet).elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'width: calc( 100% / {{columns_tablet.SIZE}} ); border: {{SIZE}}px solid transparent',
					'(mobile).elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'width: calc( 100% / {{columns_mobile.SIZE}} ); border: {{SIZE}}px solid transparent',
				],
				'frontend_available' => true,
				'classes' => 'elementor-hidden',
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio' => 'margin: 0 -{{SIZE}}px',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'border-style: solid; border-color: transparent; border-right-width: calc({{SIZE}}px / 2); border-left-width: calc({{SIZE}}px / 2)',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'avator-element' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'.elementor-msie {{WRAPPER}} .elementor-portfolio-item' => 'border-bottom-width: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'avator-element' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-portfolio-item__img, {{WRAPPER}} .elementor-portfolio-item__overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_overlay',
			[
				'label' => __( 'Item Overlay', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'color_background',
			[
				'label' => __( 'Background Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a .elementor-portfolio-item__overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_title',
			[
				'label' => __( 'Color', 'avator-element' ),
				'separator' => 'before',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a .elementor-portfolio-item__title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-portfolio-item__title',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_filter',
			[
				'label' => __( 'Filter Bar', 'avator-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'color_filter',
			[
				'label' => __( 'Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-portfolio__filter' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'color_filter_active',
			[
				'label' => __( 'Active Color', 'avator-element' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-portfolio__filter.elementor-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_filter',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-portfolio__filter',
			]
		);

		$this->add_control(
			'filter_item_spacing',
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
					'{{WRAPPER}} .elementor-portfolio__filter:not(:last-child)' => 'margin-right: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .elementor-portfolio__filter:not(:first-child)' => 'margin-left: calc({{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_control(
			'filter_spacing',
			[
				'label' => __( 'Spacing', 'avator-element' ),
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
					'{{WRAPPER}} .elementor-portfolio__filters' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function get_posts_tags() {
		$taxonomy = $this->get_settings( 'taxonomy' );

		foreach ( $this->_query->posts as $post ) {
			if ( ! $taxonomy ) {
				$post->tags = [];

				continue;
			}

			$tags = wp_get_post_terms( $post->ID, $taxonomy );

			$tags_slugs = [];

			foreach ( $tags as $tag ) {
				$tags_slugs[ $tag->term_id ] = $tag;
			}

			$post->tags = $tags_slugs;
		}
	}

	public function query_posts() {

		$query_args = [
			'posts_per_page' => $this->get_settings( 'posts_per_page' ),
		];

		/** @var Module_Query $elementor_query */
		$elementor_query = Module_Query::instance();
		$this->_query = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	}

	public function render() {
		$this->query_posts();

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->get_posts_tags();

		$this->render_loop_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_loop_footer();

		wp_reset_postdata();
	}

	protected function render_thumbnail() {
		$settings = $this->get_settings();

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size' );
		?>
		<div class="elementor-portfolio-item__img elementor-post__thumbnail">
			<?php echo $thumbnail_html; ?>
		</div>
		<?php
	}

	protected function render_filter_menu() {
		$taxonomy = $this->get_settings( 'taxonomy' );

		if ( ! $taxonomy ) {
			return;
		}

		$terms = [];

		foreach ( $this->_query->posts as $post ) {
			$terms += $post->tags;
		}

		if ( empty( $terms ) ) {
			return;
		}

		usort( $terms, function( $a, $b ) {
			return strcmp( $a->name, $b->name );
		} );

		?>
		<ul class="elementor-portfolio__filters">
			<li class="elementor-portfolio__filter elementor-active" data-filter="__all"><?php echo __( 'All', 'avator-element' ); ?></li>
			<?php foreach ( $terms as $term ) { ?>
				<li class="elementor-portfolio__filter" data-filter="<?php echo esc_attr( $term->term_id ); ?>"><?php echo $term->name; ?></li>
			<?php } ?>
		</ul>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_settings( 'title_tag' );
		?>
		<<?php echo $tag; ?> class="elementor-portfolio-item__title">
		<?php the_title(); ?>
		</<?php echo $tag; ?>>
		<?php
	}

	protected function render_categories_names() {
		global $post;

		if ( ! $post->tags ) {
			return;
		}

		$separator = '<span class="elementor-portfolio-item__tags__separator"></span>';

		$tags_array = [];

		foreach ( $post->tags as $tag ) {
			$tags_array[] = '<span class="elementor-portfolio-item__tags__tag">' . $tag->name . '</span>';
		}

		?>
		<div class="elementor-portfolio-item__tags">
			<?php echo implode( $separator, $tags_array ); ?>
		</div>
		<?php
	}

	protected function render_post_header() {
		global $post;

		$tags_classes = array_map( function( $tag ) {
			return 'elementor-filter-' . $tag->term_id;
		}, $post->tags );

		$classes = [
			'elementor-portfolio-item',
			'elementor-post',
			implode( ' ', $tags_classes ),
		];

		?>
		<article <?php post_class( $classes ); ?>>
			<a class="elementor-post__thumbnail__link" href="<?php echo get_permalink(); ?>">
		<?php
	}

	protected function render_post_footer() {
		?>
		</a>
		</article>
		<?php
	}

	protected function render_overlay_header() {
		?>
		<div class="elementor-portfolio-item__overlay">
		<?php
	}

	protected function render_overlay_footer() {
		?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		if ( $this->get_settings( 'show_filter_bar' ) ) {
			$this->render_filter_menu();
		}
		?>
		<div class="elementor-portfolio elementor-grid elementor-posts-container">
		<?php
	}

	protected function render_loop_footer() {
		?>
		</div>
		<?php
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_overlay_header();
		$this->render_title();
		// $this->render_categories_names();
		$this->render_overlay_footer();
		$this->render_post_footer();
	}

	public function render_plain_content() {}

}