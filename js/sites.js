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
	       '<div class="child-post-content"><a href="' + data.link + '"><h4>' + data.title.rendered + '</h4></a><div class="child-post-summary">' + data.excerpt.rendered + '</div></div>'
	    ) 
	  );
	}
}