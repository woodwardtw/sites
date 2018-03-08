<?php get_header(); ?>	

    <body>  
    	<div class="timeline">
			<div class="content-area">
				<main id="main" class="site-main" role="main">
       				 <div id="timeline-embed"></div>
				        <?php if(have_posts()): while(have_posts()): the_post(); ?>
				           <?php 
				          	 $post_id = get_the_ID();
				          	 $url = get_post_meta($post_id, 'site-url', true);
				          	echo '<h2>post_id' . $post_id . '</h2>';
				          	echo '<h3>'.the_title().'</h3>';

				          	//test
				          	$url = get_post_meta($post_id, 'site-url', true);
				          	var_dump($url);
							 var_dump(get_title($url)['text']);


				          	 ?>	          	 		
						<?php endwhile; endif;?>

				        

				</main><!-- #main -->
			</div><!-- .content-area -->
		</div><!-- .timeline -->
	</body>
<?php get_footer();