<?php get_header(); ?>	

    <body>  
    	<div class="timeline">
			<div class="content-area">
				<main id="main" class="site-main" role="main">
       				 <div id="content">       				 	
				        <?php if(have_posts()): while(have_posts()): the_post(); ?>
				        	<?php 
				        	$post_id = get_the_ID();
				        	totalPages($post_id);?>
				           <?php 
				          	 $post_id = get_the_ID();
				          	 $url = get_post_meta($post_id, 'site-url', true);
				          	 $name = get_post_meta($post_id, 'child-title', true);
				          	 $description = get_post_meta($post_id, 'child-description', true);

				          	$posts = get_post_meta($post_id, 'total-posts', true);
							$pages = get_post_meta($post_id, 'total-pages', true);

							$posts_url = get_post_meta($post_id, 'full-api-url', true) . 'wp/v2/posts?per_page=10';
							$pages_url = get_post_meta($post_id, 'full-api-url', true) . 'wp/v2/pages?per_page=10';

				          	echo '<h1 class="site-title">' . $name . '</h1>';
				          	if($description){
				          		echo '<h2>'. $description .'</h2>';
				          	} 
				          	if ($posts > 0){
				          		echo '<h3 class="child-content-header"> Posts: ' . $posts . '</h3>';
				          		echo '<div class="child-content" id="child-posts" data-url="' . $posts_url . '"></div>';
				          	}
				          	if ($pages > 0 ){
				          		echo '<h3 class="child-content-header">Pages: ' . $pages . '</h3>';
				          		echo '<div class="child-content" id="child-pages" data-url="' . $pages_url . '"></div>';				          	
				          	}
				          	
				          	 ?>	          	 		
						<?php endwhile; endif;?>

				    </div>      
				</main><!-- #main -->
			</div><!-- .content-area -->
		</div><!-- .timeline -->
	</body>
<?php get_footer();