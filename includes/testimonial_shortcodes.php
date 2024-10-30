<?php

add_action( 'comment_form_before', 'my_pre_comment_text' );

function my_pre_comment_text() {
    global $post;
  $before_form = $post->post_content;
    $arr= explode("[review_form]", $before_form, 2);
    if(isset($before_form) && !empty($before_form)):
			$text = $arr[0];
			$pattern = get_shortcode_regex();
			$matches = array();
			preg_match_all('/'.$pattern.'/s', $text, $matches);
    
			foreach($matches as $match){
				$text = str_replace($match, do_shortcode($match), $text);
			}
      echo ' <div style="width:100%;" >'.$text.' </div><p style="clear:both;"> </p>';        
    endif;
}

/*
 * To put [review_form] wherever you want it
 */

add_filter('the_content', 'plugin_ctpo_ContentFilter');

function plugin_ctpo_ContentFilter($content) {
    // Take the existing content and return a subset of it
        global $post;
        $this_content = $post->post_content;
        if (strpos($this_content,'[review_form]') !== false) {
            $arr= explode("[review_form]", $content, 2);
						$content = str_replace($arr[0], "", $content);
            return $content;
        } else{
            return $content;
        }

}

/*~~~~~~~~~~~~~~~~~~~~~*\
[ Review Form Shortcode ]
\*~~~~~~~~~~~~~~~~~~~~~*/

function generate_form() {
	
	
	$custom = get_post_custom($post->ID);
	//var to include/exclude review form
	$featured_form_value = $custom["contempo_review_check"][0];
	
	if($featured_form_value == 1):
	
		$featured_form_title = $custom["contempo_review_title"][0];
		$featured_form_description = $custom["contempo_review_description"][0];
		// ##########  Do not delete these lines
		if (isset($_SERVER['SCRIPT_FILENAME']) && 'review_template.php' == basename($_SERVER['SCRIPT_FILENAME'])){
				die ('Please do not load this page directly. Thanks!'); 
				return;
		}else{
		
		// ##########  End do not delete section
				 
				 // Display Form/Login info Section
				// the comment_form() function handles this and can be used without any paramaters simply as "comment_form()"
				
				$review_form = comment_form(array(
					'comment_notes_before' => '<h2 style="width:100%;">'.$featured_form_title.'</h2>
					'.$featured_form_description.' <hr class="review-style" />
					None of your personal information will be published.  Required fields are marked <span class="required">*</span>',
					'comment_field' => '<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" aria-required="true"></textarea></p>',
					'label_submit' => 'Submit Review',
					'comment_notes_after' => '',
					'title_reply' => ''
				));
				
				$review_form .= '<p sytle="clear:both;"></p>';
				
				comments_template( 'review_template.php' );
				
			return $review_form;
		}
	endif;
	
}
add_shortcode(__('review_form', 'contempo_textdomain'), 'generate_form');


/*~~~~~~~~~~~~~~~~~~~~*\
[ Review Map Shortcode ]
\*~~~~~~~~~~~~~~~~~~~~*/

function generate_map() {
	
	// create review map
	$review_map = '<div id="ctpo-map-container"><div id="contempo_review_map" ></div>';
	
	$args = array(
		
		'meta_key' => 'city_state',
		'status' => 'approve'
		
	);
	
	$custom = get_post_custom($post->ID);
	
	// create review testimonials
	$comments = get_comments($args);
	
	foreach($comments as $comment) {
		
		$review_map .= '<div id="comment-meta-tags"><span class="gmap-area" style="display:none;">' . get_comment_meta( $comment->comment_ID, 'city_state', true ). '</span>';
		
		$comment_exc = substr($comment->comment_content, 0, 50);
		$etc = " [...]"; 
		$comment_exc  = $comment_exc .$etc;
		
		$rating_enum = get_comment_meta( $comment->comment_ID, 'rating_enum', true );
		
		$comment_rating = 0;
		
		for($i=1;$i<=$rating_enum;$i++):
			$comment_rating = $comment_rating + intval(get_comment_meta( $comment->comment_ID, 'rating_'.$i, true ));
		endfor;
		
		$review_map .= '<span class="gmap-rating" style="display:none;">' . round($comment_rating/$rating_enum, 0) . '</span>';
		
		$comment_date = substr($comment->comment_date, 0, 10);
		
		$review_map .= '<span class="gmap-date" style="display:none;">' . $comment_date . '</span>';
		
		$review_map .= '<span class="gmap-excerpt" style="display:none;">' . $comment_exc . '</span></div>';
		
	}
	
	$review_map .= '</div><p style="clear:both"></p>';
	
	return $review_map;
	
}
add_shortcode(__('review_map', 'contempo_textdomain'), 'generate_map');

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*\
[ Review Testimonial Shortcode ]
\*~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

function generate_testimonials() {
	
	$custom = get_post_custom($post->ID);
	
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
			
			'status' => 'approve'
			
		);
		
	if(isset( $formatted_city )):
		$args = array(
			
			'meta_value' => $formatted_city,
			'status' => 'approve'
			
		);
	endif;
	
	if(isset( $formatted_service )):
		$args = array(
			
			'meta_value' => $formatted_service,
			'status' => 'approve'
			
		);
	endif;
	
	if(isset( $formatted_city ) && isset( $formatted_service )):
		$formatted_city_service = $formatted_city . '_' . $formatted_service;
		
		$args = array(
			
			'meta_value' => $formatted_city_service,
			'status' => 'approve'
			
		);
	endif;
		
	if($testimonial_value == 1):
	
		$review_count = 0;
		$aggregate_rating = 0;
		
		$rating_array = array();

		$comments = get_comments($args);
		
		foreach($comments as $comment) {
			
			$rating_enum = 0;
			$rating_enum = get_comment_meta( $comment->comment_ID, 'rating_enum', true );
			
			if(isset($rating_enum) && $rating_enum != 0):
			
				$rative_value = 0;
				
				//Current rating scale is 1 to 5.
				$rating_enum = get_comment_meta( $comment->comment_ID, 'rating_enum', true );
				$agg_rating = 0;
				$rating_field = '';
				
				for($i=1; $i<=$rating_enum; $i++):
					$rating_value = intval(get_comment_meta( $comment->comment_ID, 'rating_'.$i, true ));
					
					
					$agg_rating = $agg_rating + $rating_value;
					
				endfor;
				
				$agg_rating = round($agg_rating/$rating_enum, 0);
				
				$rating_value = $rating_value/$rating_enum;
				array_push($rating_array, round($rating_value, 0));
				
				$aggregate_rating = $aggregate_rating + $agg_rating;
				
				$review_count++;
				
			endif;
			
		}
		
		if($review_count != 0):
		
			$options = get_option('ctpo_review_options');
			
			if(!isset($options['rating_type']) || $options['rating_type'] == 'use_stars'):
			
				$aggregate_rating = round($aggregate_rating/$review_count, 1);
				
				$review_testimonials = '<blockquote class="rectangle"><a name="review-anchor"/><div class="radial-effect"><div class="text-above"><p>
				<span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate"> 
					<strong><a href="'.$company_url.'"><span property="v:itemreviewed">'.$company_name.'</span></a></strong>
					<span rel="v:rating"> 
						<span typeof="v:Rating">      
							'.__('is rated ', 'contempo_textdomain') .'
								<span property="v:average">'. $aggregate_rating .'</span> '.__('/', 'contempo_textdomain') .'
								<span property="v:best">'.__('5', 'contempo_textdomain') .'</span> 
							</span>
				 		</span>
						'.__('based on these ', 'contempo_textdomain') .'
				 		<span property="v:count">'. $review_count .' </span>
				'.__('happy customer reviews', 'contempo_textdomain') .'
					</span> 
				</p></div></div></blockquote><hr class="ctpo-break" /><div id="contempo-testimonial-wrapper" >';
								
			endif;
			
			if($options['rating_type'] == 'use_percents'):
			
				$percentage_rating = round((round($aggregate_rating/$review_count, 1)/5)*100,2);
				
				$review_testimonials = '<blockquote class="rectangle"><a name="review-anchor"></a><div class="radial-effect"><div class="text-above">
				<p><span xmlns:v="http://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate"> 
				<strong><a href="'.$company_url.'"><span property="v:itemreviewed">'. $company_name .' </span></a></strong>
				'.__('is rated ', 'contempo_textdomain') .'
				<span rel="v:rating">
					<span typeof="v:Rating"> 
						<span property="v:average">'. $percentage_rating .'%</span>
					</span>
				</span>
				<br />
				'.__('based on these ', 'contempo_textdomain') .'
				<span property="v:count">'. $review_count .' </span>
				'.__('happy customer reviews', 'contempo_textdomain') .'
				</span></p></div></div></blockquote><hr class="ctpo-break" /><div id="contempo-testimonial-wrapper" >';
				
			endif;
			
		else:
			$review_testimonials = '<blockquote class="rectangle"><div class="radial-effect"><div class="text-above"><p itemprop="aggregateRating" itemscope
			itemtype="http://schema.org/AggregateRating"><span itemprop="itemReviewed" itemscope
			itemtype="http://schema.org/'. $company_type .'"> '.$company_name . __(' appreciates your feedback.  Please give us more time to collect your reviews and they will be displayed below.', 'contempo_textdomain') .'
			</p></div></div></blockquote><hr class="ctpo-break" /><div id="contempo-testimonial-wrapper" >';
		endif;
			
		$comment_index = 0;
		$comments = get_comments($args);
		
		foreach($comments as $comment) {
			
		//for pagination TEST
		if($comment_index < $testimonial_pages):
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
			
			if(!isset($options['testimonial_full_name'])):
				$name_arr = explode(' ',trim(ucfirst($review_author)));
				$display_name = $name_arr[0];
			else:
				$display_name = $review_author;
			endif;
			
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
			
			(isset($review_city) && $review_city != '') || (isset($review_state) && $review_state != '') ?
				$pipe = "|" :
				$pipe = '';
				
			$review_testimonials .= $comment_full . '<div class="ctpo-list-wrappers ctpo-list-'.$comment_index.'"><blockquote class="testimonial" itemprop="reviews" itemscope itemtype="http://schema.org/Review"><div class="ctpo-effect"><div class="text-above">' . $review_title
  														  	. $testimonial_image . '<p itemprop="reviewBody"> ' . __($comment_exc) . ' </p><br />
																	<p style="clear:both;"></p>' . $rating_field . '<br />
															</div></div></blockquote>
															<div class="arrow-down"></div>
															<p style="clear:both"></p>
															<p class="testimonial-author">
															<span itemprop="author" itemscope itemtype="http://schema.org/Person">
																<span itemprop="name">' . $display_name . '</span> '.$pipe.' 
																<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
																	<span class="contempo-city" itemprop="addressLocality">' . ucfirst($review_city) . '</span>
																	<span class="contempo-city" itemprop="addressRegion">' . $review_state . '</span>
																	
																<span> 
															</p>
															</span></div><p style="clear:both">&nbsp;</p>';

			$comment_index++;
		
		endif; //end the first page paginated testimonials TEST
		}
		
		endif; //end display testimonial if
	
	$review_testimonials .= '</div>';
	
	$pages = ceil(count($comments)/$testimonial_pages);
	
	//create pagination
	$pagination = '';
	if($pages > 1)
	{
			$pagination .= '<p style="clear:both;"></p><hr class="ctpo-break" /><ul class="paginate">';
			for($i = 1; $i<=$pages; $i++)
			{
					$pagination .= '<div class="radial-effect"><span class="text-above"><li class="ctpo-paginated"><a href="#" class="paginate_click" id="'.$i.'-page">'.$i.'</a></li></span></div>';
			}
			$pagination .= '</ul><div id="pagination-page" style="display:none;">'.get_the_ID().'</div>';
	}
	
	$review_testimonials .= $pagination;
	
	return $review_testimonials;
	
	
}
add_shortcode(__('review_testimonials', 'contempo_textdomain'), 'generate_testimonials');

?>