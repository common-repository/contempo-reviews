jQuery(document).ready(function() {
		
		
		var ctpo_review_uri = ctpo_ajax_object.review_ajax_uri;
		jQuery(".ctpo-inline").colorbox({className:'ctpo'});
		
    jQuery(".paginate_click").click(function (e) {
        
        jQuery("#contempo-testimonial-wrapper").prepend('<div class="loading-indication">Loading...</div>');
				
				function scrollToAnchor(aid){
					var aTag = jQuery("a[name='"+ aid +"']");
					jQuery('html,body').animate({scrollTop: aTag.offset().top}, 1);
				}
        
        var clicked_id = jQuery(this).attr("id").split("-"); //ID of clicked element, split() to get page number.
        var page_num = parseInt(clicked_id[0]); //clicked_id[0] holds the page number we need 
				var pageID = jQuery('#pagination-page').text();
        
        jQuery('.paginate_click').removeClass('active'); //remove any active class
				
				//ensuring all things work well
				var data = {
					action: 'my_action',
					page: page_num-1,     // We pass php values differently in Wordpress!
					page_id: pageID
				};
        
        //post page number and load returned data into result element
        //notice (page_num-1), subtract 1 to get actual starting point
        jQuery("#contempo-testimonial-wrapper").load(ctpo_review_uri, data, function(){
					//re-initialize rating script
					jQuery('input[type=radio].star').rating();
					//re-initialize colorbox
					jQuery(document).ready(function(e){jQuery(".ctpo-inline").colorbox({inline:true,width:"90%", className:'ctpo'})})
					//create transition
					jQuery(this).hide().fadeIn('slow');
        });
				
				scrollToAnchor('review-anchor');
				
        jQuery(this).addClass('active'); //add active class to currently clicked element
        //prevent click
				return false;
    }); //end click
				
		
});