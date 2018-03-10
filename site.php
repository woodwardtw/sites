<?php get_header(); ?>	

    <body>  
    	<div class="syndicated-site">
			<div class="content-area">
				<main id="main" class="site-main" role="main">
       				 <div id="sites-content">       				 	
				        <?php if(have_posts()): while(have_posts()): the_post(); ?>
				        	<?php 
				        	$post_id = get_the_ID();				       
				          	$url = get_post_meta($post_id, 'site-url', true);
				          	$name = get_post_meta($post_id, 'child-title', true);
				          	$description = get_post_meta($post_id, 'child-description', true);

				          	$posts = get_post_meta($post_id, 'total-posts', true);
							$pages = get_post_meta($post_id, 'total-pages', true);
/*TESTING STUFF*/
							$api = get_post_meta($post_id, 'full-api-url', true);
							$request = wp_remote_get($api);
							
							$body = wp_remote_retrieve_body( $request );
							$data = json_decode( $body );
							var_dump($data->description);														

							$posts_url = get_post_meta($post_id, 'full-api-url', true) . 'wp/v2/posts?per_page=10&_embed';
							$pages_url = get_post_meta($post_id, 'full-api-url', true) . 'wp/v2/pages?per_page=10&_embed';
							
							echo '<a href="' . $url .'">';
							the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full', 'alt' => 'Screenshot of the site.']);
							echo '</a>';

				          	echo '<h1 id="syndicated-site-title">' . $name . '</h1>';
				          	if($description){
				          		echo '<h2 id="syndicated-site-description">'. $description .'</h2>';
				          	} 
				          	if ($posts > 0){
				          		echo '<h3 class="child-content-header">Total Posts: ' . $posts . '</h3>';
				          		echo '<div class="child-content" id="child-posts" data-url="' . $posts_url . '"></div>';
				          	}
				          	if ($pages > 0 ){
				          		echo '<h3 class="child-content-header">Total Pages: ' . $pages . '</h3>';
				          		echo '<div class="child-content" id="child-pages" data-url="' . $pages_url . '"></div>';				          	
				          	}
				          	
				          	 ?>	          	 		
						<?php endwhile; endif;?>

				    </div>      
				</main><!-- #main -->
			</div><!-- .content-area -->
		</div><!-- .syndicated-site -->
	</body>
<?php get_footer();