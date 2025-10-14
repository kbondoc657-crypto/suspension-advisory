<?php 
/**
 * The template for displaying 404 pages (not found)
 * 
 */

 get_header();
?>

<div id="primary" class="content-area">
        <main id="main" class="site-main">
		<section class="error-404 not-found">

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Not Found', 'etiqa' ); ?></h1>
			</header>


            <div class="page-content">
                <h2><?php _e( 'This is somewhat embarrassing, isn’t it?', 'etiqa' ); ?></h2>
                <p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'etiqa' ); ?></p>

                <?php get_search_form(); ?>
            </div><!-- .page-content -->
	

		</section><!-- #content -->
        </main>
	</div><!-- #primary -->
<?php 
get_footer();