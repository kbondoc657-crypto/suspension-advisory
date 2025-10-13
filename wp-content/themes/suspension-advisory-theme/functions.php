<?php 
/**
 * suspension advisory functions and definations
 */

 if ( ! function_exists( 'suspension_advisory_setup' ) ) {
    /**
     * Sets up theme defailt and registers support for various WordPress features
     * Note that this function hooked into the after setup theme hook, which
     * runs before the init hook. the init hook is to late for some features, such
     * as indicating support for post thumbnails
     */
    function suspension_advisory_setup() {
        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory
         * If you're building a theme based on Crafty Press, use a find and replace
         * to change 'suspension_advisory' to the name of your theme in all the template files.
         */
        load_theme_textdomain( 'suspension_advisory', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automaic-feed-links' );

        /**
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the documen head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /**
         * Enable support for Post Thumbnails on posts and pages
         * 
         * @Link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails
         */
        add_theme_support( 'post-thumbnails' );

        // Set up the WordPress core custom background feature
        add_theme_support( 'custom-background', apply_filters( 'suspension_advisory_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        /**
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTMLS
         */
        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'galley',
            'caption',
        ) );

         // Add theme support for selective refresh for widgets
         add_theme_support( 'customize-selective-refresh-widgets' );

         /**
          * Add support for core custom Logo.
          */
        add_theme_support( 'custom-logo', [
        'height'        => 250,
        'width'         => 250,
        'flex-width'    => true,
        'flex-height'   => true,
        ] );

        /**
         * Add Support for Custom page Header
         */
        add_theme_support( 'custom-header', array(
        'flex-width'    => true,
        'width'         => 1600,
        'flex-height'   => true,
        'height'        => 450,
        'default-image' => '',
        ) );

        /**
         * Add Post Type Support
         */
        add_theme_support( 'post-formats', array( 'aside', 'galley', 'link', 'image', 'quote', 'video', 'audio' ) );
    
        /**
         * This theme uses wp_nav_menu() in one locations.
         */
        register_nav_menus( array(
            'primary' => esc_html__( 'Primary', 'suspension_advisory' ),
            'footer' => esc_html__( 'Footer Menu', 'suspension_advisory' )
        ) );
    }
 }
 add_action( 'after_setup_theme', 'suspension_advisory_setup' );

 /**
  * Set the content width in pixels, based on the theme's design and stylesheet
  *
  * Priority 0 to make it available to lower priority callbacks
  *
  * @global int $content_width
  */
  function suspension_advisory_content_width(){
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@Link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues.1043}
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobal.NonPrefixedVariablefound
    $GLOBALS['content_width'] = apply_filters( 'suspension_advisory_content_width', 1170 );
}
add_action( 'after_setup_theme', 'suspension_advisory_content_width', 0);

/**
 * Register Sidebar widget area.
 * 
 * @since 1.0.0
 */
function suspension_advisory_sidebar_widgets_init() {
    //Default Sidebar
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'suspension_advisory' ),
        'id'            => 'default-sidebar',
        'description'   => esc_html__( 'Add widgets here.', 'suspension_advisory' ),
        'before_widget' => '<section id="%1s$s" class="widget %2$s"></section>',
        'before_title'  => '</section>',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'suspension_advisory_sidebar_widgets_init' );

/**
 * Enqueue public scripts and styles
 */
function suspension_advisory_public_scripts() {

        // Google Fonts.
    wp_enqueue_style(
        'google-fonts-open-sans',
        'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap',
        [],
        null // No version, so WordPress won't add ?ver=
    );

    // Styles.
    wp_enqueue_style( 'default', get_template_directory_uri() . '/assets/css/default.css', [], wp_rand(), 'all' );
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', [], wp_rand(), 'all' );

    // Scripts.
    wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], wp_rand(), true);
}
add_action( 'wp_enqueue_scripts', 'suspension_advisory_public_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function suspension_advisory_admin_scripts() {

}
add_action( 'admin_enqueue_scripts', 'suspension_advisory_admin_scripts' );

// function enqueue_tailwind_styles() {
//     wp_enqueue_style('tailwindcss', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
// }
// add_action('wp_enqueue_scripts', 'enqueue_tailwind_styles');