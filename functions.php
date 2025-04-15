<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
include "inc/inc.vite.php";

$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	Timber\Timber::init();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'feed_links', 2 );
		
		add_filter('show_admin_bar', '__return_false');

		add_filter( 'script_loader_tag', array( $this, 'add_id_to_script'), 10, 3  );
		add_filter( 'mce_buttons', array( $this, 'jivedig_remove_tiny_mce_buttons_from_editor'));
		add_filter( 'mce_buttons_2', array( $this, 'jivedig_remove_tiny_mce_buttons_from_kitchen_sink'));
		add_filter( 'acf/fields/wysiwyg/toolbars' , array( $this, 'my_toolbars'  ));

		add_action( 'wp_enqueue_scripts', array( $this, 'wpse_enqueue_page_template_styles' ));

		add_filter( 'the_content', array( $this, 'surround_img_tags_with_div' ), 99 );
		add_filter( 'acf_the_content', array( $this, 'surround_img_tags_with_div' ), 99 );
		add_filter('wpcf7_autop_or_not', array( $this, '__return_false'), 10,2 );


		/* AJAX CALLS */
		
		add_action( 'init', array( $this, 'prefix_add_endpoints' ));
		add_action( 'template_redirect', array( $this,'prefix_do_latest' ));

		add_action( 'template_redirect', array( $this,'prefix_do_load_products_by_category' ));

		/* AJAX CALLS */

		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page();
		}

		parent::__construct();
	}

	function admin_style() {
		
	}

	function wpse_enqueue_page_template_styles() {

		if ( ! is_admin() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'global-styles' );
			wp_deregister_script( 'wp-embed' );
		}

	}

	function prefix_add_endpoints() {
		add_rewrite_tag( '%api_custom_type%', '([0-9A-Za-z-]+)' );
		add_rewrite_rule( 'api/load_latest/([0-9A-Za-z-]+)/?', 'index.php?api_custom_type=$matches[1]', 'top' );
		
		add_rewrite_tag( '%api_product_category_id%', '([0-9_]+)' );
		add_rewrite_rule( 'api/load_products_by_category/([0-9_]+)/?', 'index.php?api_product_category_id=$matches[1]', 'top' );		

		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	}

	function prefix_do_latest() {
		global $wp_query;

		$api_custom_type = sanitize_text_field($wp_query->get( 'api_custom_type' ));

		if ((! empty( $api_custom_type ) || ($api_custom_type == 'exhibition-and-fairs'))) {
			$msg = '';

			$today = date('Ymd');

			$args = array(
				'numberposts'	 => 1,
				'post_type'		 => $api_custom_type,
				'meta_key'		 => 'start_date',
				'orderby'		 => 'meta_value',
				'order'			 => 'ASC',
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' 	=> 'start_date',
						'value'   => $today,
						'compare' => '>='
					)
				),
			);

			$custom_query = new WP_Query($args);

			if ( $custom_query->have_posts() ){
				while ( $custom_query->have_posts() ) :
					$custom_query->the_post();
					$cur_id = get_the_ID();
					$article = Timber::get_post($cur_id);
				
					$msg .= Timber::compile( 'partial/exhibition-and-fairs-preview.twig', array( 'item' => $article ) );
					
				endwhile;
			}

			if($msg == ''){
				$msg = 'hide-latest';
			}

			echo $msg;

			exit;
		}
	}


	function prefix_do_load_products_by_category (){
		global $wp_query;

		$api_product_category_id = $wp_query->get( 'api_product_category_id' );

		if ( (! empty( $api_product_category_id ) || ($api_product_category_id == '0'))) {
			$msg = '';

			$args = array(
				'post_type'		 => 'product',
				'numberposts' => -1,
				'post_status' => 'publish',
				'orderby'		 => 'date',
				'order'			 => 'DESC',
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => array($api_product_category_id), 
						'operator' => 'IN',
					)
				),
			);

			$custom_query = new WP_Query($args);

			if ( $custom_query->have_posts() ){
		
				while ( $custom_query->have_posts() ) :
					$custom_query->the_post();
					$cur_id = get_the_ID();
					$exclude_id[] = $cur_id;
					$custom_post = Timber::get_post($cur_id);
					 
					$msg .= Timber::compile( 'partial/product.twig', array( 'item' => $custom_post, 'featured' => true ) );
					
				endwhile;

			}
			else{
				$msg = 'hide-latest';
			}

			echo $msg;
			exit;
		}
	}



	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['options'] = get_fields('option');
		$context['menu']  = Timber::get_menu('Main Menu');
		$context['secondary_menu']  = Timber::get_menu('Secondary Menu');
		$context['site']  = $this;

		$current_language = get_language_shortcode();
		$context['current_language'] = $current_language;


		$context['model_archive'] = Timber::get_post(get_field('models_archive','options'));

		$args = [
			'post_type' => 'model',
			'posts_per_page' => -1, // -1 per prenderli tutti
			'orderby' => 'date',
			'order' => 'DESC'
		];
		
		$context['models'] = Timber::get_posts($args);

		return $context;
	}

	

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
		add_theme_support('woocommerce');
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

	function add_id_to_script($tag, $handle, $src) {

		if ( ! is_admin() ) {
			if($handle == 'main'){
				$tag = '<script type="module" src="' . esc_url( $src ) . '" id="'.$handle.'" ></script>';
			}
		}
 
    	return $tag;
	}

	function surround_img_tags_with_div( $content ){
		$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
		$pattern = '/(<img([^>]*)>)/i';

		$replacement = '<div class="image-container">$1</div>';

		$content = preg_replace( $pattern, $replacement, $content );

		$checkParagraph = '<p></p>';
		$substitutionParagraph = '';
		$content = str_replace( $checkParagraph,$substitutionParagraph,$content);

		 // return the processed content
		 return $content;
	}

	function jivedig_remove_tiny_mce_buttons_from_editor( $buttons ) {

		$remove_buttons = array(
			'strikethrough',
			'blockquote',
			'hr', // horizontal line
			'alignleft',
			'aligncenter',
			'alignright',
			'wp_more', // read more link
			'spellchecker',
			'dfw', // distraction free writing mode
			'wp_adv', // kitchen sink toggle (if removed, kitchen sink will always display)
		);
		foreach ( $buttons as $button_key => $button_value ) {
			if ( in_array( $button_value, $remove_buttons ) ) {
				unset( $buttons[ $button_key ] );
			}
		}
		return $buttons;
	}

	function jivedig_remove_tiny_mce_buttons_from_kitchen_sink( $buttons ) {

		$remove_buttons = array(
			'formatselect', // format dropdown menu for <p>, headings, etc
			'underline',
			'alignjustify',
			'forecolor', // text color
			'pastetext', // paste as text
			'removeformat', // clear formatting
			'charmap', // special characters
			'outdent',
			'indent',
			'undo',
			'redo',
			'wp_help', // keyboard shortcuts
		);
		foreach ( $buttons as $button_key => $button_value ) {
			if ( in_array( $button_value, $remove_buttons ) ) {
				unset( $buttons[ $button_key ] );
			}
		}
		return $buttons;
	}

	function my_toolbars( $toolbars ){
		$toolbars['Very Simple' ] = array();
		$toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline','bullist',
		'numlist','link' );

		if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
		{
			unset( $toolbars['Full' ][2][$key] );
		}

		unset( $toolbars['Basic' ] );

		return $toolbars;
	}	
}




new StarterSite();

include "inc/admin.php";
include "inc/wpml.php";
include "inc/woocommerce.php";
