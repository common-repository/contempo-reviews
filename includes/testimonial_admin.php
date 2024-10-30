<?php

function ctpo_add_custom_box($postType) {
	$types = get_post_types( '', 'names' ); 
	if(in_array($postType, $types)){
		add_meta_box(
				'review-manager-meta',
				__( 'Customer Review Options', 'contempo_textdomain' ),
				'review_manager_meta_options',
				$postType,
				"normal",
				"high"
		);
	}
}

add_action( 'add_meta_boxes', 'ctpo_add_custom_box' );

//Add scripts based on post type

function ctpo_portfolio_script_addition(){
  global $post;
	$plugin_url_path = WP_PLUGIN_URL;
	
	$types = get_post_types( '', 'names' );
	if(in_array($post->post_type, $types)){
		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-slider");
    wp_enqueue_script("review-manager-js", $plugin_url_path . "/ctpo-testimonials/js/review-manager.js", array( 'jquery-ui-slider' ));
		wp_enqueue_style("review-manager-css", $plugin_url_path . "/ctpo-testimonials/css/testimonial_admin.css");
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('ctpo-upload', $plugin_url_path . '/ctpo-testimonials/js/ctpo-media-upload.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('ctpo-upload');
  }
}

add_action('admin_enqueue_scripts', 'ctpo_portfolio_script_addition');

function review_manager_meta_options(){
	
  global $post;
	
  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
    return $post_id;
  $custom = get_post_custom($post->ID);
	
	//var to include/exclude review map
	$featured_maps_value = $custom["contempo_map_check"][0];
	$featured_maps_center = $custom["contempo_map_center"][0];
	$featured_maps_zoom = $custom["contempo_map_zoom"][0];
	
	//var to include/exclude review form
	$featured_form_value = $custom["contempo_review_check"][0];
	//review form title and description
	$featured_form_title = $custom["contempo_review_title"][0];
	$featured_form_description = $custom["contempo_review_description"][0];
	
	//labels and values, and hidden value 
	//to tell how many textboxes are needed
	//for review form services
	$title_value = $custom["contempo_title_check"][0];
	$phone_value = $custom["contempo_phone_check"][0];
	$recommend_value = $custom["contempo_recommend_check"][0];
	$city_value = $custom["contempo_city_check"][0];
	$state_value = $custom["contempo_state_check"][0];
	$country_value = $custom["contempo_country_check"][0];
	$zip_value = $custom["contempo_zip_check"][0];
	
	//testimonial data
	$testimonial_value = $custom["testimonial_value"][0];
	$testimonial_city = $custom["testimonial_city"][0];
	$testimonial_service = $custom["testimonial_service"][0];
	$testimonial_image = $custom["testimonial_image"][0];
	$testimonial_excerpt = $custom["testimonial_excerpt_check"][0];
	$testimonial_pages = $custom["testimonial_pages"][0];
	$company_type = $custom["company_type"][0];
	$company_name = $custom["company_name"][0];
	$company_url = $custom["company_url"][0];
	
	//declare hidden values, services and ratings
	$hidden_value = $custom["hidden_value"][0];
  $services_stored = array();
	
	$hidden_value_2 = $custom["hidden_value_2"][0];
	$ratings_stored = array();
	$ratings_images = array();
	
	$hidden_value_meta = get_post_meta($post->ID, "hidden_value", true);
	$hidden_value_meta_2 = get_post_meta($post->ID, "hidden_value_2", true);
	
	//put values into array variables for use later
  array_push($services_stored, $custom["services_stored_1"][0]);
	array_push($ratings_stored, $custom["ratings_stored_1"][0]);
	array_push($ratings_images, $custom["ratings_images_1"][0]);
	
	for($i=2;$i<=$hidden_value_meta;$i++){
    array_push($services_stored, $custom["services_stored_".$i][0]);
  }
	
	for($i=2;$i<=$hidden_value_meta_2;$i++){
		array_push($ratings_stored, $custom["ratings_stored_".$i][0]);
		array_push($ratings_images, $custom["ratings_images_".$i][0]);
  }
	
	?>
  
  <div class="review_manager_extras clearfix">
  	<?php
			
			?>
     
    <!-- Begin Review Map -->
    <div id="review-map" class="clearfix">
    
    	<h2 class="testimonial"><?php _e('Review Map', 'contempo_textdomain'); ?></h2>
    	
      
    	<div><label><b><?php _e('Display map for testimonials: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_map_check'; ?>" type="checkbox" style="margin-left:10px;"
      value="<?php echo $featured_maps_value; ?>" id="<?php echo 'contempo_map_check'; ?>" 
      <?php checked('1', $featured_maps_value); ?> /></div>
      
      <div class="mapChecker" >
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Map City Center:  ', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'contempo_map_center' ?>" rows="3" cols="50" value="<?php echo $featured_maps_center; ?>"
      id="<?php echo 'contempo_map_center' ?>"  
      placeholder="<?php _e('e.g., Pensacola, FL', 'contempo_textdomain'); ?>" style="width:30%;"/>
      </div>
      
      <hr class="review-manager-divider" />
      
      <div>
        <label for="contempo_map_zoom"><strong><?php _e('Zoom: ', 'contempo_textdomain'); ?></strong></label>
        <select name="contempo_map_zoom" id="contempo_map_zoom">
        	<?php for($i=1;$i<=12;$i++): ?>
          	<option value=<?php echo $i ?>
          	<?php $featured_maps_zoom == $i ? print_r('selected="selected"') : print_r(''); ?> ><?php echo $i ?></option>
          <?php endfor; ?>
        </select>
      </div>
      
      <p style="clear:both;"></p>
      <hr class="review-manager-divider large-divider" />
      <p><?php _e('Note:', 'contempo_textdomain'); ?><br /><?php _e('Map', 'contempo_textdomain'); ?> <strong><?php _e('must have city center', 'contempo_textdomain'); ?></strong> <?php _e('to initialize.', 'contempo_textdomain'); ?>  <br /><?php _e('To display the map, copy this shortcode and paste it in the text editor where you want it:', 'contempo_textdomain'); ?> <strong><?php _e('[review_map]', 'contempo_textdomain'); ?></strong></p>
      
  	</div>
    
    </div>
    <!-- Begin Testimonials -->
    <div id="review-testimonials" class="clearfix">
    
    	<h2 class="testimonial"><?php _e('Testimonials', 'contempo_textdomain'); ?></h2>
    
    	<div><label><b><?php _e('Display Testimonials: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'testimonial_value'; ?>" type="checkbox" style="margin-left:10px;"
      value="<?php echo $testimonial_value; ?>" id="<?php echo 'testimonial_value'; ?>" 
      <?php checked('1', $testimonial_value); ?> /></div>
      
      <div class="testimonialChecker" >
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Display Testimonials for what city?  ', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'testimonial_city' ?>" rows="3" cols="50" value="<?php echo $testimonial_city; ?>"
      id="<?php echo 'testimonial_city' ?>" 
      placeholder="<?php _e('e.g., Chicago', 'contempo_textdomain'); ?>" style="width:30%;"/>
      </div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Display Testimonials for what service?  ', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'testimonial_service' ?>" rows="3" cols="50" value="<?php echo $testimonial_service; ?>"
      id="<?php echo 'testimonial_service' ?>" 
      placeholder="<?php _e('e.g., Roofing', 'contempo_textdomain'); ?>" style="width:30%;"/>
      </div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Product / Service Image:', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'testimonial_image'; ?>" value="<?php echo $testimonial_image; ?>" 
      placeholder="<?php _e('e.g., http://www.imageexample.com/my-image.jpg', 'contempo_textdomain'); ?>"
      id="<?php echo 'testimonial_image'; ?>" class="review-textfield ctpo-media-upload" />
      <input  class="ctpo-upload-button button" type="button" value="Upload Image" />
      </div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Paginate Testimonials After:', 'contempo_textdomain'); ?></b></label>
      <select id="testimonial_pages" name="<?php echo 'testimonial_pages'; ?>">
        <option value="600" 
				<?php $testimonial_page == 600 ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Do Not Paginate', 'contempo_textdomain'); ?></option>  
          
        <?php for($i=1;$i<21;$i++): ?>
          <option value=<?php echo $i ?> 
          <?php $testimonial_pages == $i ? print_r('selected="selected"') : print_r(''); ?> ><?php _e($i, 'contempo_textdomain'); ?></option>    
        <?php endfor; ?>
         
			</select></div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Create excerpt if testimonial is longer than 350 characters? : ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'testimonial_excerpt_check'; ?>" type="checkbox" style="margin-left:10px;"
      value="<?php echo $testimonial_excerpt; ?>" id="<?php echo 'testimonial_excerpt_check'; ?>" 
      <?php checked('1', $testimonial_excerpt); ?> /></div>
            
      
      <p style="clear:both;"></p>
      <hr class="review-manager-divider large-divider" />
      
      <h2 class="testimonial" style="font-size:15px; margin-top:0px; margin-bottom:0px;"><?php _e('SEO Info', 'contempo_textdomain'); ?></h2>
      
      <div><label><b><?php _e('Company Type', 'contempo_textdomain'); ?></b></label>
      <select id="company_type" name="<?php echo 'company_type'; ?>">
        <option value="LocalBusiness" 
				<?php $company_type == "LocalBusiness" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Other', 'contempo_textdomain'); ?></option>
        <option value="Church"
        <?php $company_type == "Church" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Church', 'contempo_textdomain'); ?></option>
        <option value="HealthAndBeautyBusiness"
        <?php $company_type == "HealthAndBeautyBusiness" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Health and/or Beauty', 'contempo_textdomain'); ?></option>
        <option value="RealEstateAgent"
        <?php $company_type == "RealEstateAgent" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Real Estate Agent', 'contempo_textdomain'); ?></option>
        <option value="MaidService"
        <?php $company_type == "MaidService" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Maid Service', 'contempo_textdomain'); ?></option>
        <option value="HomeAndConstructionBusiness"
        <?php $company_type == "HomeAndConstructionBusiness" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Home and Construction Business', 'contempo_textdomain'); ?></option>
    
        <option value="Locksmith"
        <?php $company_type == "Locksmith" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Locksmith', 'contempo_textdomain'); ?></option>
        <option value="MovingCompany"
        <?php $company_type == "MovingCompany" ? print_r('selected="selected"') : print_r(''); ?> ><?php _e('Moving Company', 'contempo_textdomain'); ?></option>
        
        
			</select></div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('Company Name:  ', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'company_name' ?>" style="width:40%; margin-top:5px;" value="<?php echo $company_name; ?>"
      id="<?php echo 'company_name' ?>" 
      placeholder="<?php _e('e.g., John Doe Construction', 'contempo_textdomain'); ?>" style="width:30%;"/>
      </div>
      
      <hr class="review-manager-divider" />
      
      <div><label><b><?php _e('URL:  ', 'contempo_textdomain'); ?></b></label><br /><input 
      name="<?php echo 'company_url' ?>" style="width:60%; margin-top:5px;" value="<?php echo $company_url; ?>"
      id="<?php echo 'company_url' ?>" 
      placeholder="<?php _e('e.g., http://www.johndoeconstruction.com', 'contempo_textdomain'); ?>" style="width:30%;"/>
      </div>
      
      
      <p style="clear:both;"></p>
      <hr class="review-manager-divider large-divider" />
      
      <p><?php _e('Note: ', 'contempo_textdomain'); ?><br />
      <?php _e('To display testimonials for all cities or all services, 
			simply leave either or both of those fields blank.', 'contempo_textdomain'); ?><br />
      
      <?php _e('To display the testimonials, 
			copy this shortcode and paste it in the text editor where you want it: ', 'contempo_textdomain'); ?>
      <strong><?php _e('[review_testimonials]', 'contempo_textdomain'); ?></strong></p>
      
  	</div>
    
    </div>
    
    <!-- Begin Review Form -->
    <div id="review-form" class="clearfix">
    
      <h2 class="testimonial"><?php _e('Review Form', 'contempo_textdomain'); ?></h2>
      <div>
        <div class="review_form_check">
          <div><label><b><?php _e('Display form for reviews: ', 'contempo_textdomain'); ?></b></label><input 
          name="<?php echo 'contempo_review_check'; ?>" type="checkbox"
          style="margin-left:10px;"
          value="<?php echo $featured_form_value; ?>" id="<?php echo 'contempo_review_check'; ?>" 
          <?php checked('1', $featured_form_value); ?> />
          </div>
        </div>
        
      </div>
        <div class="reviewChecker" >
        
        <!-- Editing -->
        <hr class="review-manager-divider" />
        <div>
          <div><label><b><?php _e('Review Form Title: ', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'contempo_review_title'; ?>" style="width:92%;"
            value="<?php echo $featured_form_title; ?>" placeholder="<?php _e('e.g., Leave a Review', 'contempo_textdomain'); ?>"
            id="<?php echo 'contempo_review_title'; ?>" class="review-textfield" /></div>
        </div>
        
        <hr class="review-manager-divider" />
        
        <div>
          <div><label><b><?php _e('Review Form Description: ', 'contempo_textdomain'); ?></b></label><br /><textarea name="<?php echo 'contempo_review_description'; ?>"
            placeholder="<?php _e('e.g., Please leave a brief review and rating of the service based on your experience...',
						'contempo_textdomain'); ?>"
            style="width:80%; height:auto;"
            id="<?php echo 'contempo_review_description'; ?>" class="review-textfield" ><?php echo $featured_form_description; ?></textarea></div>
        </div>
        
        <hr class="review-manager-divider large-divider">
      
      <?php
        /**********************************
        Beginning of Type of Service Display
        ***********************************/
        if( is_numeric($hidden_value_meta) && $hidden_value_meta > 1):
          //loop through and put label and value on page
          for($i=1;$i<=$hidden_value_meta;$i++):
      ?>
      <div style="width:160px;">
        <hr class="review-manager-divider divider-<?php echo $i; ?>" />
        <div class="service_labels">
          <div><label><b><?php _e('Type of Service:', 'contempo_textdomain'); ?></b></label><input name="<?php echo 'services_stored_'.$i ?>"
            value="<?php echo $services_stored[$i-1]; ?>" placeholder="<?php _e('Installment, Repair, etc...', 'contempo_textdomain'); ?>"
            id="<?php echo 'services_stored_'.$i ?>" class="review-textfield" /></div>
        </div>
        <!-- Hidden value for field calculation -->
        <div class="review_manager_hidden_field">
          <div><input name="hidden_value" id="hidden_value" style="display:none;" 
            value="<?php echo $hidden_value; ?>" /></div>
        </div>
      </div>
    <?php
    
    
      
        endfor;
      else:
      ?>
     
    <div class="portfolio_manager_labels">
      <div><label><b><?php _e('Type of Service:', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'services_stored_1' ?>"
        value="<?php echo $services_stored[0]; ?>" placeholder="<?php _e('e.g., Installment', 'contempo_textdomain'); ?>"
        id="<?php echo 'services_stored_1' ?>" class="review-textfield" /></div>
    </div>
    
    <div class="review_manager_hidden_field">
      <div><input name="hidden_value" id="hidden_value" style="display:none;" 
        value="1" /></div>
    </div>
    
    <?php
    
    
      endif;
    
    ?>
    <ul class="review_manager_buttons">
      <li><button type="submit" id="review_add_button" value="Add" class="button" ><?php _e('Add Service', 'contempo_textdomain'); ?></button></li>
      <li><button type="submit" id="review_remove_button" value="Remove" class="button" ><?php _e('Remove Service', 'contempo_textdomain'); ?></button></li>
    </ul>
    <p>&nbsp;</p>
    <hr class="review-manager-divider large-divider" />
    
    <?php
        /**********************************
        Beginning of Service Rating Display
        ***********************************/
        if( is_numeric($hidden_value_meta_2) && $hidden_value_meta_2 > 1):
          //loop through and put label and value on page
          for($i=1;$i<=$hidden_value_meta_2;$i++):
      ?>
      <div style="width:160px;">
        <hr class="review-manager-divider divider-<?php echo $i; ?>" />
        <div class="rating_categories">
          <div><label><b><?php _e('Rated Service / Item:', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'ratings_stored_'.$i ?>"
            value="<?php echo $ratings_stored[$i-1]; ?>" placeholder="<?php _e('e.g., Timeliness', 'contempo_textdomain'); ?>"
            id="<?php echo 'ratings_stored_'.$i ?>" class="review-textfield" /></div>
        </div>
      
      <div class="rating_categories">
          <div><label><b><?php _e('Image:', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'ratings_images_'.$i ?>"
            value="<?php echo $ratings_images[$i-1]; ?>" placeholder="<?php _e('e.g., http://www.imageexample.com/my-image.jpg', 'contempo_textdomain'); ?>"
            id="<?php echo 'ratings_images_'.$i ?>" class="review-textfield ctpo-media-upload" />
            <input  class="ctpo-upload-button button" type="button" value="Upload Image" />
            </div>
            
        </div>
        
      </div>
      
        <!-- Hidden value for field calculation -->
        <div class="review_manager_hidden_field_2">
          <div><input name="hidden_value_2" id="hidden_value_2" style="display:none;" 
            value="<?php echo $hidden_value_2; ?>" /></div>
        </div>
    <?php
    
    
      
        endfor;
      else:
      ?>
      
    <div class="rating_categories">
      <div><label><b><?php _e('Rated Service / Item:', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'ratings_stored_1' ?>"
        value="<?php echo $ratings_stored[0]; ?>" placeholder="<?php _e('e.g., Timeliness', 'contempo_textdomain'); ?>"
        id="<?php echo 'ratings_stored_1' ?>" class="review-textfield" /></div>
    </div>
    
    <div class="rating_categories">
      <div><label><b><?php _e('Image:', 'contempo_textdomain'); ?></b></label><br /><input name="<?php echo 'ratings_images_1' ?>"
        value="<?php echo $ratings_images[0]; ?>" placeholder="<?php _e('e.g., http://www.imageexample.com/my-image.jpg', 'contempo_textdomain'); ?>"
        id="<?php echo 'ratings_images_1' ?>" class="review-textfield ctpo-media-upload" />
        <input  class="ctpo-upload-button button" type="button" value="Upload Image" />
        </div>
        
    </div>
    
    <div class="review_manager_hidden_field_2">
      <div><input name="hidden_value_2" id="hidden_value_2" style="display:none;" 
        value="1" /></div>
    </div>
    
    <?php
    
    
      endif;
    
    ?>
    <ul class="review_manager_buttons">
      <li><button type="submit" id="ratings_add_button" value="Add" class="button" ><?php _e('Add Service / Item', 'contempo_textdomain'); ?></button></li>
      <li><button type="submit" id="ratings_remove_button" value="Remove" class="button" ><?php _e('Remove Service / Item', 'contempo_textdomain'); ?></button></li>
    </ul>
    <p>&nbsp;</p>
    <hr class="review-manager-divider large-divider">
    
    <h2 class="testimonial" style="font-size:15px; margin-top:0px; margin-bottom:0px;"><?php _e('Other Review Form Fields', 'contempo_textdomain'); ?></h2>
    
    <div><label><b><?php _e('Display review title field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_title_check'; ?>" type="checkbox"
      value="<?php echo $title_value; ?>" id="<?php echo 'contempo_title_check'; ?>" 
      <?php checked('1', $title_value); ?>
    </div>
    
    <div><label><b><?php _e('Display phone field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_phone_check'; ?>" type="checkbox"
      value="<?php echo $phone_value; ?>" id="<?php echo 'contempo_phone_check'; ?>" 
      <?php checked('1', $phone_value); ?>
    </div>
    
    <div><label><b><?php _e('Display recommend field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_recommend_check'; ?>" type="checkbox"
      value="<?php echo $recommend_value; ?>" id="<?php echo 'contempo_recommend_check'; ?>" 
      <?php checked('1', $recommend_value); ?>
    </div>
    
    <hr class="review-manager-divider large-divider">
    
    <p><?php _e('Note: Display state for US Reviews.  Any other countries should display country instead.  If chosen, country always overrides state.', 'contempo_textdomain'); ?></p>
     
    <div><label><b><?php _e('Display city field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_city_check'; ?>" type="checkbox"
      value="<?php echo $city_value; ?>" id="<?php echo 'contempo_city_check'; ?>" 
      <?php checked('1', $city_value); ?>
    </div>
   
    <div><label><b><?php _e('Display state field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_state_check'; ?>" type="checkbox"
      value="<?php echo $state_value; ?>" id="<?php echo 'contempo_state_check'; ?>" 
      <?php checked('1', $state_value); ?>
    </div>
    
     <div><label><b><?php _e('Display country field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_country_check'; ?>" type="checkbox"
      value="<?php echo $country_value; ?>" id="<?php echo 'contempo_country_check'; ?>" 
      <?php checked('1', $country_value); ?>
    </div>
    
    <div><label><b><?php _e('Display zip/postal code field: ', 'contempo_textdomain'); ?></b></label><input 
      name="<?php echo 'contempo_zip_check'; ?>" type="checkbox"
      value="<?php echo $zip_value; ?>" id="<?php echo 'contempo_zip_check'; ?>" 
      <?php checked('1', $zip_value); ?>
    </div>
    
    <!-- A note with the shortcode -->
    <p style="clear:both;"></p>
      <hr class="review-manager-divider large-divider" />
      <p><?php _e('Note:', 'contempo_textdomain'); ?><br /><?php _e('To display the review form, copy this shortcode and paste it in the text editor where you want it: ', 'contempo_textdomain'); ?><strong><?php _e('[review_form]', 'contempo_textdomain'); ?></strong></p>
    
  </div> 
  </div>
  </div>
  </div>
  </div>
  </div>
  <!-- End Review-Form ID -->
  </div>
  </div>
  </div>
  </div>
  <?php
}

//save meta options to db
add_action('save_post', 'review_manager_save_extras');

function review_manager_save_extras(){
  global $post;

  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
    return $post_id;
  } else{
			if(isset($_POST['contempo_map_check'])){
				update_post_meta($post->ID, "contempo_map_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_map_check", 0);
			}
			
			if(isset($_POST['contempo_review_check'])){
				update_post_meta($post->ID, "contempo_review_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_review_check", 0);
			}
			
			if(isset($_POST['contempo_phone_check'])){
				update_post_meta($post->ID, "contempo_phone_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_phone_check", 0);
			}
			
			if(isset($_POST['contempo_city_check'])){
				update_post_meta($post->ID, "contempo_city_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_city_check", 0);
			}
			
			if(isset($_POST['contempo_state_check'])){
				update_post_meta($post->ID, "contempo_state_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_state_check", 0);
			}
			
			if(isset($_POST['contempo_country_check'])){
				update_post_meta($post->ID, "contempo_country_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_country_check", 0);
			}
			
			if(isset($_POST['contempo_zip_check'])){
				update_post_meta($post->ID, "contempo_zip_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_zip_check", 0);
			}
			
			if(isset($_POST['contempo_recommend_check'])){
				update_post_meta($post->ID, "contempo_recommend_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_recommend_check", 0);
			}
			
			if(isset($_POST['contempo_title_check'])){
				update_post_meta($post->ID, "contempo_title_check", 1);
			} else{
				update_post_meta($post->ID, "contempo_title_check", 0);
			}
			
			if(isset($_POST['testimonial_value'])){
				update_post_meta($post->ID, "testimonial_value", 1);
			} else{
				update_post_meta($post->ID, "testimonial_value", 0);
			}
			
			if(isset($_POST['testimonial_excerpt_check'])){
				update_post_meta($post->ID, "testimonial_excerpt_check", 1);
			} else{
				update_post_meta($post->ID, "testimonial_excerpt_check", 0);
			}
		
		//save services
		update_post_meta($post->ID, "services_stored_1", $_POST["services_stored_1"]);
		for($i=2;$i<=get_post_meta($post->ID, "hidden_value", true);$i++){
        update_post_meta($post->ID, "services_stored_".$i, $_POST["services_stored_".$i]);
    }
		
		//save ratings and rating images
		update_post_meta($post->ID, "ratings_stored_1", $_POST["ratings_stored_1"]);
		update_post_meta($post->ID, "ratings_images_1", $_POST["ratings_images_1"]);
		for($i=2;$i<=get_post_meta($post->ID, "hidden_value_2", true);$i++){
        update_post_meta($post->ID, "ratings_stored_".$i, $_POST["ratings_stored_".$i]);
				update_post_meta($post->ID, "ratings_images_".$i, $_POST["ratings_images_".$i]);
    }
		
		//hidden counters
		update_post_meta($post->ID, "hidden_value", $_POST["hidden_value"]);
		update_post_meta($post->ID, "hidden_value_2", $_POST["hidden_value_2"]);
		
		//map data
		update_post_meta($post->ID, "contempo_map_center", $_POST["contempo_map_center"]);
		update_post_meta($post->ID, "contempo_map_zoom", $_POST["contempo_map_zoom"]);

		//testimonial data
		update_post_meta($post->ID, "testimonial_city", $_POST["testimonial_city"]);
		update_post_meta($post->ID, "testimonial_service", $_POST["testimonial_service"]);
		update_post_meta($post->ID, "testimonial_image", $_POST["testimonial_image"]);
		update_post_meta($post->ID, "testimonial_pages", $_POST["testimonial_pages"]);
		update_post_meta($post->ID, "company_type", $_POST["company_type"]);
		update_post_meta($post->ID, "company_name", $_POST["company_name"]);
		update_post_meta($post->ID, "company_url", $_POST["company_url"]);
		
		//review data
		update_post_meta($post->ID, "contempo_review_title", $_POST["contempo_review_title"]);
		update_post_meta($post->ID, "contempo_review_description", $_POST["contempo_review_description"]);
		
  }
	
}

?>