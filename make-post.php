<?php 

/*
Template Name: Manually run site generator
*/

get_header();
if(have_posts()): while(have_posts()): the_post();
	the_content();
	endwhile; endif;
//GETTING THE SITES
	run_import();


get_footer();