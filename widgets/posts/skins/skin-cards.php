<?php
namespace Jaxer\Widgets\Posts\Skins;
include_once 'skin-base.php';

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Skinss_Cards extends Skinss_Base {


	public function get_id() {
		return 'cards';
	}

	public function get_title() {
		return __( 'Cards', 'elementor-pro' );
	}

	public function start_controls_tab( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tab( $this->get_control_id( $id ), $args );
	}

	public function end_controls_tab() {
		$this->parent->end_controls_tab();
	}

	public function start_controls_tabs( $id ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->parent->start_controls_tabs( $this->get_control_id( $id ) );
	}

	public function end_controls_tabs() {
		$this->parent->end_controls_tabs();
	}

	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/pss/cards_section_design_image/before_section_end', [ $this, 'register_additional_design_image_controls' ] );
    }
    
	public function register_controls( Widget_Base $widget ) {  // from parent
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
		$this->register_badge_controls();
		$this->register_avatar_controls();
	}

	public function register_design_controls() { // from parent
		$this->register_design_layout_controls();
		$this->register_design_card_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}

	protected function register_thumbnail_controls() {
		parent::register_thumbnail_controls();
		$this->remove_responsive_control( 'image_width' );
		$this->update_control(
			'thumbnail',
			[
				'label' => __( 'Show Image', 'elementor-pro' ),
				'options' => [
					'top' => __( 'Yes', 'elementor-pro' ),
					'none' => __( 'No', 'elementor-pro' ),
				],
				'render_type' => 'template',
			]
		);
	}

	protected function register_meta_data_controls() {
		parent::register_meta_data_controls();
		$this->update_control(
			'meta_separator',
			[
				'default' => '•',
			]
		);
	}

	public function register_additional_design_image_controls() {
		$this->update_control(
			'image_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'margin-top: {{SIZE}}{{UNIT}}', // wrapper is contaner outest without it , it will modifies class every where
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->remove_control( 'img_border_radius' );

		$this->add_control(
			'heading_badge_style',
			[
				'label' => __( 'Badge', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_position',
			[
				'label' => 'Badge Position',
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__badge' => '{{VALUE}}: 0',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'background-color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'badge_margin',
			[
				'label' => __( 'Margin', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__badge' => 'margin: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-post__card .elementor-post__badge',
				'exclude' => [ 'font_size', 'line-height' ],
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_avatar_style',
			[
				'label' => __( 'Avatar', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				],
			]
		);

		$this->add_control(
			'avatar_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 90,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__avatar' => 'top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .elementor-post__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2)',
				],
				'condition' => [
					$this->get_control_id( 'show_avatar' ) => 'show-avatar',
				],
			]
		);
	}

	public function register_badge_controls() {
		$this->add_control(
			'show_badge',
			[
				'label' => __( 'Badge', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'badge_taxonomy',
			[
				'label' => __( 'Badge Taxonomy', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'category',
				'options' => $this->get_taxonomies(),
				'condition' => [
					$this->get_control_id( 'show_badge' ) => 'yes',
				],
			]
		);
	}

	public function register_avatar_controls() {
		$this->add_control(
			'show_avatar',
			[
				'label' => __( 'Avatar', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'return_value' => 'show-avatar',
				'default' => 'show-avatar',
				'separator' => 'before',
				'prefix_class' => 'elementor-posts--',
				'render_type' => 'template',
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);
	}

	public function register_design_card_controls() {
		$this->start_controls_section(
			'section_design_card',
			[
				'label' => __( 'Card', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
		$this->add_control(
			'card_bg_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_padding',
			[
				'label' => __( 'Horizontal Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'padding: 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__meta-data' => 'padding: 10px {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-post__avatar' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'card_vertical_padding',
			[
				'label' => __( 'Vertical Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_shadow_box_shadow_type', // The name of this control is like that, for future extensibility to group_control box shadow.
			[
				'label' => __( 'Box Shadow', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-card-shadow-',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'none' => __( 'None', 'elementor-pro' ),
					'gradient' => __( 'Gradient', 'elementor-pro' ),
					//'zoom-in' => __( 'Zoom In', 'elementor-pro' ),
					//'zoom-out' => __( 'Zoom Out', 'elementor-pro' ),
				],
				'default' => 'gradient',
				'separator' => 'before',
				'prefix_class' => 'elementor-posts__hover-',
			]
		);

		$this->add_control(
			'meta_border_color',
			[
				'label' => __( 'Meta Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card .elementor-post__meta-data' => 'border-top-color: {{VALUE}}',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
        );


		$this->add_control(
			'card_bg_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__card' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_content_controls() {
		parent::register_design_content_controls();
		$this->remove_control( 'meta_spacing' );

		$this->update_control(
			'read_more_spacing',
			[
				'selectors' => [
					'{{WRAPPER}} .elementor-post__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	protected function render_post_header() {
        // echo '<pre>';
        // print_r($this->parent->get_settings());
		?>
		<article <?php post_class( [ 'elementor-post elementor-grid-item server-side__'. $this->parent->get_data()['id'].' animated '. $this->get_instance_value('c_animation')]); echo ' style="animation-duration: '.$this->get_instance_value('animation_duration').'ms;"'?>>
			<div class="elementor-post__card">
		<?php
	}

	protected function render_post_footer() {
		?>
			</div>
		</article>
		<?php
	}

	protected function render_avatar() {
        
        ?>
        
		<div class="elementor-post__avatar">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 128, '', get_the_author_meta( 'display_name' ) ); ?>
		</div>
		<?php
	}

	protected function render_badge() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-post__badge"><?php echo $terms[0]->name; ?></div>
		<?php
	}

	protected function render_thumbnail() {
		if ( $this->get_instance_value( 'thumbnail' )  === 'none' ) {
			return;
		}

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		?>
		<a class="elementor-post__thumbnail__link" href="<?php echo get_permalink(); ?>">
        <div class="elementor-post__thumbnail  <?php echo ($this->get_instance_value('item_ratio')['size'] > 0.66) ?  ' elementor-fit-height': ''; ?>"> <?php echo $thumbnail_html; ?> </div>
            </a>
		<?php
		if ( $this->get_instance_value( 'show_badge' ) ) {
			$this->render_badge();
		}

		if ( $this->get_instance_value( 'show_avatar' ) ) {
			$this->render_avatar();
		}
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_meta_data();
		$this->render_post_footer();
    }
    protected function render_post_wrapper() {
		?>
        <article  ng-show="<?php echo '_'. $this->parent->get_data('id').'.length';?>" ng-repeat="post in <?php echo '_'. ($this->parent->get_data('id')); ?>||[] track by $index" <?php post_class( [ 'elementor-post elementor-grid-item animated ' . $this->parent->get_settings()['cards_c_animation'] ] ); echo ' style="animation-duration: '.$this->get_instance_value('animation_duration').'ms;"'?>>
			<div class="elementor-post__card">
		<?php
    }
    protected function render_post_wrapper_end() {
		?>
			</div>
		</article>
		<?php
	}
    protected function render_thumbnail_angular(){
        $thumbnail = $this->get_instance_value('thumbnail');
        if ('none' === $thumbnail && !Plugin::elementor()->editor->is_edit_mode()) {
            return;
        }
        $settings = $this->parent->get_settings();
        $skin = $this->get_id();
        ?>
            <a class="elementor-post__thumbnail__link " href="{{post.posturl}}">
                <div class="elementor-post__thumbnail <?php echo ($this->get_instance_value('item_ratio')['size'] > 0.66) ?  ' elementor-fit-height': ''; ?>">
                    <img
                        width="<?php echo get_option($settings[$skin.'_thumbnail_size_size'] . '_size_w')|750;?>"
                        height="<?php echo get_option($settings[$skin.'_thumbnail_size_size'] . '_size_h')|500;?>"
                        ng-src="{{post.thumbnail}}"
                        class="attachment-<?php echo $settings[$skin.'_thumbnail_size_size']; ?> size-<?php echo $settings[$skin.'_thumbnail_size_size'];?>"
                        alt=""
                        ng-srcset="{{post.thumbnail}} 300w"
                        sizes="100vw"
                    />
                </div>
            </a>
            
        <?php
        if ( $this->get_instance_value( 'show_badge' ) ) {
            $this->render_badge_ang();
        }

        if ( $this->get_instance_value( 'show_avatar' ) ) {
            $this->render_avatar_ang();
        }
    }
    protected function render_title_wrapper()
    {
        ?>
		<div class="elementor-post__text">
		<?php
    }
    protected function render_title_wrapper_end()
    {
        ?>
		</div>
		<?php
    }
    protected function render_avatar_ang() {
        
        ?>
        
		<div class="elementor-post__avatar" >
           <ng-bind-html ng-bind-html="post.avatar">
           
            </ng-bind-html>
		</div>
		<?php
    }
    protected function render_badge_ang() {
		$taxonomy = $this->get_instance_value( 'badge_taxonomy' );
		if ( empty( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
		<div class="elementor-post__badge">{{post.badge}}</div>
		<?php
	}
    protected function render_post_angular (){
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return;
        } 
        $this->render_post_wrapper(); // article
            $this->render_thumbnail_angular(); // 

            $this->render_title_wrapper();
                $this->render_title_angular();
                $this->render_excerpt_ang();
                $this->render_read_more_ang();
                $this->render_title_wrapper_end();
                $this->render_meta_data_ang();

        $this->render_post_wrapper_end();
    }
}
