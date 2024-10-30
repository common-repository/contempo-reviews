<?php

class contempo_reviews_widget extends WP_Widget {
  function contempo_reviews_widget() {
		$widget_options = array(
    'classname' => 'contempo_reviews',
    'description' => __('Displays Your Latest, Rated Testimonials', 'contempo_textdomain') );
    parent::WP_Widget("latest_project", __("Contempo Testimonials", "contempo_textdomain"), $widget_options);
		add_action('wp_enqueue_scripts', array(&$this, 'ctpo_widget_js'));
  }
	
	public function ctpo_widget_js(){
		
		if(is_active_widget( false, false, $this->id_base, true )):
		
			$plugin_url_path = WP_PLUGIN_URL;
			$options = get_option('ctpo_review_options');
			
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
			
			//Load rating style
			wp_enqueue_style('rating_style',
				$plugin_url_path . '/ctpo-testimonials/js/star-rating/jquery.rating.css'
			);
		
			//Load colorbox style
			wp_enqueue_style('colorbox_style',
				$plugin_url_path . '/ctpo-testimonials/css/colorbox.css'
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
			
				
			//Load ColorBox script
			wp_enqueue_script(
				'color-Box',
				$plugin_url_path . '/ctpo-testimonials/js/jquery.colorbox-min.js',
				array('rating'),
				false,
				true
			);
			
			//Load ctpo widget script
				wp_enqueue_script(
					'ctpo-widget',
					$plugin_url_path . '/ctpo-testimonials/js/jquery.ctpowidget.js',
					array('color-Box')
				);
			
			endif;
	}
 
  public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'thumbnail_url' => '', 'url' => '' ) );
		$title = $instance['title'];
		$select = $instance['select'];
	 
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'contempo_textdomain'); ?></label><br />
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo   $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title ?>"   />
		</p>
		
    <p>

      <label for="<?php echo $this->get_field_id('select'); ?>"><?php _e('Display how many testimonials?', 'contempo_textdomain'); ?></label>
  
      <select name="<?php echo $this->get_field_name('select'); ?>" id="<?php echo $this->get_field_id('select'); ?>" class="widefat">
  
          <?php
  
          $options = array('1', '2', '3', '4', '5');
  
          foreach ($options as $option) {
  
              echo '<option value="' . $option . '" id="' . $option . '"', $select == $option ? ' selected="selected"' : '', '>', $option, '</option>';
  
          }
  
          ?>
  
      </select>

    </p>

    
		<?php
  }
 
  public function update( $new_instance, $old_instance ) {
		$instance = array(); 
		$instance['title'] = $new_instance['title'];
		$instance['select'] = $new_instance['select'];

		return $instance;
	}
	 
		public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$t_number = $instance['select'];
		
		$args = array(
								'number' => $t_number,
								'meta_key' => 'city_state',
								'status' => 'approve'
							);
	 
		echo $before_widget;
		echo '<h3>'.$title.'</h3>';
		echo '<div id="ctpo-widget" style="position:relative; display:block; clear:both;">';
		//display comments
		$comments = get_comments($args);
		
		if(isset($comments) && !empty($comments)):
			$comment_index = 1;
			
			
			foreach($comments as $comment) {
				$rating_field = '';
				if(strlen($comment->comment_content) > 75){
					$comment_exc = '<div style="display:none">
				<div id="inline_content_'.$comment_index.'" style="padding:10px; background:#fff;">
				<p style="line-height:1.5em;">'.$comment->comment_content.'</p>
				</div>
				</div>';
					$comment_exc .= substr($comment->comment_content, 0, 75);
					$etc = " <a class='ctpo-inline' href='#inline_content_".$comment_index."'>[...]</a>"; 
					$comment_exc  = $comment_exc .$etc;
				}else{
					$comment_exc = $comment->comment_content;
				}
				
				/*************************
				Begin to calculate ratings
				**************************/
				//Current rating scale is 1 to 5.
				$rating_enum = get_comment_meta( $comment->comment_ID, 'rating_enum', true );
				
				
				if(isset($rating_enum) && $rating_enum != 0):
					$agg_rating = 0;
					
					for($i=1; $i<=$rating_enum; $i++):
						$rating_value = intval(get_comment_meta( $comment->comment_ID, 'rating_'.$i, true ));
						$rating_cat = get_comment_meta( $comment->comment_ID, 'ratings_stored_'.$i, true );
						
						$agg_rating = $agg_rating + $rating_value;
						
					endfor;
					
					$agg_rating = round($agg_rating/$rating_enum, 0);
						
					$rating_field .= '<p>Rating: '.$agg_rating. __('/5', 'contempo_textdomain').'</p>';
						
					for( $i=1; $i <= 5; $i++ ):
						
						$i == $agg_rating ? $checked='checked="checked"' : $checked='';
						
						$rating_field .= '<input class="star" disabled="disabled" type="radio" 
																name="rating_widget_overall_'.$comment_index.'" value="'.$i.'" ' . $checked . '>';
					endfor;
					
					$rating_field .= '<p style="clear:both;">';
				
				endif;
				
				/********************
				End Calculate Ratings
				*********************/
				
				//display the widgetized testimonial with js..
				echo '<blockquote class="testimonial ctpo-widget" style="min-height:100px;"><div class="small-effect"><div class="text-above"><p>' . $comment_exc . '	</p><p> '. '<span style="position:absolute; bottom:7px;">'.$rating_field . 
				'</span></p></div></div></blockquote>';
				
				$comment_index++;
			}
			
			
			endif;
			
			echo '</div><br clear="all" /><div style="width:100px; height:100px; clear:both;"></div>';
			echo $after_widget;
		}

	
}



add_action( 'widgets_init', create_function( '', 'register_widget("contempo_reviews_widget");'));
?>