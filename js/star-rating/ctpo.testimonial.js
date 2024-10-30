jQuery(document).ready(function(){
	
	//display awaiting moderation message after user submits form.
	if(jQuery('.comment-awaiting-moderation').length != 0)
		jQuery("#respond").prepend('<em class="comment-awaiting-moderation">'+jQuery('.comment-awaiting-moderation').html()+'</em>');
		
	
	
});