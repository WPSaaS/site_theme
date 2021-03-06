<?php
/**
 * WP SaaS functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link       https://codex.wordpress.org/Theme_Development
 * @link       https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package    WordPress
 * @subpackage WPSaaS
 * @since      WP SaaS 1.0
 */

/**
 * WP SaaS only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'wpsaas_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * Create your own wpsaas_setup() function to override in a child theme.
	 *
	 * @since WP SaaS 1.0
	 */
	function wpsaas_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on WP SaaS, use a find and replace
		 * to change 'wpsaas' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'wpsaas', get_template_directory() . '/languages' );

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
		 * Enable support for custom logo.
		 *
		 *  @since WP SaaS 1.2
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 240,
			'width'       => 240,
			'flex-height' => true,
		) );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'wpsaas' ),
			'social'  => __( 'Social Links Menu', 'wpsaas' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
			'chat',
		) );

		/*
		 * This theme styles the visual editor to resemble the theme style,
		 * specifically font, colors, icons, and column width.
		 */
		add_editor_style( array( 'src/css/editor-style.css', wpsaas_fonts_url() ) );

		// Indicate widget sidebars can use selective refresh in the Customizer.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif; // wpsaas_setup
add_action( 'after_setup_theme', 'wpsaas_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since WP SaaS 1.0
 */
function wpsaas_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpsaas_content_width', 840 );
}

add_action( 'after_setup_theme', 'wpsaas_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link  https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since WP SaaS 1.0
 */
function wpsaas_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'wpsaas' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'wpsaas' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'wpsaas' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'wpsaas' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'wpsaas' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'wpsaas' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'wpsaas_widgets_init' );

if ( ! function_exists( 'wpsaas_fonts_url' ) ) :
	/**
	 * Register Google fonts for WP SaaS.
	 *
	 * Create your own wpsaas_fonts_url() function to override in a child theme.
	 *
	 * @since WP SaaS 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function wpsaas_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'wpsaas' ) ) {
			$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
		}

		/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'wpsaas' ) ) {
			$fonts[] = 'Montserrat:400,700';
		}

		/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
		if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'wpsaas' ) ) {
			$fonts[] = 'Inconsolata:400';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), 'https://fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since WP SaaS 1.0
 */
function wpsaas_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action( 'wp_head', 'wpsaas_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since WP SaaS 1.0
 */
function wpsaas_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'wpsaas-fonts', wpsaas_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/src/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'wpsaas-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'wpsaas-ie', get_template_directory_uri() . '/src/css/ie.css', array( 'wpsaas-style' ), '20160412' );
	wp_style_add_data( 'wpsaas-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'wpsaas-ie8', get_template_directory_uri() . '/src/css/ie8.css', array( 'wpsaas-style' ), '20160412' );
	wp_style_add_data( 'wpsaas-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'wpsaas-ie7', get_template_directory_uri() . '/src/css/ie7.css', array( 'wpsaas-style' ), '20160412' );
	wp_style_add_data( 'wpsaas-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'wpsaas-html5', get_template_directory_uri() . '/src/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'wpsaas-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'wpsaas-skip-link-focus-fix', get_template_directory_uri() . '/src/js/skip-link-focus-fix.js', array(), '20160412', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'wpsaas-keyboard-image-navigation', get_template_directory_uri() . '/src/js/keyboard-image-navigation.js', array( 'jquery' ), '20160412' );
	}
	wp_enqueue_script( 'eproject-swipebox', get_stylesheet_directory_uri() . '/src/js/jquery.swipebox.min.js', array( 'jquery' ) );


	wp_enqueue_script( 'wpsaas-script', get_template_directory_uri() . '/src/js/functions.js', array( 'jquery' ), '20160412', true );

	wp_localize_script( 'wpsaas-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'wpsaas' ),
		'collapse' => __( 'collapse child menu', 'wpsaas' ),
	) );
}

add_action( 'wp_enqueue_scripts', 'wpsaas_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since WP SaaS 1.0
 *
 * @param array $classes Classes for the body element.
 *
 * @return array (Maybe) filtered body classes.
 */
function wpsaas_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}

add_filter( 'body_class', 'wpsaas_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since WP SaaS 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 *
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function wpsaas_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ) . substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ) . substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ) . substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Hooks
 */
require get_template_directory() . '/inc/hooks/footer.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since WP SaaS 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 *
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function wpsaas_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}

add_filter( 'wp_calculate_image_sizes', 'wpsaas_content_image_sizes_attr', 10, 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since WP SaaS 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 *
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function wpsaas_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}

	return $attr;
}

add_filter( 'wp_get_attachment_image_attributes', 'wpsaas_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since WP SaaS 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 *
 * @return array A new modified arguments.
 */
function wpsaas_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';

	return $args;
}

add_filter( 'widget_tag_cloud_args', 'wpsaas_widget_tag_cloud_args' );


function wpsaas_gallery_default_type_set_link( $settings ) {
	$settings['galleryDefaults']['link'] = 'file';

	return $settings;
}

add_filter( 'media_view_settings', 'wpsaas_gallery_default_type_set_link' );

/**
 * @param $content
 *
 * @return mixed
 */
function wpsaas_lightbox_replace( $content ) {

	//a[rel^='prettyPhoto']
	$pattern     = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 class="colorbox prettyPhoto thumbnail" rel="prettyPhoto[gallery]" $6>$7</a>';
	$content     = preg_replace( $pattern, $replacement, $content );

	return $content;
}

add_filter( 'the_content', 'wpsaas_lightbox_replace', 12 );

function wpsaas_add_title_attachment_link( $link, $id = null ) {
	$id         = intval( $id );
	$_post      = get_post( $id );
	$post_title = esc_attr( $_post->post_title );

	return str_replace( '<a href', '<a title="' . $post_title . '" href', $link );
}

add_filter( 'wp_get_attachment_link', 'wpsaas_add_title_attachment_link', 10, 2 );


if ( is_admin() ) {

	// If we are using WP Emmet add a good error styling for code editor
	function wpsaas_admin_styles() {
		?>
		<style type="text/css">
			.cm-s-default .cm-error {
				color:            #fff !important;
				background-color: #a00 !important;
			}
		</style>
	<?php
	}

	add_action('admin_print_styles', 'wpsaas_admin_styles');
}