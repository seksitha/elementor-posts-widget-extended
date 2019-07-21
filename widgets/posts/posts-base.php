<?php
namespace Jaxer\Widgets\Posts;

use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base as Base_Widget;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
abstract class Pss_Base extends Base_Widget {
	/**
	 * @var \WP_Query
	 */
	protected $query = null;

	protected $_has_template_content = false;

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function get_query() {
		return $this->query;
	}

	public function render() {}

	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'jaxer-element' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination', 'jaxer-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'jaxer-element' ),
					'numbers' => __( 'Nav-button', 'jaxer-element' ),
					'load_more' => __( 'Load more', 'jaxer-element' ),
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label' => __( 'Page Limit', 'jaxer-element' ),
				'default' => '',
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label' => __( 'Shorten', 'jaxer-element' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'pagination_type' => [
						'numbers',
					],
				],
			]
		);

		$this->add_control(
			'pagination_prev_label',
			[
				'label' => __( 'Previous Label', 'jaxer-element' ),
				'default' => __( '&laquo; Previous', 'jaxer-element' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_next_label',
			[
				'label' => __( 'Next Label', 'jaxer-element' ),
				'default' => __( 'Next &raquo;', 'jaxer-element' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => __( 'Alignment', 'jaxer-element' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'jaxer-element' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'jaxer-element' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'jaxer-element' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->end_controls_section();






        /**
         * Pagination tab style.
         */
		$this->start_controls_section( // SECTION CONTROL
			'section_pagination_style',
			[
				'label' => __( 'Pagination', 'jaxer-element' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type!' => '',
				],
			]
        );
        $this->add_responsive_control(
			'pagination_nav_padding',
			[
				'label' => __( 'Nav Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        $this->add_responsive_control(
			'pagination_button_padding',
			[
				'label' => __( 'Button Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        $this->add_responsive_control(
			'pagination_boader_radius',
			[
				'label' => __( 'Button Boarder radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination button.load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control( 
			'font_color_nav_button',
			[
				'label' => __( 'Color', 'jaxer-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} nav button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control( // typography Font
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .elementor-pagination button',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->add_control( // seperator line ____________________
			'pagination_color_heading',
			[
				'label' => __( 'Colors', 'jaxer-element' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'pagination_colors' ); // Normal | Hover | Active

		$this->start_controls_tab(
			'pagination_color_normal',
			[
				'label' => __( 'Normal', 'jaxer-element' ),
			]
		);

		$this->add_control(
			'pagination_color', // normal
			[
				'label' => __( 'Color', 'jaxer-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover', //hover
			[
				'label' => __( 'Hover', 'jaxer-element' ),
			]
		);

		$this->add_control(
			'pagination_hover_color', //hover
			[
				'label' => __( 'Color', 'jaxer-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination button.page-numbers:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			[
				'label' => __( 'Active', 'jaxer-element' ),
			]
		);

		$this->add_control(
			'pagination_active_color', // active
			[
				'label' => __( 'Color', 'jaxer-element' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => __( 'Space Between', 'jaxer-element' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
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
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->end_controls_section();
	}

	abstract public function query_posts();

	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post = get_post();
		$query_args = [];
		$url = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id'] = wp_unslash( $_GET['preview_id'] );
				$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;
	}

	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = [];

		$paged = $this->get_current_page();

		$link_template = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'jaxer-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();
	}

	public function render_plain_content() {}
}
