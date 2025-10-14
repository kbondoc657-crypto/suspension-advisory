<?php
/**
 * Template Name: LGU Registration Form
 */
 get_header(); ?>

<div class="registration">
    <div class="container">
        <h1>Register an Account</h1>
        <p>Create an account to receive the latest class suspension announcements directly to your email.</p>

        <?php echo do_shortcode('[user_registration_form id="6"]'); ?>
    </div>
</div>

 <?php get_footer(); ?>