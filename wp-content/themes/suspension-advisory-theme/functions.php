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

// Hide posts by other users in admin for non-admin roles
function restrict_posts_to_own_author($query) {
    global $pagenow;

    // Only apply to admin post list
    if (
        is_admin() &&
        $pagenow == 'edit.php' &&
        !current_user_can('manage_options') // exclude admins
    ) {
        $query->set('author', get_current_user_id());
    }
}
add_action('pre_get_posts', 'restrict_posts_to_own_author');


function display_author_location_shortcode() {
    if (!is_single()) return '';

    $author_id = get_the_author_meta('ID');
    $province = get_user_meta($author_id, 'billing_state', true);
    $city = get_user_meta($author_id, 'billing_city', true);

    if ($city || $province) {
        return '<span>' . esc_html("$city, $province") . '</span>';
    }

    return '';
}
add_shortcode('author_location', 'display_author_location_shortcode');

/**
 * DEBUG VERSION - Find the correct field key
 * Add this first to see what field keys are being used
 */
function ur_debug_field_keys( $valid_form_data, $form_id, $user_id ) {
    error_log('=== USER REGISTRATION DEBUG ===');
    error_log('User ID: ' . $user_id);
    error_log('Form ID: ' . $form_id);
    error_log('All fields: ' . print_r($valid_form_data, true));
    
    // Also log what's being saved to user meta
    $all_meta = get_user_meta($user_id);
    error_log('User meta after save: ' . print_r($all_meta, true));
}
add_action('user_registration_after_register_user_action', 'ur_debug_field_keys', 10, 3);


/**
 * Save full province name instead of code
 * This version checks multiple possible field keys
 */
function ur_save_province_label_instead_of_code( $value, $field_key, $field_data ) {
    
    // Log what we're processing
    error_log("Processing field: $field_key with value: $value");
    
    // Check all possible field key variations
    $target_fields = array(
        'billing_state',
        'user_registration_billing_state',
        'state',
        'province',
        'provincial',
        'user_province',
        'user_state'
    );
    
    // Check if current field matches any target field
    $is_province_field = false;
    foreach ($target_fields as $target) {
        if (strpos($field_key, $target) !== false) {
            $is_province_field = true;
            break;
        }
    }
    
    if ($is_province_field) {
        
        $provinces = array(
            'ABR' => 'Abra',
            'AGN' => 'Agusan del Norte',
            'AGS' => 'Agusan del Sur',
            'AKL' => 'Aklan',
            'ALB' => 'Albay',
            'ANT' => 'Antique',
            'APA' => 'Apayao',
            'AUR' => 'Aurora',
            'BAS' => 'Basilan',
            'BAN' => 'Bataan',
            'BTN' => 'Batanes',
            'BTG' => 'Batangas',
            'BEN' => 'Benguet',
            'BIL' => 'Biliran',
            'BOH' => 'Bohol',
            'BUK' => 'Bukidnon',
            'BUL' => 'Bulacan',
            'CAG' => 'Cagayan',
            'CAN' => 'Camarines Norte',
            'CAS' => 'Camarines Sur',
            'CAM' => 'Camiguin',
            'CAP' => 'Capiz',
            'CAT' => 'Catanduanes',
            'CAV' => 'Cavite',
            'CEB' => 'Cebu',
            'COM' => 'Compostela Valley',
            'NCO' => 'Cotabato',
            'DAV' => 'Davao del Norte',
            'DAS' => 'Davao del Sur',
            'DAC' => 'Davao Occidental',
            'DAO' => 'Davao Oriental',
            'DIN' => 'Dinagat Islands',
            'EAS' => 'Eastern Samar',
            'GUI' => 'Guimaras',
            'IFU' => 'Ifugao',
            'ILN' => 'Ilocos Norte',
            'ILS' => 'Ilocos Sur',
            'ILI' => 'Iloilo',
            'ISA' => 'Isabela',
            'KAL' => 'Kalinga',
            'LUN' => 'La Union',
            'LAG' => 'Laguna',
            'LAN' => 'Lanao del Norte',
            'LAS' => 'Lanao del Sur',
            'LEY' => 'Leyte',
            'MAG' => 'Maguindanao',
            'MAD' => 'Marinduque',
            'MAS' => 'Masbate',
            'MSC' => 'Misamis Occidental',
            'MSR' => 'Misamis Oriental',
            'MOU' => 'Mountain Province',
            'NEC' => 'Negros Occidental',
            'NER' => 'Negros Oriental',
            'NSA' => 'Northern Samar',
            'NUE' => 'Nueva Ecija',
            'NUV' => 'Nueva Vizcaya',
            'MDC' => 'Occidental Mindoro',
            'MDR' => 'Oriental Mindoro',
            'PLW' => 'Palawan',
            'PAM' => 'Pampanga',
            'PAN' => 'Pangasinan',
            'QUE' => 'Quezon',
            'QUI' => 'Quirino',
            'RIZ' => 'Rizal',
            'ROM' => 'Romblon',
            'WSA' => 'Samar',
            'SAR' => 'Sarangani',
            'SIQ' => 'Siquijor',
            'SOR' => 'Sorsogon',
            'SCO' => 'South Cotabato',
            'SLE' => 'Southern Leyte',
            'SUK' => 'Sultan Kudarat',
            'SLU' => 'Sulu',
            'SUN' => 'Surigao del Norte',
            'SUR' => 'Surigao del Sur',
            'TAR' => 'Tarlac',
            'TAW' => 'Tawi-Tawi',
            'ZMB' => 'Zambales',
            'ZAN' => 'Zamboanga del Norte',
            'ZAS' => 'Zamboanga del Sur',
            'ZSI' => 'Zamboanga Sibugay',
            '00'  => 'Metro Manila',
        );
        
        // Convert code to province name
        if ( isset( $provinces[ $value ] ) ) {
            error_log("Converting $value to {$provinces[$value]}");
            return $provinces[ $value ];
        }
    }
    
    return $value;
}
add_filter( 'user_registration_process_registration_field_value', 'ur_save_province_label_instead_of_code', 10, 3 );


/**
 * FALLBACK METHOD: Force update after user registration
 * This will definitely work if the above doesn't
 */
function ur_force_province_name_update( $valid_form_data, $form_id, $user_id ) {
    
    $provinces = array(
        'ABR' => 'Abra',
        'AGN' => 'Agusan del Norte',
        'AGS' => 'Agusan del Sur',
        'AKL' => 'Aklan',
        'ALB' => 'Albay',
        'ANT' => 'Antique',
        'APA' => 'Apayao',
        'AUR' => 'Aurora',
        'BAS' => 'Basilan',
        'BAN' => 'Bataan',
        'BTN' => 'Batanes',
        'BTG' => 'Batangas',
        'BEN' => 'Benguet',
        'BIL' => 'Biliran',
        'BOH' => 'Bohol',
        'BUK' => 'Bukidnon',
        'BUL' => 'Bulacan',
        'CAG' => 'Cagayan',
        'CAN' => 'Camarines Norte',
        'CAS' => 'Camarines Sur',
        'CAM' => 'Camiguin',
        'CAP' => 'Capiz',
        'CAT' => 'Catanduanes',
        'CAV' => 'Cavite',
        'CEB' => 'Cebu',
        'COM' => 'Compostela Valley',
        'NCO' => 'Cotabato',
        'DAV' => 'Davao del Norte',
        'DAS' => 'Davao del Sur',
        'DAC' => 'Davao Occidental',
        'DAO' => 'Davao Oriental',
        'DIN' => 'Dinagat Islands',
        'EAS' => 'Eastern Samar',
        'GUI' => 'Guimaras',
        'IFU' => 'Ifugao',
        'ILN' => 'Ilocos Norte',
        'ILS' => 'Ilocos Sur',
        'ILI' => 'Iloilo',
        'ISA' => 'Isabela',
        'KAL' => 'Kalinga',
        'LUN' => 'La Union',
        'LAG' => 'Laguna',
        'LAN' => 'Lanao del Norte',
        'LAS' => 'Lanao del Sur',
        'LEY' => 'Leyte',
        'MAG' => 'Maguindanao',
        'MAD' => 'Marinduque',
        'MAS' => 'Masbate',
        'MSC' => 'Misamis Occidental',
        'MSR' => 'Misamis Oriental',
        'MOU' => 'Mountain Province',
        'NEC' => 'Negros Occidental',
        'NER' => 'Negros Oriental',
        'NSA' => 'Northern Samar',
        'NUE' => 'Nueva Ecija',
        'NUV' => 'Nueva Vizcaya',
        'MDC' => 'Occidental Mindoro',
        'MDR' => 'Oriental Mindoro',
        'PLW' => 'Palawan',
        'PAM' => 'Pampanga',
        'PAN' => 'Pangasinan',
        'QUE' => 'Quezon',
        'QUI' => 'Quirino',
        'RIZ' => 'Rizal',
        'ROM' => 'Romblon',
        'WSA' => 'Samar',
        'SAR' => 'Sarangani',
        'SIQ' => 'Siquijor',
        'SOR' => 'Sorsogon',
        'SCO' => 'South Cotabato',
        'SLE' => 'Southern Leyte',
        'SUK' => 'Sultan Kudarat',
        'SLU' => 'Sulu',
        'SUN' => 'Surigao del Norte',
        'SUR' => 'Surigao del Sur',
        'TAR' => 'Tarlac',
        'TAW' => 'Tawi-Tawi',
        'ZMB' => 'Zambales',
        'ZAN' => 'Zamboanga del Norte',
        'ZAS' => 'Zamboanga del Sur',
        'ZSI' => 'Zamboanga Sibugay',
        '00'  => 'Metro Manila',
    );
    
    // Check all user meta for province codes
    $all_meta = get_user_meta($user_id);
    
    foreach ($all_meta as $meta_key => $meta_value) {
        // Get the actual value (user meta returns arrays)
        $value = is_array($meta_value) ? $meta_value[0] : $meta_value;
        
        // If this meta value is a province code, update it
        if (isset($provinces[$value])) {
            error_log("Found province code $value in $meta_key, updating to {$provinces[$value]}");
            update_user_meta($user_id, $meta_key, $provinces[$value]);
        }
    }
}
add_action('user_registration_after_register_user_action', 'ur_force_province_name_update', 20, 3);
// function enqueue_tailwind_styles() {
//     wp_enqueue_style('tailwindcss', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
// }
// add_action('wp_enqueue_scripts', 'enqueue_tailwind_styles');