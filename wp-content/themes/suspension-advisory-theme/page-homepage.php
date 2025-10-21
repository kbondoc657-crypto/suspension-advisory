<?php
/**
 * Template Name: Homepage
 */
 get_header(); ?>
<div class="homepage">

    <div class="container">
        <h1>Find Class Suspension Annoucements</h1>
        <p>Select your location to view relevant class suspension annoucement in your area.</p>

        <div class="filter-dropdown-container">
            
            <!-- Filter Dropdowns -->
       
                <!-- Province Dropdown -->
                <div class="province-wrapper">
                    <div class="dropdown-content">
                        <div class="dropdown-content-posts_dropdown">
                            <div class="dropdown-content-posts_sortby">
                                <div class="dropdown">
                                    <label for="">Province</label>
                                    <div class="select">
                                        <span class="selected">All Provinces</span>
                                        <svg class="arrow_down caret" width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_623_3)">
                                                <path class="path" d="M11.781 0.000164032L6.67498 5.10016L1.56898 0.000164032L-2.47955e-05 1.56816L6.67498 8.24316L13.35 1.56816L11.781 0.000164032Z" fill="#23252B"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <ul class="dropdown-list" id="province-filter">
                                        <li class="province active" data-value="">All Provinces</li>
                                        <?php display_province_dropdown(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- City Dropdown -->
                <div class="city-wrapper">
                    <div class="dropdown-content">
                        <div class="dropdown-content-posts_dropdown">
                            <div class="dropdown-content-posts_sortby">
                                <div class="dropdown">
                                    <label for="">City</label>
                                    <div class="select">
                                        <span class="selected">All Cities</span>
                                        <svg class="arrow_down caret" width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_623_3)">
                                                <path class="path" d="M11.781 0.000164032L6.67498 5.10016L1.56898 0.000164032L-2.47955e-05 1.56816L6.67498 8.24316L13.35 1.56816L11.781 0.000164032Z" fill="#23252B"/>
                                            </g>
                                        </svg>
                                    </div>
                                    <ul class="dropdown-list" id="city-filter">
                                        <li class="city active" data-value="">All Cities</li>
                                        <?php display_city_dropdown(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        




        </div>

        <!-- Announcements Container -->
        <div class="annoucements-container">
            <?php
                $args = array(
                    'post_type'      => 'posts_advisory',
                    'post_status'    => 'publish',
                    'order'          => 'DESC',
                    'posts_per_page' => 5,
                );

                $development = new WP_Query( $args );
                $total_posts = $development->found_posts;
                $count_text = $total_posts === 1 ? '1 Announcement Found' : $total_posts . ' Announcements Found';
            ?>   
    
            <span class="annoucements-number"><?php echo $count_text; ?></span>

            <?php if ($development->have_posts()) : ?>
                <?php while ($development->have_posts()) : $development->the_post(); ?>
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
                                    $province = get_user_meta($author_id, 'billing_state', true);
                                    $city = get_user_meta($author_id, 'billing_city', true);

                                    if ($city || $province) {
                                        echo '<span>' . esc_html($city);
                                        if ($city && $province) {
                                            echo ', ';
                                        }
                                        echo esc_html($province) . '</span>';
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
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>No announcements found.</p>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        </div>
        
        <!-- Pagination Container -->
        <div class="pagination-container">
            <?php
                if ($development->max_num_pages > 1) {
                    echo '<div class="pagination">';
                    
                    // Previous button
                    if ($development->query_vars['paged'] > 1) {
                        echo '<a href="#" class="page-number prev" data-page="' . ($development->query_vars['paged'] - 1) . '">← Previous</a>';
                    }
                    
                    // Page numbers
                    for ($i = 1; $i <= $development->max_num_pages; $i++) {
                        if ($i == max(1, $development->query_vars['paged'])) {
                            echo '<span class="page-number active">' . $i . '</span>';
                        } else {
                            echo '<a href="#" class="page-number" data-page="' . $i . '">' . $i . '</a>';
                        }
                    }
                    
                    // Next button
                    if ($development->query_vars['paged'] < $development->max_num_pages) {
                        echo '<a href="#" class="page-number next" data-page="' . ($development->query_vars['paged'] + 1) . '">Next →</a>';
                    }
                    
                    echo '</div>';
                }
            ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>