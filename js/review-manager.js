jQuery(document).ready(function(){

  if(jQuery("input#hidden_value").val() == "1")
    jQuery("button#review_remove_button").css("display", "none");

  jQuery("button#review_add_button").click(function(){

    var hiddenVal = jQuery("input#hidden_value").val();

    //set hidden value to one on add if hidden value is not set
    jQuery("input#hidden_value").val(parseInt(hiddenVal)+1);

  }); //end of click function to add input fields

  jQuery("button#review_remove_button").click(function(){

    var hiddenVal = jQuery("input#hidden_value").val();

    jQuery("input#services_stored_"+hiddenVal).val("");
		jQuery("input#hidden_value").val(parseInt(hiddenVal)-1);

  }); //end of click function to remove input fields
	
	//this is for the ratings area
	if(jQuery("input#hidden_value_2").val() == "1")
    jQuery("button#ratings_remove_button").css("display", "none");

  jQuery("button#ratings_add_button").click(function(){

    var hiddenVal_2 = jQuery("input#hidden_value_2").val();

    //set hidden value to one on add if hidden value is not set
    jQuery("input#hidden_value_2").val(parseInt(hiddenVal_2)+1);

  }); //end of click function to add input fields

  jQuery("button#ratings_remove_button").click(function(){

    var hiddenVal_2 = jQuery("input#hidden_value_2").val();

    jQuery("input#ratings_stored_"+hiddenVal_2).val("");
		jQuery("input#hidden_value_2").val(parseInt(hiddenVal_2)-1);

  }); //end of click function to remove input fields
		
	//display fields
	if(jQuery('#contempo_map_check').is(':checked')){
		jQuery('div.mapChecker').css('display','block');
	}else{
		jQuery('div.mapChecker').css('display','none');
	}
	
	jQuery('#contempo_map_check').click(function () {
    jQuery("div.mapChecker").toggle(this.checked);
	});
	
	//display fields
	if(jQuery('#contempo_review_check').is(':checked')){
		jQuery('div.reviewChecker').css('display','block');
	}else{
		jQuery('div.reviewChecker').css('display','none');
	}
	jQuery('#contempo_review_check').click(function () {
    jQuery("div.reviewChecker").toggle(this.checked);
	});
	
	//display fields
	if(jQuery('#testimonial_value').is(':checked')){
		jQuery('div.testimonialChecker').css('display','block');
	}else{
		jQuery('div.testimonialChecker').css('display','none');
	}
	jQuery('#testimonial_value').click(function () {
    jQuery("div.testimonialChecker").toggle(this.checked);
	});
	
}); //end of ready function