<?php
/*
Plugin Name: Contempo Reviews
Version: 1.2
Plugin URI: N/A
Description: An all-in-one review, map and testimonial system
Author: James McBride
Author URI: N/A
*/
 
//load textdomain
function ctpo_init() {
  load_plugin_textdomain( 'contempo_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/textdomain/' ); 
}
add_action('plugins_loaded', 'ctpo_init');


register_activation_hook(__FILE__, 'ctpo_testimonials_activate');

function ctpo_testimonials_activate(){
	
}

if(is_admin()) :
	
	require_once(dirname(__FILE__).'/includes/testimonial_admin.php');
	//check if admin interface exists, and if so, include it and the panel
	if(file_exists(dirname(__FILE__).'/includes/admin/ctpo_interface.php') && file_exists(dirname(__FILE__).'/includes/admin/ctpo_gui.php')){
		require_once(dirname(__FILE__).'/includes/admin/ctpo_interface.php');
		require_once(dirname(__FILE__).'/includes/admin/ctpo_gui.php');
	}
	
endif;

if(!is_admin()) :
	
	//add scripts necessary for rating system
	function ctpo_scripts_method() {
		
		$plugin_url_path = WP_PLUGIN_URL;
		
		$custom = get_post_custom($post->ID);
		$featured_form_value = $custom["contempo_review_check"][0];
		$featured_maps_value = $custom["contempo_map_check"][0];
		$testimonial_value = $custom["testimonial_value"][0];
		$options = get_option('ctpo_review_options');
	
		//Load jQuery
		wp_enqueue_script('jquery');
		
		
		
		if($testimonial_value == 1):
		
			//Load rating style
			wp_enqueue_style('rating_style',
				$plugin_url_path . '/ctpo-testimonials/js/star-rating/jquery.rating.css'
			);
			
			//check if unique options are enabled
			if(!isset($options) || isset($options['testimonial_classic']) || $options['testimonial_classic'] == 'testimonial_classic'):
				//Load classic testimonial style
				wp_enqueue_style('testimonial_style',
					$plugin_url_path . '/ctpo-testimonials/css/testimonial_client.css'
				);
			else:
				//Load user defined testimonial style
				wp_enqueue_style('testimonial_style',
					$plugin_url_path . '/ctpo-testimonials/includes/admin/css/ctpo_client_style.css'
				);
				//Load user defined testimonial style
				wp_enqueue_style('unique_font',
					'http://fonts.googleapis.com/css?family=' . $options['ctpo_font_family']
				);
			endif;
		
			//Load colorbox style
			wp_enqueue_style('colorbox_style',
				$plugin_url_path . '/ctpo-testimonials/css/colorbox.css'
			);
		
			//Load ColorBox script
			wp_enqueue_script(
				'color-Box',
				$plugin_url_path . '/ctpo-testimonials/js/jquery.colorbox-min.js',
				array('rating'),
				false,
				true
			);
			
			//Load ajax script for testimonial pagination
			wp_enqueue_script(
				'ctpo-ajax-page',
				$plugin_url_path . '/ctpo-testimonials/js/jquery.ctpotest.js',
				array('jquery')
			);
			
			$nonce = wp_create_nonce( 'ctpo-hard-to-guess-nonce' );
			
			//Localize pagination script (add header so js can access filestructure)
			$ctpo_review_ajax_uri = array('review_ajax_uri' => admin_url( 'admin-ajax.php' ), 'page_value' => $nonce);
			wp_localize_script('ctpo-ajax-page', 'ctpo_ajax_object', $ctpo_review_ajax_uri );

		endif;
		
			
		//only add review scripts on review pages
		if($featured_form_value == 1 || $testimonial_value == 1 || $featured_maps_value == 1):
			
			//Load rating style
			wp_enqueue_style('rating_style',
				$plugin_url_path . '/ctpo-testimonials/js/star-rating/jquery.rating.css'
			);
			
			//Load MetaData script
			wp_enqueue_script(
				'metaData',
				$plugin_url_path . '/ctpo-testimonials/js/star-rating/jquery.MetaData.js',
				array('jquery')
			);
			
			//Load rating script
			wp_enqueue_script(
				'rating',
				$plugin_url_path . '/ctpo-testimonials/js/star-rating/jquery.rating.pack.js',
				array('jquery')
			);
			
			//Load ctpo review script
			wp_enqueue_script(
				'ctpo-script',
				$plugin_url_path . '/ctpo-testimonials/js/ctpo.testimonial.js',
				array('rating')
			);
	
			//Localize rating script (add header so js can access filestructure)
			$contempo_wp_js_uri = array('wp_js_uri' => $plugin_url_path . '/ctpo-testimonials/js/');
			wp_localize_script('rating', 'ctpo_js_uri', $contempo_wp_js_uri );
			
			//Load review client style
			wp_enqueue_style('ctpo-review-style',
				$plugin_url_path . '/ctpo-testimonials/css/review_client.css'
			);
		
		endif;
		
		
		
		if($featured_maps_value == 1):
		
			//Load map client style
			wp_enqueue_style('ctpo-map-style',
				$plugin_url_path . '/ctpo-testimonials/css/map_client.css'
			);
		
		endif;
		
		
	}
	
	add_action( 'wp_enqueue_scripts', 'ctpo_scripts_method' );

endif; //end !is_admin()

function ctpo_map_script(){

	$plugin_url_path = WP_PLUGIN_URL;
	$gmap_script = $plugin_url_path . '/ctpo-testimonials/js/gmap3/gmap3.min.js';
	$img_src = $plugin_url_path . '/ctpo-testimonials/js/star-rating/';
	
	$colorbox_script = $plugin_url_path . '/ctpo-testimonials/js/jquery.colorbox-min.js';
	
	$custom = get_post_custom($post->ID);
	$featured_maps_value = $custom["contempo_map_check"][0];
	$featured_maps_center = $custom["contempo_map_center"][0];
	$featured_maps_zoom = $custom["contempo_map_zoom"][0];
	
	$testimonial_value = $custom["testimonial_value"][0];
	
	if($featured_maps_value == 1):
		?>
		
		<script type="text/javascript" src="<?php echo $gmap_script; ?>"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&amp;language=en"></script>
		
		<script type="text/javascript">
					// Can also be used with $(document).ready()
					jQuery(document).ready(function() {
						
						
  					
						var placeCounter = 0;
						var placeObject = new Array;
						var placeExcerpt = new Array;
						var placeRating = new Array;
						var placeDate = new Array;
						
						jQuery('div#ctpo-map-container').width('100%');
						
						jQuery('div#comment-meta-tags').each(function(){
							
							var placeStars = '';
							
							placeObject[placeCounter] = jQuery(this).find('span.gmap-area').text();
							placeExcerpt[placeCounter] = jQuery(this).find('span.gmap-excerpt').text();
							placeDate[placeCounter] = jQuery(this).find('span.gmap-date').text();
							placeRating[placeCounter] = jQuery(this).find('span.gmap-rating').text();
							for(i=1;i<=5;i++){
								if(i <= placeRating[placeCounter]){
									placeStars += '<img src="<?php echo $img_src; ?>star-1.png" style="float:left; box-shadow:none !important;">';
								}else{
									placeStars += '<img src="<?php echo $img_src; ?>star-2.png" style="float:left; box-shadow:none !important;">';
								}
							}
							
							placeRating[placeCounter] = placeStars;
							placeCounter++;
								
						});
						
						//get rid of related posts link
						jQuery('div.related-posts').hide();
						
						jQuery("div#contempo_review_map").css({
					
						'border':'1px solid black',
						
						});
						jQuery("div#contempo_review_map").width('100%').height('350px').gmap3({
							
							getlatlng:{
								address:  <?php echo json_encode($featured_maps_center); ?>,
								callback: function(results){
									if ( !results ) return;
									jQuery(this).gmap3({
										map:{
											options:{
												center:results[0].geometry.location,
												zoom:parseInt(<?php echo json_encode($featured_maps_zoom); ?>)
											}
											
										}
									});
									//still in callback.  Placing markers.
									for(i=0;i<=placeCounter;i++){
										jQuery(this).gmap3({
											marker:{
												values:[
													{address:placeObject[i], data:"<div id='ctpo-data-container'><strong>"+placeDate[i]
													+"</strong><br />"+placeExcerpt[i]
													+ "<br />"+"<span style='float:left; margin-right:2px;'>Overall Rating: </span>"+placeRating[i]
													+"</div>"
													+ "<p style='clear:both;'></p>",
													 options:{/*icon: "http://maps.google.com/mapfiles/marker_green.png"*/}},
												
												],
												options:{
												draggable: false
												},
												events:{
													mouseover: function(marker, event, context){
														var map = jQuery(this).gmap3("get"),
															infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
														if (infowindow){
															infowindow.open(map, marker);
															infowindow.setContent(context.data);
															//fixes scrollbar bug.  Please do not remove. -James M
															google.maps.event.addListener(infowindow, 'domready', function() {
																document.getElementById('ctpo-data-container').parentNode.style.overflow='';
																document.getElementById('ctpo-data-container').parentNode.parentNode.style.overflow='';
																
															});
															
														} else {
															jQuery(this).gmap3({
																infowindow:{
																	anchor:marker, 
																	options:{
																		content: context.data,
																		disableAutoPan: true
																	
																	}
																	
																}
															});
														}
													},
													mouseout: function(){
														var infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
														if (infowindow){
															//infowindow.close();
														google.maps.event.addListener(infowindow, 'domready', function() {
																document.getElementById('ctpo-data-container').parentNode.style.overflow='';
																document.getElementById('ctpo-data-container').parentNode.parentNode.style.overflow='';
																
														});
														}
													}
												}
											}
										});//end looped gmap3 function
									}//end for statement
								} //end callback
							}, //end get latLong
							
							//if markers exist outside of zoom level, correct
								
						});//end gmap3 outer function
						
						jQuery('div.comment').width('100%');
						jQuery("h3#reply-title").remove();
					
						
						
						
					});
				</script>
        
        
		<?php
		
	endif;
	
	if($testimonial_value == 1):
	
	?>
		<script type="text/javascript">
			jQuery(document).ready(function(e){jQuery(".ctpo-inline").colorbox({inline:true,width:"90%"})})
		</script>
		
	<?php
		
	
	endif;
	
}

add_action('wp_footer', 'ctpo_map_script');
	

/****************************
Create Form Elements.
*****************************/

add_filter('comment_form_default_fields', 'custom_fields');
function custom_fields($fields) {
	
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields[ 'author' ] = '<p class="comment-form-author">'.
			'<label for="author"><strong>' . __( 'Name: ', 'contempo_textdomain' ) . '</strong>'.
			( $req ? '<span class="required">*</span></label>' : '' ).
			'<br /><input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) .
			'" style="width:100%;"' . $aria_req . ' /></p>';

		$fields[ 'email' ] = '<p class="comment-form-email">'.
			'<label for="email"><strong>' . __( 'Email: ', 'contempo_textdomain' ) . '</strong>'.
			( $req ? '<span class="required">*</span></label>' : '' ).
			'<br /><input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) .
			'" style="width:100%;"' . $aria_req . ' /></p>';

		$fields[ 'url' ] = '<p class="comment-form-url">'.
			'<label for="url">' . __( 'Website', 'contempo_textdomain' ) . '</label>'.
			'<input id="url" name="url" type="text" value="'. esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" /></p>';

	return $fields;
}

add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {
	
	$custom = get_post_custom($post->ID);
	
	$hidden_value = $custom["hidden_value"][0];
	$hidden_value_2 = $custom["hidden_value_2"][0];
	
	$review_phone_value = $custom["contempo_phone_check"][0];
	$review_city_value = $custom["contempo_city_check"][0];
	$review_state_value = $custom["contempo_state_check"][0];
	$review_country_value = $custom["contempo_country_check"][0];
	$review_zip_value = $custom["contempo_zip_check"][0];
	$review_title_value = $custom["contempo_title_check"][0];
	$review_recommend_value = $custom["contempo_recommend_check"][0];
	
	$featured_form_value = $custom["contempo_review_check"][0];
	
	if($featured_form_value == 1):
	
	
	
	if (isset($custom["ratings_stored_1"][0]) && $custom["ratings_stored_1"][0] != ""):
	
		echo '<hr class="review-style" /><p><em>'. __('Please rate the following based on your experience:', 'contempo_textdomain').'</em></p>';
		
		$before = '';
		//begin rating field generation
		for($i=1;$i<=$hidden_value_2;$i++):
			//get rated service
			$rating_stored = $custom["ratings_stored_".$i][0];
			//get rated image if one exists
			isset($custom["ratings_images_".$i][0]) && $custom["ratings_images_".$i][0] != '' ?
			$rating_image = '<img src="'.$custom["ratings_images_".$i][0].'" /><br />' :
			$rating_image = '';
			//optional margin
			isset($rating_image) && $rating_image != '' ? $margin_o = 'margin-bottom: 5px;' : $margin_o = '';
			
			echo $before . '<p class="comment-form-rating" style="white-space:nowrap !important;"></p>'.
			'<div style="float:left; width:100% !important; display:inline; '.$margin_o.' white-space:nowrap !important;">'.$rating_image.'<strong>'. __(''.$rating_stored, 'contempo_textdomain') . '</strong></div>
			<p><br /></p><div class="commentratingbox" style="width:60% !important; overflow:visible; white-space:nowrap !important;">';
			
			//Current rating scale is 1 to 5. If you want the scale to be 1 to 3, then set the value of $j to 3.
			for( $j=1; $j <= 5; $j++ )
				echo '<input class="star" type="radio" name="rating_'.$i.'" value="'.$j.'">';
			echo '</div>';
			
			//set the before link break if the element needs it
			isset($rating_image) && $rating_image != '' ? $before = '<p style="clear:both;"></p>' : $before = '';
			$before .= '<hr class="review-style" />';
			//get spacing right
			if ($i != $hidden_value_2)
				echo '<br />';
			else
				echo '<p style="clear:both;">&nbsp;</p>';
				
				
		endfor; //end rating field generation
	
	endif; //end rating field check
	
	if($review_phone_value == 1){
		
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-phone">'.
		'<span class="ctpo-review-text"><input id="phone" name="phone" type="text" /></span>'.
		'<label for="phone"><strong>' . __( 'Phone: ', 'contempo_textdomain' ) . '</strong></label>'.
		'</p>';
		
		
	}
	
	
	if($review_recommend_value == 1){
		
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-recommend clearfix">'.
		'<span style="float:right;"><span style="float:left;"><input class="is-recommended" type="radio" 
		name="recommend" value="yes" checked="checked" />Yes<br />'.
		'<input class="not-recommended" type="radio" name="recommend" value="no" />No</span></span>'.
		'<label for="recommend"><strong>' . __( 'Would You Recommend?', 'contempo_textdomain' ) . '</strong></label>'.
		''.
		'</p>';
		
		
	}
	
	if(isset($custom["services_stored_1"][0]) && $custom["services_stored_1"][0] != ''):
	
	echo '<hr class="review-style" />';
	
	echo '<p class="comment-form-service">'.
	
	'<span style="float:right;"><span style="float:left;">'.
	'<select id="service" name="service">';
	
		//begin service field generation
		for($i=1;$i<=$hidden_value;$i++):
			$service_stored = $custom["services_stored_".$i][0];
			echo '<option>'.$service_stored.'</option>';
		endfor;
		
		$options = get_option('ctpo_review_options');
		
		//get review type
		!isset($options['review_type']) || empty($options['review_type']) || $options['review_type'] == 'service_reviews' || $options['review_type'] == 'company_reviews' ? 
		$review_statement = __('Type of Service: ', 'contempo_textdomain') :
		$review_statement = __('Product Feature: ', 'contempo_textdomain');
			
		echo '</select></span></span>'.
		'<label for="service"><strong>' .$review_statement . '</strong></label></p>';
	
	
	endif; //end check for service stored
	
	if($review_city_value == 1){
		
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-city">'.
		'<span class="ctpo-review-text"><input id="city" name="city" type="text"/></span>'.
		'<label for="city"><strong>' . __( 'City: ', 'contempo_textdomain' ) . '</strong></label>'.
		'</p>';
	
		
	} //end check for review_city_value
	
	if($review_country_value == 1){
	
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-country">'.
		'<span class="ctpo-review-text"><input id="state" name="state" type="text"/></span>'.
		'<label for="country"><strong>' . __( 'Country: ', 'contempo_textdomain' ) . '</strong></label>'.
		'</p>';
		
	} else if($review_state_value == 1){
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-state">'.
		
		'<span style="float:right;"><span style="float:left;">'.
		'<select id="state" name="state">
			<option value=""></option>
			<option value="AL">AL</option>
			<option value="AK">AK</option>
			<option value="AZ">AZ</option>
			<option value="AR">AR</option>
			<option value="CA">CA</option>
			<option value="CO">CO</option>
			<option value="CT">CT</option>
			<option value="DE">DE</option>
			<option value="DC">DC</option>
			<option value="FL">FL</option>
			<option value="GA">GA</option>
			<option value="HI">HI</option>
			<option value="ID">ID</option>
			<option value="IL">IL</option>
			<option value="IN">IN</option>
			<option value="IA">IA</option>
			<option value="KS">KS</option>
			<option value="KY">KY</option>
			<option value="LA">LA</option>
			<option value="ME">ME</option>
			<option value="MD">MD</option>
			<option value="MA">MA</option>
			<option value="MI">MI</option>
			<option value="MN">MN</option>
			<option value="MS">MS</option>
			<option value="MO">MO</option>
			<option value="MT">MT</option>
			<option value="NE">NE</option>
			<option value="NV">NV</option>
			<option value="NH">NH</option>
			<option value="NJ">NJ</option>
			<option value="NM">NM</option>
			<option value="NY">NY</option>
			<option value="NC">NC</option>
			<option value="ND">ND</option>
			<option value="OH">OH</option>
			<option value="OK">OK</option>
			<option value="OR">OR</option>
			<option value="PA">PA</option>
			<option value="RI">RI</option>
			<option value="SC">SC</option>
			<option value="SD">SD</option>
			<option value="TN">TN</option>
			<option value="TX">TX</option>
			<option value="UT">UT</option>
			<option value="VT">VT</option>
			<option value="VA">VA</option>
			<option value="WA">WA</option>
			<option value="WV">WV</option>
			<option value="WI">WI</option>
			<option value="WY">WY</option>
		</select></span></span>
		<label for="state"><strong>' . __( 'State: ', 'contempo_textdomain' ) . '</strong></label></p>';
		}
	
	
	if($review_zip_value == 1){
		
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-zip">'.
		'<span class="ctpo-review-text"><input id="zip_code" name="zip_code" type="text"/></span>'.
		'<label for="zip_code"><strong>' . __( 'Zip/Postal Code: ', 'contempo_textdomain' ) . '</strong></label>'.
		'</p>';
	
		
	} //end check for review_zip_value
	
	if($review_title_value == 1){
		
		echo '<hr class="review-style" />';
		
		echo '<p class="comment-form-title">'.
		'<span class="ctpo-review-text"><input id="ctpo_review_title" name="ctpo_review_title" type="text"/></span>'.
		'<label for="ctpo_review_title"><strong>' . __( 'Review Title: ', 'contempo_textdomain' ) . '</strong></label>'.
		'</p>';
	
		
	} //end check for ctpo_review_title
	
	echo '<hr class="review-style" />';
	
	echo '<strong>'.__('Comment:', 'contempo_textdomain').'</strong>';
	
	endif;
}

// Save the comment meta data along with comment
// also update comment meta that needs the most recent db info from the admin side (images, etc)

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
	
	$rating_counter = 0;
	
	if ( ( isset( $_POST['author'] ) ) && ( $_POST['author'] != '') )
		$author = wp_filter_nohtml_kses($_POST['author']);
	add_comment_meta( $comment_id, 'author', $author );
	
	if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '') )
		$phone = wp_filter_nohtml_kses($_POST['phone']);
	add_comment_meta( $comment_id, 'phone', $phone );
	
	if ( ( isset( $_POST['recommend'] ) ) && ( $_POST['recommend'] != '') )
		$recommend = wp_filter_nohtml_kses($_POST['recommend']);
	add_comment_meta( $comment_id, 'recommend', $recommend );
	
	if ( ( isset( $_POST['service'] ) ) && ( $_POST['service'] != '') )
		$service = wp_filter_nohtml_kses($_POST['service']);
	add_comment_meta( $comment_id, 'service', strtolower( trim( $service ) ) );

	if ( ( isset( $_POST['city'] ) ) && ( $_POST['city'] != '') )
		$city = wp_filter_nohtml_kses($_POST['city']);
	add_comment_meta( $comment_id, 'city', strtolower( trim( $city ) ) );
	
	if ( ( isset( $_POST['city'] ) ) && ( $_POST['city'] != '') && ( isset( $_POST['service'] ) ) && ( $_POST['service'] != '') )
		$city_service = wp_filter_nohtml_kses($_POST['city']) . '_' . wp_filter_nohtml_kses($_POST['service']);
	add_comment_meta( $comment_id, 'city_service', strtolower( trim( $city_service ) ) );
	
	if ( ( isset( $_POST['state'] ) ) && ( $_POST['state'] != '') )
		$state = wp_filter_nohtml_kses($_POST['state']);
	add_comment_meta( $comment_id, 'state', $state );
	
	if ( ( isset( $_POST['zip_code'] ) ) && ( $_POST['zip_code'] != '') )
		$zip = wp_filter_nohtml_kses($_POST['zip_code']);
	add_comment_meta( $comment_id, 'zip_code', $zip );
	
	if ( ( isset( $_POST['ctpo_review_title'] ) ) && ( $_POST['ctpo_review_title'] != '') )
		$review_title = wp_filter_nohtml_kses($_POST['ctpo_review_title']);
	add_comment_meta( $comment_id, 'ctpo_review_title', $review_title );
	
	if ( ( isset( $_POST['city'] ) ) && ( isset( $_POST['state'] ) ) && ( $_POST['city'] != '') && ( $_POST['state'] != ''))
	{
		if( isset( $_POST['zip_code'] ) && $_POST['zip_code'] != ''){
			$city_state = wp_filter_nohtml_kses($_POST['city']) . ', ' . wp_filter_nohtml_kses($_POST['state']) . ', ' .  
			wp_filter_nohtml_kses($_POST['zip_code']);
		}
		else{
			$city_state = wp_filter_nohtml_kses($_POST['city']) . ', ' . wp_filter_nohtml_kses($_POST['state']);
		}
	}
	add_comment_meta( $comment_id, 'city_state', $city_state );
	
	
	$custom = get_post_custom($post->ID);
	$hidden_value_2 = $custom["hidden_value_2"][0];
		
	for($i=1; $i<=$hidden_value_2; $i++):
		if ( ( isset( $_POST['rating_'.$i] ) ) && ( $_POST['rating_'.$i] != '') )
			$rating = wp_filter_nohtml_kses($_POST['rating_'.$i]);
		add_comment_meta( $comment_id, 'rating_'.$i, $rating );
		add_comment_meta( $comment_id, 'ratings_stored_'.$i, $custom["ratings_stored_".$i][0] );
		$rating_counter++;
		
		
	endfor;
	
	if ( isset( $rating_counter ) )
		add_comment_meta( $comment_id, 'rating_enum', $rating_counter );
	
	//add comment meta for the testimonial image whether it be blank or not
	add_comment_meta( $comment_id, 'testimonial_image', $custom["testimonial_image"][0] );
}

// Add the filter to check whether the comment meta data has been filled

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
	
	$custom = get_post_custom($post->ID);
	
	$hidden_value = $custom["hidden_value"][0];
	$hidden_value_2 = $custom["hidden_value_2"][0];
	$rating_stored = $custom["ratings_stored_1"][0];
	
	//rating field check
	if(isset($rating_stored) && !empty($rating_stored)):
		for($i=1;$i<=$hidden_value_2;$i++):
			if ( ! isset( $_POST["rating_".$i] ) )
			wp_die( __( 'Error: You did not add a rating. Please use the back button on your Web browser and resubmit your comment with a rating.', 'contempo_textdomain' ) );
		endfor;
	endif;
	
	return $commentdata;
	
}


//take out fields based on conditions
add_filter('comment_form_default_fields', 'review_filtered');
function review_filtered($fields)
{
	//always take out url field
	if(isset($fields['url']))
	unset($fields['url']);
	
	
	return $fields;
}


function ctpo_comment_template( $comment_template ) {
     global $post;
		 $custom = get_post_custom($post->ID);
		 //var to include/exclude review form
		 $featured_form_value = $custom["contempo_review_check"][0];
     
     if($featured_form_value == 1){ 
        return dirname(__FILE__) . '/includes/review_template.php'; //just for awaiting mod.  tricks wordpress into not using default
     }
}

add_filter( "comments_template", "ctpo_comment_template" );

function ctpo_comment($comment){
	$GLOBALS['comment'] = $comment;
	
	if ($comment->comment_approved == '0') : ?>
		<em><span class="comment-awaiting-moderation" style="display:none;"><?php _e('Your review is awaiting moderation.', 'contempo_textdomain'); ?></span></em>
		<br />
	
	<?php endif;

}

//add phone to comment admin screen ONLY
//also includes other fields in admin panel for comments
function comment_meta_row_action( $meta_att ) {
	global $comment;
	global $post;
	
	echo '<hr /><p><strong>Contact Info and Service</strong></p><div class="comment-meta">Phone: ';
	echo get_comment_meta( $comment->comment_ID, 'phone', true );
	echo '</div>';
	echo '<div class="comment-meta">Service: ';
	echo get_comment_meta( $comment->comment_ID, 'service', true );
	echo '</div>';
	echo '<div class="comment-meta">Location: ';
	echo get_comment_meta( $comment->comment_ID, 'city_state', true );
	echo '<div class="comment-meta">Recommend? ';
	echo get_comment_meta( $comment->comment_ID, 'recommend', true );
	echo '</div><hr /><p><strong>Ratings</strong></p>';
	
	
	$custom = get_post_custom($post->ID);
	$hidden_value_2 = $custom["hidden_value_2"][0];
	
	for($i=1; $i<=$hidden_value_2; $i++):
		echo '<div class="comment-meta">'.get_comment_meta( $comment->comment_ID, 'ratings_stored_'.$i, true ).': ';
		echo get_comment_meta( $comment->comment_ID, 'rating_'.$i, true );
		echo '</div>';
	endfor;
	
	
	
	return $meta_att;
}

add_filter( 'comment_row_actions', 'comment_meta_row_action', 11, 1 );

/*
 * To put [review_form] wherever you want it
 */
 


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*\
  Testimonial plugin shortcode
\*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

require_once(dirname(__FILE__).'/includes/testimonial_shortcodes.php'); 


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*\
    Testimonial widgets code
\*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

require_once(dirname(__FILE__).'/includes/testimonial_widget.php');

// ajax function to produce comments based on page selected...
add_action('wp_ajax_my_action', 'my_action_callback');
add_action('wp_ajax_nopriv_my_action', 'my_action_callback');

function my_action_callback() {
	global $wpdb;
	//sanitize post value
	$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$page_id = filter_var($_POST["page_id"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);


$custom = get_post_custom($page_id);
	
	// create review testimonials
	$testimonial_value = $custom["testimonial_value"][0];
	$testimonial_city = $custom["testimonial_city"][0];
	$testimonial_service = $custom["testimonial_service"][0];
	$testimonial_excerpt = $custom["testimonial_excerpt_check"][0];
	$testimonial_pages = intval($custom["testimonial_pages"][0]);
	$company_type = $custom["company_type"][0];
	$company_name = $custom["company_name"][0];
	$company_url = $custom["company_url"][0];
	
	if(isset( $testimonial_city ) && $testimonial_city != '')
		$formatted_city = strtolower( trim( $testimonial_city ) );
	
	if(isset( $testimonial_service ) && $testimonial_service != '')
		$formatted_service = strtolower( trim( $testimonial_service ) );
	
	$formatted_arr = array();
	
	if(isset( $formatted_city ))
		array_push($formatted_arr, $formatted_city);
	
	if(isset( $formatted_service ))
		array_push($formatted_arr, $formatted_service);
	
	$args = array(
			
			'status' => 'approve',
			'offset' => $testimonial_pages*$page_number,
			'number' => $testimonial_pages
			
		);
		
	if(isset( $formatted_city )):
		$args = array(
			
			'meta_value' => $formatted_city,
			'status' => 'approve',
			'offset' => $testimonial_pages*$page_number,
			'number' => $testimonial_pages
			
		);
	endif;
	
	if(isset( $formatted_service )):
		$args = array(
			
			'meta_value' => $formatted_service,
			'status' => 'approve',
			'offset' => $testimonial_pages*$page_number,
			'number' => $testimonial_pages
			
		);
	endif;
	
	if(isset( $formatted_city ) && isset( $formatted_service )):
		$formatted_city_service = $formatted_city . '_' . $formatted_service;
		
		$args = array(
			
			'meta_value' => $formatted_city_service,
			'status' => 'approve',
			'offset' => $testimonial_pages*$page_number,
			'number' => $testimonial_pages
			
		);
	endif;
		
	if($testimonial_value == 1):
	
		$comment_index = 0;
		$comments = get_comments($args);
		
		foreach($comments as $comment) {
			
			$review_title = get_comment_meta( $comment->comment_ID, 'ctpo_review_title', true );
			
			
			if(strlen($comment->comment_content) > 350 && $testimonial_excerpt == 1){
				$comment_full = '<div style="display:none">
			<div id="inline_content_'.$comment_index.'" style="padding:10px; background:#fff;">
			<p style="line-height:1.5em;">'.$comment->comment_content.'</p>
			</div>
			</div>';
				$comment_exc = substr($comment->comment_content, 0, 300);
				$etc = " <a class='ctpo-inline' href='#inline_content_".$comment_index."'>[...]</a>"; 
				$comment_exc  = $comment_exc .$etc;
			}else{
				$comment_exc = $comment->comment_content;
			}
			
			$review_author = get_comment_meta( $comment->comment_ID, 'author', true );
			$name_arr = explode(' ',trim(ucfirst($review_author)));
			
			$review_city = get_comment_meta( $comment->comment_ID, 'city', true );
			$review_state = get_comment_meta( $comment->comment_ID, 'state', true );
			$review_state = trim(strtoupper($review_state));
			
			//Current rating scale is 1 to 5.
			$rating_enum = get_comment_meta( $comment->comment_ID, 'rating_enum', true );
			$rating_field = '';
			
			if(isset($rating_enum) && $rating_enum != 0):
				$agg_rating = 0;
				
				for($i=1; $i<=$rating_enum; $i++):
					$rating_value = intval(get_comment_meta( $comment->comment_ID, 'rating_'.$i, true ));
					$rating_cat = get_comment_meta( $comment->comment_ID, 'ratings_stored_'.$i, true );
					
					$rating_field .=  '<p class="rating-cat">' . $rating_cat . '</p>';
					for( $j=1; $j <= 5; $j++ ){
						
						$j == $rating_value ? $checked='checked="checked"' : $checked='';
						
						$rating_field .= '<p style="clear:both;"></p><input class="star" disabled="disabled" type="radio" 
															name="rating_'.$i.'_'.$comment_index.'" value="'.$j.'" ' . $checked . '>';
						
					}
					
					
					$agg_rating = $agg_rating + $rating_value;
					
				endfor;
				
				$agg_rating = round($agg_rating/$rating_enum, 0);
					
				$rating_field .= '<hr class="ctpo-break" /><span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				<p class="rating-cat">'.__('Overall Rating: ', 'contempo_textdomain') .'<span itemprop="ratingValue">'.$agg_rating.'</span>'
				.__('/', 'contempo_textdomain').'<span itemprop="bestRating">'.__('5', 'contempo_textdomain') .'</span></p></span>';
					
				for( $i=1; $i <= 5; $i++ ):
					
					$i == $agg_rating ? $checked='checked="checked"' : $checked='';
					
					$rating_field .= '<input class="star" disabled="disabled" type="radio" 
															name="rating_overall_'.$comment_index.'" value="'.$i.'" ' . $checked . '>';
				endfor;
				
				$rating_field .= '<p style="clear:both;">';
			
			endif; //end rating enumerator if check
			
			//get possible images
			//set the rating image
			$rating_image = $custom["testimonial_image"][0];
			isset($rating_image) && $rating_image != '' ?
			$testimonial_image = '<img itemprop="image" 
			style="float:right; margin-left:7px !important; margin-bottom:0px !important;" src="'.$rating_image.'" />' :
			$testimonial_image = '';
			
			isset($review_title) && $review_title != '' ? $review_title = '<span class="ctpo-review-title">' . $review_title . '</span><br />' :
			$review_title = '';
			
			$review_testimonials .= $comment_full . '<div class="ctpo-list-wrappers ctpo-list-'.$comment_index.'"><blockquote class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review"><div class="ctpo-effect"><div class="text-above">' . $review_title
  														  	. $testimonial_image . '<p itemprop="reviewBody"> ' . __($comment_exc) . ' </p><br />
																	<p style="clear:both;"></p>' . $rating_field . '<br />
															</div></div></blockquote>
															<div class="arrow-down"></div>
															<p style="clear:both"></p>
															<p class="testimonial-author">
															<span itemprop="author" itemscope itemtype="http://schema.org/Person">
																<span itemprop="name">' . $name_arr[0] . '</span> | 
																<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
																	<span class="contempo-city" itemprop="addressLocality">' . ucfirst($review_city) . '</span>
																	<span class="contempo-city" itemprop="addressRegion">' . $review_state . '</span>
																	
																<span> 
															</p>
															</span><p style="clear:both">&nbsp;</p></div>';

			$comment_index++;
		
		}
		
		endif; //end display testimonial if
	
	echo $review_testimonials;

	
	die();
}

// Add the comment meta (saved earlier) to the comment text
// You can also output the comment meta values directly to the comments template  

/*
add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

	$plugin_url_path = WP_PLUGIN_URL;

	if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
		$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
		$text = $commenttitle . $text;
	} 

	if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$commentrating = '<p class="comment-rating">	<img src="'. $plugin_url_path .
		'/ExtendComment/images/'. $commentrating . 'star.gif"/><br/>Rating: <strong>'. $commentrating .' / 5</strong></p>';
		$text = $text . $commentrating;
		return $text;
	} else {
		return $text;
	}
}
*/


//delete unnecessary comments on plugin uninstall.
//if this is missing and plugin is uninstalled and
//reinstalled, serious problems could occur.

/*This information may be sensitive and not desirable to be deleted.  If requested enough, I will include this.
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ){
    exit("You have deleted all files and directories associated with Contempo Testimonials.");

	$comments = get_comments();
	foreach($comments as $comment) {
		delete_comment_meta($comment->comment_ID, 'phone');
		delete_comment_meta($comment->comment_ID, 'recommend');
		delete_comment_meta($comment->comment_ID, 'city');
	}
}
*/

?>