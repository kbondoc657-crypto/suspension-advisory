<?php
/**
 * Template Name: Login
 */
 get_header(); ?>

<div class="login-page">
    <div class="container">
        <!-- <h1>Login to Your Account</h1>
        <p>Access your account to manage your class suspension announcements and preferences.</p> -->

        <div class="login-form">
            <?php echo do_shortcode('[user_registration_login]'); ?>
        </div>
     
    </div>
</div>

 <?php get_footer(); ?>