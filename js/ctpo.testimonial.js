jQuery(document).ready(function(){
	
	//display awaiting moderation message after user submits form.
	if(jQuery('.comment-awaiting-moderation').length != 0)
		jQuery("#respond").prepend('<em class="comment-awaiting-moderation">'+jQuery('.comment-awaiting-moderation').html()+'</em>');
		
	jQuery("#results").load("fetch_pages.php", {'page':0}, function() {jQuery("#1-page").addClass('active');});  //initial page number to load

    jQuery(".paginate_click").click(function (e) {
        
        jQuery("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');
        
        var clicked_id = jQuery(this).attr("id").split("-"); //ID of clicked element, split() to get page number.
        var page_num = parseInt(clicked_id[0]); //clicked_id[0] holds the page number we need 
        
        jQuery('.paginate_click').removeClass('active'); //remove any active class
        
        //post page number and load returned data into result element
        //notice (page_num-1), subtract 1 to get actual starting point
        jQuery("#results").load("fetch_pages.php", {'page': (page_num-1)}, function(){

        });

        jQuery(this).addClass('active'); //add active class to currently clicked element
        
        return false; //prevent going to herf link
    }); 
	
	
});