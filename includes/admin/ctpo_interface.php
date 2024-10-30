<?php
// Check the interface exists before trying to use it
if (!interface_exists('ctpoAdmin')) :

	// Declare the Contempo interface
	interface ctpoAdmin
	{
			//add admin page and dynamic css
			public function add_ctpo_admin_page();
			public function generate_ctpo_options_css($data);
			public function add_ctpo_google_client();
			//add tabs
			public function open_ctpo_tab($tab_name, $sub_id='');
			public function close_ctpo_tab($tab_name='', $sub_tab='');
			public function open_sub_tab_group($sub_tabs = array());
			public function close_sub_tab_group($sub_id='');
			//add inputs
			public function add_ctpo_text_input($name, $label='', $note='', $default='');
			public function add_ctpo_checkbox($name, $descrip, $label='', $note='', $default='');
			public function add_ctpo_radio_button($name, $inputs=array(), $label='', $note='', $default='');
			public function add_ctpo_typography($name, $label='', $note='', $default='');
			public function add_ctpo_color_picker($name, $label='', $note='', $default='');
			public function add_ctpo_single_media($name, $label='', $note='');
			
	}
	
	// Implement the admin interface
	
	class ContempoOptions implements ctpoAdmin
	{
			public $admin_page_vars = array();
			public $admin_page_params = array();
			public $options_group_id = 'generic_options';
			public $ctpo_options = array();
			public $ctpo_plugin_url = WP_PLUGIN_URL;
			
			//all input elements are stored in this array
			public $ctpo_tabs_and_inputs = array();
			
			//to open and close the function output between tabs and sub-tabs
			public $tabs_named = array();
			public $sub_tabs_named = array();
			public $sub_group_id = '';
			
		
			public function __construct($admin_page_vars)  
			{  
			
				add_action('admin_menu', array($this, 'add_ctpo_admin_page'));
				add_action( 'admin_init', array($this, 'add_ctpo_admin_scripts') );
				add_action('admin_head', array($this, 'add_ctpo_header_css'));
				add_action('admin_head', array($this, 'ctpo_add_ajax_options'));
				add_action('admin_footer', array($this, 'add_ctpo_google_client'));
	
				add_action('wp_ajax_ctpo_main_options_save', array($this, 'ctpo_main_options_save_ajax') );
				
				$this->admin_page_params = $admin_page_vars;
				$this->options_group_id = $admin_page_vars['options_group'];
				$this->ctpo_options = get_option($this->options_group_id);
				
				$this->admin_page_vars = array( 
										'page_title' => 'Contempo Options Page', 
										'menu_title' => 'Contempo Menu', 
										'capability' => 'manage_options',
										'menu_slug' => 'contempo-menu',
										'function' => array($this, 'contempo_options_gui') );
					
				//merge array with defaults and extract indecies to vars
				$this->admin_page_params = array_merge($this->admin_page_vars, $this->admin_page_params);
			}
			
			public function add_ctpo_google_client() {
				
					?>
          <script>
					var your_api_key = 'AIzaSyCzqkk9eaAZnA4_xt5AxIb_2PGyDC__WQk';
					jQuery(window).load(function(){
						jQuery(".styleFont").change(function() {
								var font_name = jQuery('.styleFont option:selected').text();
								jQuery('<link/>', {
										href: 'http://fonts.googleapis.com/css?family=' + encodeURI(font_name),
										rel: 'stylesheet',
										type: 'text/css'
								}).appendTo('head');
								jQuery('.font-preview').css('font-family', font_name);
								jQuery('.font-preview').css('font-size', '1.7em');

								jQuery('.font-preview').animate({backgroundColor: '#FF0000'}, 'slow', function(){
									jQuery(this).text('Grumpy wizards make toxic brew for the evil Queen and Jack.');
									jQuery(this).animate({backgroundColor: '#FFFFFF'}, 'slow');
									jQuery(this).animate({color: '#000000'}, 'slow');
								});
						});
						
						gapi.client.load('webfonts', 'v1', function() {
								var request = gapi.client.webfonts.webfonts.list({
										key: your_api_key
								});
								request.execute(function(response) {
										response.items.forEach(function(font, id) {
												console.log(font.family);
												jQuery('<option/>', {
														value: encodeURI(font.family),
														text: font.family
												}).appendTo('.styleFont');
										});
										console.log(response);
								});
								console.log('loaded.');
						
						});
					});
					</script>
          
          <?php
					
			}
			
			public function generate_ctpo_options_css($data) {
				
				isset($data['ctpo_font_family']) ? $decoded_font = urldecode($data['ctpo_font_family']) : 0;
				if(isset($data['text_shadow'])){
					$data['text_shadow'] == 'light_shadow' ? $text_shadow = 'text-shadow: 0 1px 0 #ECFBFF;' : 0;
					$data['text_shadow'] == 'dark_shadow' ? $text_shadow = 'text-shadow: 0 1px 0 #333333;' : 0;
					$data['text_shadow'] == 'no_shadow' ? $text_shadow = '' : 0;
				} else{
					$text_shadow = '';
				}
				if(isset($data['testimonial_shine']) && $data['testimonial_shine'] == 'testimonial_shine'){
					$shine_effect = "div.ctpo-effect:after { content:'';position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;background-image: url(images/shine-effect.png);background-repeat: no-repeat;background-position: center top;}
div.radial-effect:after { content:''; position:absolute; top:0; left:0; width:100%; height:100%; z-index:1; background-image: url(images/radial-effect.png); background-repeat: no-repeat; background-position: center top; }\r\n
span.small-radial:after { content:''; position:absolute; top:0; left:0; width:100%; height:100%; z-index:1; background-image: url(images/small-radial.png); background-repeat: no-repeat; background-position: center top; }\r\n
div.small-effect:after { content:''; position:absolute; top:0; left:0; width:100%; height:100%; z-index:1; background-image: url(images/small-shine.png); background-repeat: no-repeat; background-position: center top; }\r\n
";
				} else{
					$shine_effect = "";
				}
				
				$css_dir = dirname(__FILE__). '/css/'; // Shorten code, save 1 call
				ob_start(); // Capture all output (output buffering)
			
				require('styles.php'); // Generate CSS
			
				$css = ob_get_clean(); // Get generated CSS (output buffering)
				file_put_contents($css_dir . 'ctpo_client_style.css', $css, LOCK_EX); // Save it
			}		
			
		/****************************************************************
		& Open and Close Tabs
		&
		*****************************************************************/
		 
		/*
		 * Open a tab or subtab
		 */
		 
			public function open_ctpo_tab($tab_name, $sub_id='')
			{
					if($sub_id == ''):
						//push the beginning tab opening div into the array when it is called
						array_push($this->ctpo_tabs_and_inputs, '<div id="tabs-'.(count($this->tabs_named)+1).'"><h2 class="tab-title">'.$tab_name.'</h2><hr />');
						//stores how many times this function has been called
						array_push($this->tabs_named, $tab_name);
					else:
						$sub_index = str_replace('-', '_', sanitize_title_with_dashes($sub_id));
						if(!isset($this->ctpo_tabs_and_inputs[$sub_index])):
							$this->ctpo_tabs_and_inputs[$sub_index] = array();
							array_push($this->ctpo_tabs_and_inputs[$sub_index], $tab_name);
							array_push($this->ctpo_tabs_and_inputs, '<div id="'.$sub_id.'-'.count($this->ctpo_tabs_and_inputs[$sub_index]).'"><h2 class="tab-title">'.$tab_name.'</h2><hr />');
						else:
							array_push($this->ctpo_tabs_and_inputs[$sub_index], $tab_name);
							array_push($this->ctpo_tabs_and_inputs, '<div id="'.$sub_id.'-'.count($this->ctpo_tabs_and_inputs[$sub_index]).'"><h2 class="tab-title">'.$tab_name.'</h2><hr />');
						endif;
					endif;
			}
		/*
		 * Open a sub-tab group (display section for subtabs)
		 */
		 
			public function open_sub_tab_group($sub_tabs = array())
			{
						foreach($sub_tabs as $sub_tab){
							if(!is_array($sub_tab)):
								array_push($this->ctpo_tabs_and_inputs,  '<script>
																														jQuery(function() {
																															jQuery("#'.$sub_tab.'").tabs();
																														});
																													</script>
																												<div id="'.$sub_tab.'">
																													<ul>');
							else:
								$sub_count = 1;
								foreach($sub_tab as $tab){
									array_push($this->ctpo_tabs_and_inputs, '<li><a href="#'.$sub_tabs['sub_id'].'-'.$sub_count.'">'.$tab.'</a></li>');
									$sub_count++;
								}
									array_push($this->ctpo_tabs_and_inputs, '</ul>');
							endif;
						
						}
			}
		
		/*
		 * Close a subtab group
		 */
		 
			public function close_sub_tab_group($sub_id='')
			{
						//closing div for each tab
							array_push($this->ctpo_tabs_and_inputs, '</div>');
			}
			
		/*
		 * Close a tab
		 */
		 
			public function close_ctpo_tab($tab_name='', $sub_tab='')
			{
						//closing div for each tab
							array_push($this->ctpo_tabs_and_inputs, '</div>');
			}
		
		/****************************************************************
		& Add input fields
		&
		*****************************************************************/
		
		/*
		 * Show the text input field
		 */
		 
			public function add_ctpo_text_input($name, $label='', $note='', $default='')
			{
					!empty($note) ? $note = '<div class="ctpo-note">'.$note.'</div>' : 0;
					
					$text_input = '<div class="ctpo-input">
													<label for="'.$name.'">'.$label.'</label><br />
													<input name="'.$name.'" id="'.$name.'" value="'.$this->ctpo_options[$name].'" type="text" /><br />
													'.$note.'
												</div>';
					array_push($this->ctpo_tabs_and_inputs, $text_input);
			}
	
		/*
		 * Show the checkbox input field
		 */
		 
			public function add_ctpo_checkbox($name, $descrip, $label='', $note='', $default='')
			{
					!empty($note) ? $note = '<div class="ctpo-note">'.$note.'</div>' : 0;
					$this->ctpo_options[$name] == 1 ? $checked = 'checked="checked"' : $checked = '';
					$checkbox_input = '<div class="ctpo-input">
														<label for="'.$name.'">'.$label.'</label><br />
														<input type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$name.'" '.checked($this->ctpo_options[$name], $name, false).' ><span class="check-text"> '.$descrip.'</span><br />
														'.$note.'
														</div>';
					array_push($this->ctpo_tabs_and_inputs, $checkbox_input);
			}
			
		/*
		 * Show the radio button input field
		 */
		 
			public function add_ctpo_radio_button($name, $inputs=array(), $label='', $note='', $default='')
			{
					$radios = '';
					foreach($inputs as $val=>$input){
						$radios .= '<input type="radio" name="'.$name.'" value="'.$val.'" '.checked($this->ctpo_options[$name], $val, false).' ><span class="check-text">'.$input."</span><br /><br />\r\n";
					}
					!empty($note) ? $note = '<div class="ctpo-note">'.$note.'</div>' : 0;
					$checkbox_input = '<div class="ctpo-input">
														<label for="'.$name.'">'.$label.'</label><br />
														'.$radios.'<br />
														'.$note.'
														</div>';
					array_push($this->ctpo_tabs_and_inputs, $checkbox_input);
			}


		/*
		 * Show the selectbox input field
		 */
		 
			public function add_ctpo_typography($name, $label='', $note='', $default='')
			{
					
						
					!empty($note) ? $note = '<div class="ctpo-note font-preview">'.$note.'</div>' : 0;
					$checkbox_input = '<div class="ctpo-input">
														<label for="'.$name.'">'.$label.'</label><br />
														<select class="ctpoCustomSelect styleFont" name="'.$name.'" id="'.$name.'" >
														<option value="'.$this->ctpo_options[$name].'">'.urldecode($this->ctpo_options[$name]).'</option>
														</select>
														<br />
														'.$note.'
														</div>';
					array_push($this->ctpo_tabs_and_inputs, $checkbox_input);
			}
			
		/*
		 * Show the color picker input field
		 */
		 
			public function add_ctpo_color_picker($name, $label='', $note='', $default='')
			{
					!empty($default) ? $default = 'data-default-color="#'.$default.'"' : 0;
					!empty($note) ? $note = '<div class="ctpo-note">'.$note.'</div>' : 0;
					$color_input = '<div class="ctpo-input">
														<label for="'.$name.'">'.$label.'</label><br />
														<input name="'.$name.'" id="'.$name.'" class="ctpo-color-field" value="'.$this->ctpo_options[$name].'"'.$default.' /><br />
														'.$note.'
														<div class="ctpo-colorpicker"></div></div>';
					array_push($this->ctpo_tabs_and_inputs, $color_input);
			}
	
		
	
		/*
		 * Show the single upload input field
		 */
		 
			public function add_ctpo_single_media($name, $label='', $note='')
			{
					!empty($note) ? $note = '<div class="ctpo-note">'.$note.'</div>' : 0;
					
					isset($this->ctpo_options[$name]) && !empty($this->ctpo_options[$name]) ? 
					$img = '<img src="'.$this->options[$name].'" class="ctpo-img-preview" />' : 
					$img='';
					
					$media_input = '<div class="ctpo-input">
														<label for="'.$name.'">'.$label.'</label><br />
														<div id="img-'.$name.'">'.$img.'</div>
														<input name="'.$name.'" id="'.$name.'" class="url-holder" value="'.$this->ctpo_options[$name].'" type="text" style="display:none;" />
														<input class="upload_image_button" type="button" value="Upload Media"><br />
														'.$note.'
														</div>';
					array_push($this->ctpo_tabs_and_inputs, $media_input);
			}
	
		/*
		 * Add the menu item
		 */
		 
			public function add_ctpo_admin_page()
			{
					//check for collisions in params and skip if found
					extract($this->admin_page_params, EXTR_SKIP);
					
					//add the actual menu page
					add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function );
			}
			
			public function add_ctpo_header_css()
			{
				
				
				echo '<style>
								.ui-tabs-vertical { width: 55em; }
								.ui-tabs-vertical > .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
								.ui-tabs-vertical > .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
								.ui-tabs-vertical > .ui-tabs-nav li a { display:block; }
								.ui-tabs-vertical > .ui-tabs-nav li.ui-tabs-selected { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
								.ui-tabs-vertical > .ui-tabs-panel { padding: 1em; float: right; width: 40em;}
								
								p { font-size: 10pt; }
								h1 { font-weight: bold; }
							</style>';
			}
			
		/*
		 * Add the admin styles
		 */
		 
			public function add_ctpo_admin_scripts()
			{
				//check for collisions in params and skip if found
				extract($this->admin_page_params, EXTR_SKIP);
				
				//add scripts & styles if on the correct page
				if( is_admin() && ( $_GET['page'] == $menu_slug ) ):
					//Access the global $wp_version variable to see which version of WordPress is installed.
					global $wp_version;
					//jquery and other jquery-ui scripts
					wp_enqueue_script( 'jquery');
					wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-widget' );
					wp_enqueue_script( 'jquery-ui-mouse' );
					wp_enqueue_script( 'jquery-ui-tabs' );
					wp_enqueue_script( 'jquery-ui-form' );
					if(function_exists('wp_enqueue_media'))
						wp_enqueue_media();
					//If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
					if ( 3.5 <= $wp_version ){
						//Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
						wp_enqueue_style( 'wp-color-picker' );
						wp_enqueue_script( 'wp-color-picker' );
					}
					//If the WordPress version is less than 3.5 load the older farbtasic color picker.
					else {
						//As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
						wp_enqueue_style( 'farbtastic' );
						wp_enqueue_script( 'farbtastic' );
					}
					
					//Load custom selectbox script
					wp_enqueue_script(
						'ctpo-selectbox',
						plugins_url('', dirname(__FILE__)). '/admin/js/customSelect/jquery.customSelect.min.js',
						array('jquery'),
						false,
						true
					);
					
					//load iCheck script for checks and radio buttons
					wp_enqueue_script(
						'ctpo-i-check',
						plugins_url('', dirname(__FILE__)). '/admin/js/iCheck/jquery.icheck.min.js',
						array('jquery'),
						false,
						true
					);

					//load google client api for web fonts
					wp_register_script(
						'google-client',
						'https://apis.google.com/js/client.js?onload=handleClientLoad',
						array('jquery')
					);
				
				wp_enqueue_script('google-client');
					
					wp_register_script(
							'ctpo-admin',
							plugins_url('', dirname(__FILE__)). '/admin/js/ctpo_admin_panel.js',
							array('google-client'),
							null,
							true
						);

					//Load ctpo admin script
					wp_enqueue_script('ctpo-admin');	
									
					/* Trying to keep everything in the custom jquery-ui css, but may add this back for organizational reasons			
					//custom and jquery-ui styles
					wp_enqueue_style('ctpo_admin_style',
					plugins_url('', dirname(__FILE__)). '/admin/css/ctpo_admin_style.css'
					);
					*/
					
					//custom and jquery-ui styles
					wp_enqueue_style('ctpo_jquery_ui_style',
					plugins_url('', dirname(__FILE__)). '/admin/css/jquery-ui-1.10.3.custom.css'
					);
					
					//icheck styles
					wp_enqueue_style('ctpo_icheck_style',
					plugins_url('', dirname(__FILE__)). '/admin/js/iCheck/skins/square/grey.css'
					);
					
				endif;
				
			}
			
		/*
		 * Construct the gui to put on the page
		 */
		 
			public function contempo_options_gui()
			{
				
				//check for collisions in params and skip if found
				extract($this->admin_page_params, EXTR_SKIP);
			
				echo '<h1>'.$page_title.'</h1>
						<form action="options.php" id="main-options-form" name="main-options-form">
							<div id="tabs">';
							
				if(!empty($this->ctpo_tabs_and_inputs)):
					$options = get_option($this->options_group_id);
	
					echo '<input type="hidden" name="action" value="ctpo_main_options_save" />
					<input type="hidden" name="security" value="'.wp_create_nonce("ctpo-insanely-difficult-to-guess-nonce").'" />';
					echo '<div class="admin-head-banner">
					<input type="submit" value="Save Changes" /><div id="ajax-loader"><img src="'.plugins_url('', dirname(__FILE__)). '/admin/images/admin-ajax-loader.gif'.'" /></div></div>';
					echo '<ul>';
					$tab_counter = 1;
						foreach($this->tabs_named as $tab_name){
							
							echo '<li><a href="#tabs-'.$tab_counter.'">'.$tab_name.'</a></li>';
							$tab_counter++;
						}
					echo '</ul>';
					//print inputs in their respective tabs
					foreach($this->ctpo_tabs_and_inputs as $ind_input){
							//make sure the individual input is not an array and print
							!is_array($ind_input) ? print_r($ind_input) : 0;
					}
				
					
					
				endif;
				
				echo '</div></form>';
			
			}
			
			public function ctpo_add_ajax_options() {
				?>
					
				<script type="text/javascript">
					jQuery(document).ready(function($) {
					
					$('#ajax-loader').hide().ajaxStart(function(){
							$('#ajax-loader img').attr('src', '<?php echo plugins_url('', dirname(__FILE__)). '/admin/images/admin-ajax-loader.gif'; ?>');
							$(this).fadeIn();
						}).ajaxStop(function() {
							$(this).fadeOut(3000);
					});
	
					jQuery('form#main-options-form').submit(function() {
						var data = jQuery(this).serialize();
						
						jQuery.post(ajaxurl, data, function(response) {
							if(response == 1) {
								show_message(1);
							} else {
								show_message(2);
							}
						});
						return false;
					});
					
				});
				
				function show_message(n) {
					if(n == 1) {
						jQuery('#ajax-loader img').attr('src', '<?php echo plugins_url('', dirname(__FILE__)). '/admin/images/ajax-saved.png'; ?>');
					} else {
						jQuery('#saved').html('<div id="message" class="error fade"><p><strong><?php _e('Options could not be saved.'); ?></strong></p></div>').show();
					}
				}
				
				function fade_message() {
					jQuery('#saved').fadeOut(1000);	
					clearTimeout(t);
				}
			</script>
				
				<?php    
				}
			
			 public function ctpo_main_options_save_ajax() { 
	
					check_ajax_referer('ctpo-insanely-difficult-to-guess-nonce', 'security' );
	
					$data = $_POST;
					unset($data['security'], $data['action']);
					
					if(!is_array(get_option($this->options_group_id))) {
						$options = array();
					} else {
						$options = get_option($this->options_group_id);
					}
				
					if(!empty($data)) {
						$diff = array_diff($options, $data);
						$this->generate_ctpo_options_css($data);
						$diff2 = array_diff($data, $options);
						$diff = array_merge($diff, $diff2);
					} else {
						$diff = array();
					}
					
					if(!empty($diff)) {	
						if(update_option($this->options_group_id, $data)) {
							die('1');
						} else {
							die('0');
						}
					} else {
						die('1');	
					}
					
			 }
	}
endif; //end check for existing interface
?>
