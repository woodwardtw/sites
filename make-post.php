<?php 

/*
Template Name: Manually run site generator
*/

get_header();

//GETTING THE SITES
	$string = file_get_contents("/home/devreclaim/davidsoninstalls.json");
	$installs = json_decode($string, true);
	$urls = array();
	$i = 1;
	echo '<ol>';
	foreach ($installs['data'] as $install){
		//array_push($urls, array('url'=>$install['url']));	
		//while ($i < 3){
		$result = check_for_site($install['url']);	
		echo '<li>' . $install['url'] . ' ' . $result .'</li>';		
			//$result = makeSite($install['url']);	
									
		//$i++;
		//}
	}

	echo '</ol>';


	//$json = json_encode($urls);

	//print $json;

/*
function checkSite($url){
	$fullUrl = $url .'/wp-json';
	$response = wp_remote_get( $url,  array( 'timeout' => 25 ) );

    if( ! is_wp_error( $response ) 
        && isset( $response['response']['code'] )        
        && 200 === $response['response']['code'] )
    {
        $body = wp_remote_retrieve_body( $response );//RETURNING JSON API DATA
        $data   = json_decode( $body, true );
        if($data['ddm_tag'] || $data->ddm_tag){
        	return 'true';
    	} else {
    		return 'false';
    	}
    }
}
*/

function check_for_site($url){
		$args = array(
		'post_type' => 'site',
		'post_status' => 'published',
		'meta_key' => 'site-url',
		'meta_value' => serialize(array('text'=>$url)),
		'compare' => '='							           
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
				return 'already here';
			} else {
				makeSite($url);
				return 'site added';
			}
			wp_reset_postdata();
	}




function makeSite($url){
	// Create post object
	$input = ['text'=>$url];
	$my_post = array(
	  'post_title'    => $url,
	  'post_content'  => '',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type' => 'site',
	  'meta_input' => array(
        'site-url' => $input,
	    ),
	);
	// Insert the post into the database
	$result = wp_insert_post( $my_post );
	var_dump($result);
}

get_footer();