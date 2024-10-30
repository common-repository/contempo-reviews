<?php
//declaration of contempo reviews admin page
$ctpo_review_panel = new ContempoOptions(array(
																				'options_group' => 'ctpo_review_options',
																				'page_title' => __('Contempo Reviews', 'contempo_textdomain'), 
																				'menu_title' => __('Contempo Reviews', 'contempo_textdomain'), 
																				'capability' => 'manage_options',
																				'menu_slug' => 'contempo-reviews',
																				));
																				
$ctpo_review_panel->open_ctpo_tab('Testimonials Style');
													
	$ctpo_review_panel->add_ctpo_checkbox('testimonial_classic', __('Use the classic style for testimonials', 'contempo_textdomain'), __('Use Default Style', 'contempo_textdomain'), __('This is the grey gradient used in the earlier versions.', 'contempo_textdomain'));
	$ctpo_review_panel->add_ctpo_color_picker('testimonial_color', __('Testimonial Color', 'contempo_textdomain'), __('Set the background color of your testimonials.', 'contempo_textdomain'));
	$ctpo_review_panel->add_ctpo_checkbox('testimonial_shine', __('Use shine overlay for testimonials','contempo_textdomain'), __('Shine Overlay', 'contempo_textdomain'), __('A nice shine to put over solid colors.', 'contempo_textdomain'));
	$ctpo_review_panel->add_ctpo_typography('ctpo_font_family', __('Testimonial Font Family', 'contempo_textdomain'), __('Choose a font family for your testimonials', 'contempo_textdomain') );
	$ctpo_review_panel->add_ctpo_color_picker('ctpo_font_color',__('Font Color', 'contempo_textdomain'), __('Set the font color of your testimonials.', 'contempo_textdomain'));
	$ctpo_review_panel->add_ctpo_checkbox('testimonial_full_name', __('Use full name for user testimonials.','contempo_textdomain'), __('First or Full Name', 'contempo_textdomain'), __('Decide whether you would like the reviewers full name to be displayed or only the first name.', 'contempo_textdomain'));

$text_shadow_radios = array(
									'light_shadow' => __('Use the light text shadow (better for darker backgrounds)', 'contempo_textdomain'),
									'dark_shadow' => __('Use the dark text shadow (better for lighter backgrounds)', 'contempo_textdomain'),
									'no_shadow' => __('Do not use text shadow', 'contempo_textdomain')
									);

	$ctpo_review_panel->add_ctpo_radio_button('text_shadow', $text_shadow_radios, __('Choose your text shadow type', 'contempo_textdomain'));

	$ctpo_review_panel->close_ctpo_tab(__('Testimonials Style', 'contempo_textdomain'));
	
	$ctpo_review_panel->open_ctpo_tab(__('Rating Options', 'contempo_textdomain'));

$rating_radios = array(
									'use_stars' => __('Use the classic 1-5 star rating system for ratings', 'contempo_textdomain'),
									'use_percents' => __('Use percentage based ratings', 'contempo_textdomain')
									);
	
	$ctpo_review_panel->add_ctpo_radio_button('rating_type', $rating_radios, 'Choose the rating type');
	
$review_type_radios = array(
									'service_reviews' => __('Optimize reviews for services', 'contempo_textdomain'),
									'item_reviews' => __('Optimize reviews for products or items', 'contempo_textdomain'),
									'company_reviews' => __('Optimize reviews for a company or business', 'contempo_textdomain')
									);
	
	$ctpo_review_panel->add_ctpo_radio_button('review_type', $review_type_radios, __('Choose the the review type you will be using', 'contempo_textdomain'));
									
$ctpo_review_panel->close_ctpo_tab(__('Rating Options', 'contempo_textdomain'));
?>