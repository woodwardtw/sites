jQuery( document ).ready(function() {
	var divs = ['child-posts','child-pages'];
	divs.forEach(function(element) {
		if (document.getElementById(element)){
	    	var postsDiv = document.getElementById(element);
	    	console.log(postsDiv.dataset.url);
	    	var url = postsDiv.dataset.url;
	    	makeContent(url, element);
	    }
	})	
});


function makeContent (url, destination){
	fetch(url)
	  .then(function(response) {
	  // Convert to JSON
	  return response.json();
	})
	  .then(function(data) {
	  // GOOD!
	  	  for (i = 0; i < data.length; i++) {
	    writeEvents(data[i], destination);
	  } 
	});

function writeEvents(data, destination) {
	  var targetDiv = '#'+destination;
	  console.log(targetDiv);	
	  var post = jQuery(targetDiv).append(
	    jQuery(
	       '<div class="child-post-wrapper">' + featureImg(data) + '<div class="child-post-summary"><a href="' + data.link + '"><h4 class="child-post-title">' + data.title.rendered + 
	       '</h4></a>' + data.excerpt.rendered + '</div></div>'
	    ) 
	  );
	}
}

function featureImg(data){
	if (data.featured_media != 0 && data._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail.source_url){
		return '<div class="child-thumb-holder"><img class="child-thumb" src="' + data._embedded['wp:featuredmedia'][0].media_details.sizes.thumbnail.source_url + '"></div>';
	} else {
		return '';//could replace this with a default image of some sort 
	}
}