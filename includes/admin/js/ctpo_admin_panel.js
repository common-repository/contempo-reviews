(function(d){d.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","color","outlineColor"],function(f,e){d.fx.step[e]=function(g){if(!g.colorInit){g.start=c(g.elem,e);g.end=b(g.end);g.colorInit=true}g.elem.style[e]="rgb("+[Math.max(Math.min(parseInt((g.pos*(g.end[0]-g.start[0]))+g.start[0]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[1]-g.start[1]))+g.start[1]),255),0),Math.max(Math.min(parseInt((g.pos*(g.end[2]-g.start[2]))+g.start[2]),255),0)].join(",")+")"}});function b(f){var e;if(f&&f.constructor==Array&&f.length==3){return f}if(e=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(f)){return[parseInt(e[1]),parseInt(e[2]),parseInt(e[3])]}if(e=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(f)){return[parseFloat(e[1])*2.55,parseFloat(e[2])*2.55,parseFloat(e[3])*2.55]}if(e=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(f)){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}if(e=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(f)){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}if(e=/rgba\(0, 0, 0, 0\)/.exec(f)){return a.transparent}return a[d.trim(f).toLowerCase()]}function c(g,e){var f;do{f=d.css(g,e);if(f!=""&&f!="transparent"||d.nodeName(g,"body")){break}e="backgroundColor"}while(g=g.parentNode);return b(f)}var a={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]}})(jQuery);

jQuery(function(jQuery) {
	
    jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
		
		var isFirefox = typeof InstallTrigger !== 'undefined';
		
		//custom styling select elements
		if(isFirefox != true)
			jQuery('.ctpoCustomSelect').customSelect();
		//custom styling checkbox and radio buttons
		
		jQuery('input').iCheck({
    	checkboxClass: 'icheckbox_square-grey',
    	radioClass: 'iradio_square-grey',
    	increaseArea: '20%' // optional
  	});
		
		"use strict";
    //This if statement checks if the color picker widget exists within jQuery UI
    //If it does exist then we initialize the WordPress color picker on our text input field
    if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){
			jQuery( '.ctpo-color-field' ).wpColorPicker();
    }
    else {
			//We use farbtastic if the WordPress color picker widget doesn't exist
			jQuery( '.ctpo-colorpicker' ).farbtastic( '.ctpo-color-field' );
    }
		
		// Uploading media files
		var file_frame;
 
		jQuery('.upload_image_button').live('click', function( event ){
	 	
			event.preventDefault();
	 		var currEl = this;
			var currID = jQuery(currEl).prev('.url-holder').attr('id');
			jQuery('div #img-'+currID).hide();
			
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
	 
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
					text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});
	 
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();
	 			
				// Do something with attachment.id and/or attachment.url here
				jQuery(currEl).prev('.url-holder').val(attachment.url);
				jQuery('div #img-'+currID).html('<img src="'+attachment.url+'" class="ctpo-img-preview" />');
				jQuery('div #img-'+currID).fadeIn('fast');
			});
	 
			// Finally, open the modal
			file_frame.open();
		});
		
		
});
