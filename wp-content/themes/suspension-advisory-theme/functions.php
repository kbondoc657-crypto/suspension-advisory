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

/**
 * Get all unique provinces from APPROVED registered users only
 */
function get_registered_user_provinces() {
    global $wpdb;
    
    // Get only approved user IDs (where ur_user_status = 1)
    $approved_users = $wpdb->get_col("
        SELECT DISTINCT user_id 
        FROM {$wpdb->usermeta} 
        WHERE meta_key = 'ur_user_status'
        AND meta_value = '1'
    ");
    
    // If no approved users found, return empty array
    if (empty($approved_users)) {
        return array();
    }
    
    // Convert array to comma-separated string for SQL IN clause
    $approved_user_ids = implode(',', array_map('intval', $approved_users));
    
    // Find ALL meta keys that might contain province data
    $possible_keys = $wpdb->get_col("
        SELECT DISTINCT meta_key 
        FROM {$wpdb->usermeta} 
        WHERE user_id IN ({$approved_user_ids})
        AND (meta_key LIKE '%state%' 
        OR meta_key LIKE '%province%'
        OR meta_key LIKE '%billing_state%')
        AND meta_value != ''
    ");
    
    $provinces = array();
    
    // Get values from all possible keys, but only from approved users
    foreach ($possible_keys as $meta_key) {
        $results = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT meta_value 
            FROM {$wpdb->usermeta} 
            WHERE meta_key = %s 
            AND user_id IN ({$approved_user_ids})
            AND meta_value != '' 
            ORDER BY meta_value ASC",
            $meta_key
        ));
        
        if (!empty($results)) {
            $provinces = array_merge($provinces, $results);
        }
    }
    
    // Remove duplicates and sort
    $provinces = array_unique($provinces);
    sort($provinces);
    
    return $provinces;
}

/**
 * Display province dropdown - just the <li> items
 */
function display_province_dropdown() {
    $provinces = get_registered_user_provinces();
    
    if (empty($provinces)) {
        echo '<li class="province">No provinces found</li>';
        return;
    }
    
    // Display all provinces from approved registered users
    foreach ($provinces as $province) {
        echo '<li class="province" data-value="' . esc_attr($province) . '">';
        echo esc_html($province);
        echo '</li>';
    }
}

// ==================== CITY FUNCTIONS ====================

/**
 * Get all unique cities from APPROVED registered users only
 */
function get_registered_user_cities() {
    global $wpdb;
    
    // Get only approved user IDs (where ur_user_status = 1)
    $approved_users = $wpdb->get_col("
        SELECT DISTINCT user_id 
        FROM {$wpdb->usermeta} 
        WHERE meta_key = 'ur_user_status'
        AND meta_value = '1'
    ");
    
    // If no approved users found, return empty array
    if (empty($approved_users)) {
        return array();
    }
    
    // Convert array to comma-separated string for SQL IN clause
    $approved_user_ids = implode(',', array_map('intval', $approved_users));
    
    // Find ALL meta keys that might contain city data
    $possible_keys = $wpdb->get_col("
        SELECT DISTINCT meta_key 
        FROM {$wpdb->usermeta} 
        WHERE user_id IN ({$approved_user_ids})
        AND (meta_key LIKE '%city%' 
        OR meta_key LIKE '%town%'
        OR meta_key LIKE '%billing_city%')
        AND meta_value != ''
    ");
    
    $cities = array();
    
    // Get values from all possible keys, but only from approved users
    foreach ($possible_keys as $meta_key) {
        $results = $wpdb->get_col($wpdb->prepare(
            "SELECT DISTINCT meta_value 
            FROM {$wpdb->usermeta} 
            WHERE meta_key = %s 
            AND user_id IN ({$approved_user_ids})
            AND meta_value != '' 
            ORDER BY meta_value ASC",
            $meta_key
        ));
        
        if (!empty($results)) {
            $cities = array_merge($cities, $results);
        }
    }
    
    // Remove duplicates and sort
    $cities = array_unique($cities);
    sort($cities);
    
    return $cities;
}

/**
 * Display city dropdown - just the <li> items
 */
function display_city_dropdown() {
    $cities = get_registered_user_cities();
    
    if (empty($cities)) {
        echo '<li class="city">No cities found</li>';
        return;
    }
    
    // Display all cities from approved registered users
    foreach ($cities as $city) {
        echo '<li class="city" data-value="' . esc_attr($city) . '">';
        echo esc_html($city);
        echo '</li>';
    }
}

// ==================== AJAX HANDLERS ====================

/**
 * AJAX: Filter content by province
 */
function filter_by_province() {
    check_ajax_referer('province_filter_nonce', 'nonce');
    
    $selected_province = isset($_POST['province']) ? sanitize_text_field($_POST['province']) : '';
    
    $args = array();
    
    if (!empty($selected_province)) {
        $meta_keys = array('billing_state', 'user_registration_billing_state', 'state', 'province', 'provincial');
        $meta_query = array('relation' => 'OR');
        
        foreach ($meta_keys as $key) {
            $meta_query[] = array(
                'key' => $key,
                'value' => $selected_province,
                'compare' => '='
            );
        }
        
        $args['meta_query'] = $meta_query;
    }
    
    $users = get_users($args);
    
    wp_send_json_success(array(
        'province' => $selected_province,
        'user_count' => count($users),
        'users' => $users
    ));
}
add_action('wp_ajax_filter_by_province', 'filter_by_province');
add_action('wp_ajax_nopriv_filter_by_province', 'filter_by_province');

/**
 * AJAX: Filter content by city
 */
function filter_by_city() {
    check_ajax_referer('city_filter_nonce', 'nonce');
    
    $selected_city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    
    $args = array();
    
    if (!empty($selected_city)) {
        $meta_keys = array('billing_city', 'user_registration_billing_city', 'city', 'user_city', 'town_city');
        $meta_query = array('relation' => 'OR');
        
        foreach ($meta_keys as $key) {
            $meta_query[] = array(
                'key' => $key,
                'value' => $selected_city,
                'compare' => '='
            );
        }
        
        $args['meta_query'] = $meta_query;
    }
    
    $users = get_users($args);
    
    wp_send_json_success(array(
        'city' => $selected_city,
        'user_count' => count($users),
        'users' => $users
    ));
}
add_action('wp_ajax_filter_by_city', 'filter_by_city');
add_action('wp_ajax_nopriv_filter_by_city', 'filter_by_city');

/**
 * AJAX: Get cities by province (cascading dropdown)
 */
function get_cities_by_province() {
    check_ajax_referer('city_filter_nonce', 'nonce');
    
    $selected_province = isset($_POST['province']) ? sanitize_text_field($_POST['province']) : '';
    
    if (empty($selected_province)) {
        wp_send_json_error('No province selected');
        return;
    }
    
    $province_meta_keys = array('billing_state', 'user_registration_billing_state', 'state', 'province', 'provincial');
    $city_meta_keys = array('billing_city', 'user_registration_billing_city', 'city', 'user_city', 'town_city');
    
    $meta_query = array('relation' => 'OR');
    foreach ($province_meta_keys as $key) {
        $meta_query[] = array(
            'key' => $key,
            'value' => $selected_province,
            'compare' => '='
        );
    }
    
    $users = get_users(array('meta_query' => $meta_query));
    
    $cities = array();
    foreach ($users as $user) {
        foreach ($city_meta_keys as $city_key) {
            $city = get_user_meta($user->ID, $city_key, true);
            if (!empty($city) && !in_array($city, $cities)) {
                $cities[] = $city;
            }
        }
    }
    
    sort($cities);
    
    wp_send_json_success(array(
        'province' => $selected_province,
        'cities' => $cities
    ));
}
add_action('wp_ajax_get_cities_by_province', 'get_cities_by_province');
add_action('wp_ajax_nopriv_get_cities_by_province', 'get_cities_by_province');

// ==================== SHORTCODES ====================

/**
 * Shortcode: [province_dropdown]
 */
function province_dropdown_shortcode() {
    ob_start();
    ?>
    <ul class="dropdown-list" id="province-filter">
        <li class="province active" data-value="">All Provinces</li>
        <?php display_province_dropdown(); ?>
    </ul>
    <?php
    return ob_get_clean();
}
add_shortcode('province_dropdown', 'province_dropdown_shortcode');

/**
 * Shortcode: [city_dropdown]
 */
function city_dropdown_shortcode() {
    ob_start();
    ?>
    <ul class="dropdown-list" id="city-filter">
        <li class="city active" data-value="">All Cities</li>
        <?php display_city_dropdown(); ?>
    </ul>
    <?php
    return ob_get_clean();
}
add_shortcode('city_dropdown', 'city_dropdown_shortcode');













































/**
 * Add these functions to your functions.php
 */

// AJAX Handler for filtering and pagination
function filter_advisory_posts() {
    // Get filter parameters
    $province = isset($_POST['province']) ? sanitize_text_field($_POST['province']) : '';
    $city = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;
    
    // Base query args
    $args = array(
        'post_type'      => 'posts_advisory',
        'post_status'    => 'publish',
        'order'          => 'DESC',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );
    
    // Build author query based on province and city filters
    if (!empty($province) || !empty($city)) {
        $meta_query = array('relation' => 'AND');
        
        if (!empty($province)) {
            $province_meta_keys = array('billing_state', 'user_registration_billing_state', 'state', 'province', 'provincial');
            $province_query = array('relation' => 'OR');
            
            foreach ($province_meta_keys as $key) {
                $province_query[] = array(
                    'key' => $key,
                    'value' => $province,
                    'compare' => '='
                );
            }
            $meta_query[] = $province_query;
        }
        
        if (!empty($city)) {
            $city_meta_keys = array('billing_city', 'user_registration_billing_city', 'city', 'user_city', 'town_city');
            $city_query = array('relation' => 'OR');
            
            foreach ($city_meta_keys as $key) {
                $city_query[] = array(
                    'key' => $key,
                    'value' => $city,
                    'compare' => '='
                );
            }
            $meta_query[] = $city_query;
        }
        
        // Get users matching the location filters
        $user_args = array('meta_query' => $meta_query);
        $users = get_users($user_args);
        $author_ids = array_map(function($user) { return $user->ID; }, $users);
        
        if (empty($author_ids)) {
            wp_send_json_success(array(
                'html' => '<div class="no-results"><p>No announcements found for the selected location.</p></div>',
                'total_posts' => 0,
                'max_pages' => 0
            ));
            return;
        }
        
        $args['author__in'] = $author_ids;
    }
    
    // Execute query
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="annoucements-wrapper">
                <div class="annoucements-title">
                    <h3><?php the_title(); ?></h3>
                    <div class="annoucements-status">
                        Urgent
                    </div>
                </div>
            
                <div class="calendar-and-location">
                    <div class="calendar">
                        <img src="/wp-content/uploads/2025/10/suspension_advisory_calendar.png" alt="">
                        <?php if( have_rows('posts_advisory') ): ?>
                            <?php while( have_rows('posts_advisory') ): the_row(); 
                                $date = get_sub_field('calendar');
                                if ($date):
                                    echo '<span>' . esc_html($date) . '</span>';
                                endif;
                            endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <div class="location">
                        <img src="/wp-content/uploads/2025/10/suspension_advisory_location.png" alt="">
                        <?php
                            $author_id = get_post_field('post_author', get_the_ID());
                            $province_val = get_user_meta($author_id, 'billing_state', true);
                            $city_val = get_user_meta($author_id, 'billing_city', true);

                            if ($city_val || $province_val) {
                                echo '<span>' . esc_html($city_val);
                                if ($city_val && $province_val) {
                                    echo ', ';
                                }
                                echo esc_html($province_val) . '</span>';
                            } else {
                                echo '<span>Location not available</span>';
                            }
                        ?>
                    </div>
                </div>

                <div class="annoucements-message">
                    <?php if( have_rows('posts_advisory') ): ?>
                        <?php while( have_rows('posts_advisory') ): the_row(); 
                            $text_content = get_sub_field('text_content');
                            if ($text_content): 
                                echo wp_kses_post($text_content);
                            endif;
                        endwhile; ?>
                    <?php endif; ?>
                </div>

                <?php 
                    $posts_advisory = get_field('posts_advisory');
                    if( $posts_advisory && isset($posts_advisory['download_file']) ):
                        $file = $posts_advisory['download_file'];
                        $file_url = $file['url'];
                        $file_name = $file['title'];
                        $file_extension = pathinfo($file_url, PATHINFO_EXTENSION);
                ?>
                    <?php if($file) : ?>
                        <div class="download-pdf">
                            <div class="file-name">
                                <img src="/wp-content/uploads/2025/10/suspension-advisory-icon.png" alt="">
                                <span>
                                    <a href="<?php echo esc_url($file_url); ?>" download>
                                        <?php echo esc_html($file_name . '.' . $file_extension); ?>
                                    </a>
                                </span>
                            </div>
                            <img src="/wp-content/uploads/2025/10/suspension_advisory_downloa.png" alt="">
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php
        endwhile;
    else :
        echo '<div class="no-results"><p>No announcements found.</p></div>';
    endif;
    
    $html = ob_get_clean();
    wp_reset_postdata();
    
    // Return JSON response
    wp_send_json_success(array(
        'html' => $html,
        'total_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'current_page' => $paged
    ));
}
add_action('wp_ajax_filter_advisory_posts', 'filter_advisory_posts');
add_action('wp_ajax_nopriv_filter_advisory_posts', 'filter_advisory_posts');


/**
 * Add this JavaScript to your theme (in footer or enqueue it)
 */
function advisory_filter_scripts() {
    if (!is_page_template('page-homepage.php') && !is_front_page()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPage = 1;
        let selectedProvince = '';
        let selectedCity = '';
        
        const announcementsContainer = document.querySelector('.annoucements-container');
        const announcementsNumber = document.querySelector('.annoucements-number');
        const paginationContainer = document.querySelector('.pagination-container');
        
        // Province filter
        const provinceItems = document.querySelectorAll('.dropdown-list li.province');
        provinceItems.forEach(function(item) {
            item.addEventListener('click', function() {
                selectedProvince = this.getAttribute('data-value');
                selectedCity = ''; // Reset city when province changes
                currentPage = 1;
                loadPosts();
            });
        });
        
        // City filter
        const cityItems = document.querySelectorAll('.dropdown-list li.city');
        cityItems.forEach(function(item) {
            item.addEventListener('click', function() {
                selectedCity = this.getAttribute('data-value');
                currentPage = 1;
                loadPosts();
            });
        });
        
        // Pagination click handler (delegated)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('page-number')) {
                e.preventDefault();
                currentPage = parseInt(e.target.getAttribute('data-page'));
                loadPosts();
                
                // Scroll to top of announcements
                announcementsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
        
        // Load posts function
        function loadPosts() {
            // Show loading state
            const wrapper = document.querySelector('.annoucements-wrapper');
            if (wrapper) {
                announcementsContainer.style.opacity = '0.5';
                announcementsContainer.style.pointerEvents = 'none';
            }
            
            const formData = new FormData();
            formData.append('action', 'filter_advisory_posts');
            formData.append('province', selectedProvince);
            formData.append('city', selectedCity);
            formData.append('paged', currentPage);
            formData.append('posts_per_page', 5);
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update announcements count
                    const countText = data.data.total_posts === 1 ? '1 Announcement Found' : data.data.total_posts + ' Announcements Found';
                    announcementsNumber.textContent = countText;
                    
                    // Get existing container content
                    const existingNumber = announcementsContainer.querySelector('.annoucements-number');
                    
                    // Clear container but keep the counter
                    announcementsContainer.innerHTML = '';
                    announcementsContainer.appendChild(existingNumber);
                    
                    // Add new posts
                    announcementsContainer.insertAdjacentHTML('beforeend', data.data.html);
                    
                    // Update pagination
                    updatePagination(data.data.current_page, data.data.max_pages);
                    
                    // Restore opacity
                    announcementsContainer.style.opacity = '1';
                    announcementsContainer.style.pointerEvents = 'auto';
                }
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                announcementsContainer.style.opacity = '1';
                announcementsContainer.style.pointerEvents = 'auto';
            });
        }
        
        // Update pagination
        function updatePagination(current, maxPages) {
            if (!paginationContainer) return;
            
            if (maxPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let html = '<div class="pagination">';
            
            // Previous button
            if (current > 1) {
                html += '<a href="#" class="page-number prev" data-page="' + (current - 1) + '">← Previous</a>';
            }
            
            // Page numbers
            for (let i = 1; i <= maxPages; i++) {
                if (i === current) {
                    html += '<span class="page-number active">' + i + '</span>';
                } else {
                    html += '<a href="#" class="page-number" data-page="' + i + '">' + i + '</a>';
                }
            }
            
            // Next button
            if (current < maxPages) {
                html += '<a href="#" class="page-number next" data-page="' + (current + 1) + '">Next →</a>';
            }
            
            html += '</div>';
            paginationContainer.innerHTML = html;
        }
    });
    </script>
    
    <style>
    .pagination {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        padding: 20px 0;
    }
    
    .pagination .page-number {
        padding: 8px 15px;
        background: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .pagination .page-number:hover {
        background: #e0e0e0;
        border-color: #999;
    }
    
    .pagination .page-number.active {
        background: #0073aa;
        color: white;
        border-color: #0073aa;
        cursor: default;
    }
    
    .pagination .page-number.prev,
    .pagination .page-number.next {
        font-weight: 600;
    }
    
    .no-results {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    
    .annoucements-container {
        transition: opacity 0.3s ease;
    }
    </style>
    <?php
}
add_action('wp_footer', 'advisory_filter_scripts');

// Validation Checking if the Provincial and City already exist in database
/**
 * Custom validation for User Registration & Membership Plugin
 * Validates unique billing_state and billing_city combination
 * Add this code to your theme's functions.php
 */

// Validate billing_city field - prevent word "city" at the end
add_filter('user_registration_validate_billing_city', 'urm_validate_city_format', 10, 3);

function urm_validate_city_format($single_field, $form_data, $field_key) {
    $billing_city = isset($_POST['billing_city']) ? sanitize_text_field($_POST['billing_city']) : '';
    
    if (!empty($billing_city)) {
        // Check if the city name ends with "city" (case-insensitive)
        if (preg_match('/\bcity\b$/i', trim($billing_city))) {
            $admin_email = get_option('admin_email');
            return sprintf(
                __('Please do not include the word "city" at the end of the city name. Example: Use "Pasay" instead of "Pasay City". If you need assistance, please contact the administrator at %s', 'user-registration'),
                $admin_email
            );
        }
    }
    
    return $single_field;
}

// Main validation - check AFTER province code is converted
// Priority 999 ensures this runs after the province code conversion
add_action('user_registration_after_register_user_action', 'urm_validate_unique_location', 999, 3);

function urm_validate_unique_location($valid_form_data, $form_id, $user_id) {
    // Get the saved meta values (after province code conversion)
    $billing_state = get_user_meta($user_id, 'billing_state', true);
    $billing_city = get_user_meta($user_id, 'billing_city', true);
    
    // Get admin email for error message
    $admin_email = get_option('admin_email');
    
    // Check if combination exists (excluding current user)
    if (!empty($billing_state) && !empty($billing_city)) {
        if (urm_location_exists($billing_state, $billing_city, $user_id)) {
            // Delete the user that was just created
            require_once(ABSPATH . 'wp-admin/includes/user.php');
            wp_delete_user($user_id);
            
            // Show error message with admin email
            wp_send_json_error(array(
                'message' => sprintf(
                    __('A user with this Province and City combination already exists. Please contact the administrator at %s for assistance.', 'user-registration'),
                    $admin_email
                )
            ));
            exit;
        }
    }
}

// Check database for existing combination
function urm_location_exists($state, $city, $exclude_user_id = null) {
    global $wpdb;
    
    $query = "SELECT DISTINCT u.ID 
        FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} um1 ON u.ID = um1.user_id
        INNER JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id
        WHERE um1.meta_key = 'billing_state' 
        AND um1.meta_value = %s
        AND um2.meta_key = 'billing_city' 
        AND um2.meta_value = %s";
    
    $prepared_query = $wpdb->prepare($query, $state, $city);
    
    // Exclude current user
    if ($exclude_user_id) {
        $prepared_query .= $wpdb->prepare(" AND u.ID != %d", $exclude_user_id);
    }
    
    $existing_user = $wpdb->get_var($prepared_query);
    
    return !empty($existing_user);
}

// For profile updates - prevent changing to existing combination
add_action('user_registration_before_save_profile_details', 'urm_validate_profile_update', 10, 2);

function urm_validate_profile_update($user_id, $form_data) {
    // Get values from POST
    $billing_state = isset($_POST['billing_state']) ? sanitize_text_field($_POST['billing_state']) : '';
    $billing_city = isset($_POST['billing_city']) ? sanitize_text_field($_POST['billing_city']) : '';
    
    // Check if city ends with "city"
    if (!empty($billing_city) && preg_match('/\bcity\b$/i', trim($billing_city))) {
        $admin_email = get_option('admin_email');
        wp_die(
            sprintf(
                __('Please do not include the word "city" at the end of the city name. Example: Use "Pasay" instead of "Pasay City". If you need assistance, please contact the administrator at %s', 'user-registration'),
                $admin_email
            ),
            __('Validation Error', 'user-registration'),
            array('back_link' => true, 'response' => 400)
        );
    }
    
    // If state is a code (like "00"), we need to get the actual name
    // Check if it's being updated
    if (!empty($billing_state) && !empty($billing_city)) {
        // Check after potential conversion - we'll check the final saved value
        $current_state = get_user_meta($user_id, 'billing_state', true);
        $current_city = get_user_meta($user_id, 'billing_city', true);
        
        // Only check if values are changing
        if ($billing_state !== $current_state || $billing_city !== $current_city) {
            // For profile updates, the state might still be a code
            // We'll check after save using a different hook
            add_action('user_registration_after_save_profile_details', 'urm_check_profile_after_save', 10, 2);
        }
    }
}

function urm_check_profile_after_save($user_id, $form_data) {
    // Get the final saved values (after any conversions)
    $billing_state = get_user_meta($user_id, 'billing_state', true);
    $billing_city = get_user_meta($user_id, 'billing_city', true);
    $admin_email = get_option('admin_email');
    
    if (!empty($billing_state) && !empty($billing_city)) {
        if (urm_location_exists($billing_state, $billing_city, $user_id)) {
            // Revert to previous values or show error
            wp_die(
                sprintf(
                    __('A user with this Province and City combination already exists. Your changes were not saved. Please contact the administrator at %s for assistance.', 'user-registration'),
                    $admin_email
                ),
                __('Update Error', 'user-registration'),
                array('back_link' => true, 'response' => 400)
            );
        }
    }
}