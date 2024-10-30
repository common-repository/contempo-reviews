/*----------------------  Testimonianls --------------------------*/
li.ctpo-paginated{
	float:left !important;
	list-style-type:none !important;
	background-image:none !important;
	margin:5px !important;
	padding:0 !important;
}
div#contempo-testimonial-wrapper{
	width:100% !important;
}
div.ctpo-list-wrappers{
float:none !important;
}

li.ctpo-paginated a{
	text-align:center;
	text-decoration:none !important;
	font-family: <?php isset($decoded_font) && !empty($decoded_font) ? print_r($decoded_font.";\r\n"): print_r("Georgia, serif;\r\n");?>
	display:block;
	float:left;
	margin: 2px 2px 2px 0;
	padding:6px 9px 5px 9px;
	text-decoration:none;
	width:15px;
	margin-left:auto;
	margin-right:auto;
	color: <?php isset($data['ctpo_font_color']) && !empty($data['ctpo_font_color']) ? print_r($data['ctpo_font_color'].";\r\n"): print_r("#666;\r\n");?>;
	background: <?php isset($data['testimonial_color']) && !empty($data['testimonial_color']) ? print_r($data['testimonial_color'].";\r\n"): print_r("#fff;\r\n");?>	border-radius: 5px;
	border:1px solid #d4d4d4;
  width:18px;
}
li.ctpo-paginated a:hover{
	color: <?php isset($data['ctpo_font_color']) && !empty($data['ctpo_font_color']) ? print_r($data['ctpo_font_color'].";\r\n"): print_r("#666;\r\n");?>;
	<?php print_r($text_shadow."\r\n"); ?>
	background: <?php isset($data['testimonial_color']) && !empty($data['testimonial_color']) ? print_r($data['testimonial_color'].";\r\n"): print_r("#fff;\r\n");?>	border-radius: 5px;
	opacity: .7;
}
a.paginate_click{
	width:30px;
}
span.ctpo-review-title{
	font-size:1.5em;
	width:100%;
	clear:both;
}

p.testimonial-author{
	font-family: <?php isset($decoded_font) && !empty($decoded_font) ? print_r($decoded_font.";\r\n"): print_r("Georgia, serif;\r\n");?>
	color: #666;
	<?php print_r($text_shadow."\r\n"); ?>
	margin: 0 !important;
}

div#ctpo-widget blockquote.testimonial {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	max-width: 150px;
}
p.star-rating-readonly {
	margin: 0 !important;
}
p.rating-cat {
	margin-top: 2px !important;
}
hr.ctpo-break {
	margin: 10px 0 !important;
	padding: 0 !important;
}
.rectangle {
	margin: 0;
	background: #B7EDFF;
	padding: 10px 50px !important;
	position: relative;
	font-family: <?php isset($decoded_font) && !empty($decoded_font) ? print_r($decoded_font.";\r\n"): print_r("Georgia, serif;\r\n");?>
	color: <?php isset($data['ctpo_font_color']) && !empty($data['ctpo_font_color']) ? print_r($data['ctpo_font_color'].";\r\n"): print_r("#666;\r\n");?>;
	border-radius: 5px;
	<?php print_r($text_shadow."\r\n"); ?>
	background: <?php isset($data['testimonial_color']) && !empty($data['testimonial_color']) ? print_r($data['testimonial_color'].";\r\n"): print_r("#fff;\r\n");?>
	max-width: 550px;
	box-shadow: 0 0 8px #888888;
	-moz-box-shadow: 0 0 8px #888888;
	-webkit-box-shadow: 0 0 8px #888888;
}
blockquote.testimonial {
	margin: 0 !important;
	background: #B7EDFF;
	padding: 10px 50px!important;
	position: relative;
	font-family: <?php isset($decoded_font) && !empty($decoded_font) ? print_r($decoded_font.";\r\n"): print_r("Georgia, serif;\r\n");?>
	color: <?php isset($data['ctpo_font_color']) && !empty($data['ctpo_font_color']) ? print_r($data['ctpo_font_color'].";\r\n"): print_r("#666;\r\n");?>;
	border-radius: 5px;
	font-style: italic;
	<?php print_r($text_shadow."\r\n"); ?>
	background: <?php isset($data['testimonial_color']) && !empty($data['testimonial_color']) ? print_r($data['testimonial_color'].";\r\n"): print_r("#fff;\r\n");?>
	max-width: 550px;
	box-shadow: 0 0 8px #888888;
	-moz-box-shadow: 0 0 8px #888888;
	-webkit-box-shadow: 0 0 8px #888888;
}
<?php print_r($shine_effect);?>
div.text-above{
	z-index:2;
  position:relative;
}
span.text-above{
	z-index:2;
  position:relative;
}
.rating-cat {
	font-style: normal;
	font-weight: bold;
}
hr {
	border: 0;
	height: 0;
	border-top: 1px solid rgba(0, 0, 0, 0.1);
	border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}
.testimonial:before, .testimonial:after {
	content: "\201C";
	position: absolute;
	font-size: 80px;
	line-height: 1;
	color: <?php isset($data['ctpo_font_color']) && !empty($data['ctpo_font_color']) ? print_r($data['ctpo_font_color'].";\r\n"): print_r("#999;\r\n");?>;
	font-style: normal;
}
.testimonial:before {
	top: 0;
	left: 10px;
}
.testimonial:after {
	content: "\201D";
	right: 10px;
	bottom: -0.5em;
}
.arrow-down {
	width: 0;
	height: 0;
	border-left: 15px solid transparent;
	border-right: 15px solid transparent;
	border-top: 15px solid <?php isset($data['testimonial_color']) && !empty($data['testimonial_color']) ? print_r($data['testimonial_color'].";\r\n"): print_r("#fff;\r\n");?>
	margin: 0 0 0 25px;
	z-index: 1500;
}
.testimonial-author {
	margin: 0 0 0 25px;
	font-family: <?php isset($decoded_font) && !empty($decoded_font) ? print_r($decoded_font.";\r\n"): print_r("Arial, Helvetica, sans-serif;\r\n");?>
	text-align: left;
}
.testimonial-author span.contempo-city {
	font-size: 12px;
	color: #666;
}
div.text-above, .radial-effect, .ctpo-effect{
	float:none !important;
}