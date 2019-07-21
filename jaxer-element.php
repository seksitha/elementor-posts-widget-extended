<?php
/**
 * Plugin Name: Jaxer Element
 * Description: Extending elementor post widget the lazy load pagination and load more by using ajax AngularJs.
 * Plugin URI:  https://github.com/seksitha/elementor-posts-widget-extended
 * Version:     1.0.0
 * Author:      Sek Sitha
 * Author URI: https://github.com/seksitha/elementor-posts-widget-extended
 * Author email : seksitha@gmail.com
 * Text Domain: jaxer-element
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Jaxer Element Class
 *
 * @since 1.0.0
 */
final class Jaxer_Element {

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

        // Init Plugin
        function add_angular_app_to_wp_body( $classes ){
            $classes[] = '" ng-app="elementorApp" ng-controller="postCtrl"';
            return $classes;
        }
        add_filter( 'body_class','add_angular_app_to_wp_body', 999 );

        add_action( 'plugins_loaded', array( $this, 'init' ) );
        add_action('wp_ajax_nopriv_ajax_request', 'ajax_handle_request');
        add_action('wp_ajax_ajax_request', 'ajax_handle_request');
        
        wp_enqueue_style( 'jaxer', plugins_url( '/assets/css/jaxer.css', __FILE__ ), [ ], 1 );
        wp_enqueue_style( 'jaxer-fa', plugins_url( '/assets/css/fa-fonts/css/all.min.css', __FILE__ ), [ ], 1 );


        wp_enqueue_script( 'angularjs', plugins_url( '/assets/js/angular.min.js', __FILE__ ), [ ], false, false );
        wp_enqueue_script( 'angular_sanitize', plugins_url( '/assets/js/angular-sanitize.min.js', __FILE__ ), ['angularjs' ], false, false );
        wp_enqueue_script( 'jaxer-script', plugins_url( '/assets/js/plugin.js', __FILE__ ), [ 'angularjs','jquery' ], false, false );
        wp_localize_script( 'jaxer-script', 'wpDdminUrl', [url=>get_admin_url(),'loaderUrl'=>plugins_url( '/widgets/posts/loader.svg', __FILE__ )] );
        
        function ajax_handle_request(){
            $query = new WP_Query(  );
            // TODO: implement taxonomy
            $data = file_get_contents("php://input");
            $postdata = json_decode($data);
            $query->query( [
                'posts_per_page'=> $postdata->posts_per_page, 
                'post_type'=>$postdata->post_type, 
                'paged'=>$postdata->paged,
                'orderby' => $postdata->orderby,
                'order' => $postdata->order,
                'category__in' => $postdata->category,
                'tag__in' => $postdata->tags,
                'author__in' => $postdata->author
                ]
            );
            $posts = $query->posts;
            foreach($posts as $key => $post ){
                // print_r($post->ID);
                $posts[$key]->thumbnail = get_the_post_thumbnail_url($post->ID, $postdata->thumbnail_size);
                $posts[$key]->posturl = get_permalink($post->ID);
                $posts[$key]->author = get_author_name($post->post_author);
                $posts[$key]->avatar = get_avatar( get_the_author_meta( $post->post_author ), 128, '', get_the_author_meta( 'display_name' ) );
                $posts[$key]->badge = get_the_terms( $post->ID, 'category' )[0]->name;
                $posts[$key]->post_excerpt = wp_trim_words($posts[$key]->post_excerpt, $postdata->excerpt_length,'...');
            }
            
            echo (json_encode($posts));

            // IMPORTANT: don't forget to "exit"
            exit;
        }
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'jaxer-element' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jaxer-element' ),
			'<strong>' . esc_html__( 'Jaxer Element', 'jaxer-element' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'jaxer-element' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'jaxer-element' ),
			'<strong>' . esc_html__( 'Jaxer Element', 'jaxer-element' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'jaxer-element' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'jaxer-element' ),
			'<strong>' . esc_html__( 'Jaxer Element', 'jaxer-element' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'jaxer-element' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate
new Jaxer_Element();
