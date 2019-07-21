<?php
namespace Jaxer\Widgets\Posts;

include_once 'posts-base.php';
include_once 'skins/skin-cards.php';
include_once 'skins/skin-classic.php';

// print_r( new Skins\Skin_Classic);

use ElementorPro\Modules\QueryControl\Controls\Group_Control_Posts;
use ElementorPro\Modules\QueryControl\Module as Query_Control;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
class Pss extends Pss_Base
{

    public function get_name()
    {
        return 'pss';
    }

    public function get_title()
    {
        return __('Jaxer Posts', 'jaxer-element');
    }


    public function get_keywords()
    { //??
        return ['posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type'];
    }
    
    public function get_categories()
    {
        return ['basic'];
    }

    public function on_import($element)
    { // import json
        if (!get_post_type_object($element['settings']['posts_post_type'])) {
            $element['settings']['posts_post_type'] = 'post';
        }

        return $element;
    }

    public function on_export($element)
    { // export json to
        $element = Group_Control_Posts::on_export_remove_setting_from_element($element, 'posts');

        return $element;
    }

    protected function _register_skins()
    { // this will add skin option in content tab/ layout section
        $this->add_skin(new Skins\Skinss_Classic($this));
        $this->add_skin(new Skins\Skinss_Cards($this));
        
    }
    protected function _register_controls() {
		parent::_register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}

	public function query_posts() {
		$avoid_duplicates = $this->get_settings( 'avoid_duplicates' );
		$query_args = Query_Control::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_current_skin()->get_instance_value( 'posts_per_page' );
		$query_args['paged'] = $this->get_current_page();

		$query_id = $this->get_settings( 'posts_query_id' );
		if ( ! empty( $query_id ) ) {
			add_action( 'pre_get_posts', [ $this, 'pre_get_posts_filter' ] );
			$this->query = new \WP_Query( $query_args );
			remove_action( 'pre_get_posts', [ $this, 'pre_get_posts_filter' ] );
		} else {
			$this->query = new \WP_Query( $query_args );
		}
		Query_Control::add_to_avoid_list( wp_list_pluck( $this->query->posts, 'ID' ) );
	}

	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'jaxer-element' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name' => 'posts',
			]
		);

		$this->add_control(
			'advanced',
			[
				'label' => __( 'Advanced', 'jaxer-element' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'posts_post_type!' => 'current_query',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'jaxer-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date' => __( 'Date', 'jaxer-element' ),
					'post_title' => __( 'Title', 'jaxer-element' ),
					'menu_order' => __( 'Menu Order', 'jaxer-element' ),
					'rand' => __( 'Random', 'jaxer-element' ),
				],
				'condition' => [
					'posts_post_type!' => 'current_query',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'jaxer-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'jaxer-element' ),
					'desc' => __( 'DESC', 'jaxer-element' ),
				],
				'condition' => [
					'posts_post_type!' => 'current_query',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label' => __( 'Offset', 'jaxer-element' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'condition' => [
					'posts_post_type!' => [
						'by_id',
						'current_query',
					],
				],
				'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'jaxer-element' ),
			]
		);

		Query_Control::add_exclude_controls( $this );

		$this->add_control(
			'posts_query_id',
			[
				'label' => __( 'Query ID', 'jaxer-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => __( 'Give your Query a custom unique id to allow server side filtering', 'jaxer-element' ),
			]
		);

		$this->end_controls_section();
	}

	public function pre_get_posts_filter( $wp_query ) {
		$query_id = $this->get_settings( 'posts_query_id' );

		/**
		 * Elementor Pro posts widget Query args.
		 *
		 * It allows developers to alter individual posts widget queries.
		 *
		 * The dynamic portion of the hook name, `$query_id`, refers to the Query ID.
		 *
		 * @since 2.1.0
		 *
		 * @param \WP_Query $wp_query
		 * @param Posts     $this
		 */
		do_action( "elementor_pro/posts/query/{$query_id}", $wp_query, $this );
    }
    protected function _content_template() {
		?>
		<div class="animated {{ settings.c_animation }}"> <p>this is animations</p> </div>
		<?php
	}
}
