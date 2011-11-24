<?php
/*
Plugin Name: Iframe to Embed
Plugin URI: http://sw-galati.ro/iframe-to-embed-plugin/
Description:Used for embedding PDF files in posts and pages. The code can be placed inside the visual vindow, not in the HTML window. Plugin transforms iframe code to embed code with [iframe src="http://your-file.pdf" width="100%" height="900"] shortcode.
Version: 1.2
Author: sw-galati.ro
Author Email: sw.galati(at)gmail.com
Author URI: http://sw-galati.ro/
*/

if ( !function_exists( 'iframe_embed_shortcode' ) ) :

	function iframe_enqueue_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action('wp_enqueue_scripts', 'iframe_enqueue_script');
				
	function iframe_embed_shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
			'width' => '100%',
			'height' => '900',
			'src' => '',
		), $atts));
		$src_cut = substr($src, 0, 35);
		if(strpos($src_cut, 'maps.google' )){
			$google_map_fix = '&output=embed';
		}else{
			$google_map_fix = '';
		}
		$return = '';
		if( $same_height_as != '' ){
			if( $same_height_as != 'content' ){ // we are setting the height of the iframe
				if( $same_height_as == 'document' || $same_height_as == 'window' ){ // remove quotes for window or document selectors
					$target_selector = $same_height_as;
				}else{
					$target_selector = '"'.$same_height_as.'"';
				}
				$return .= '
					<script>
					jQuery(document).ready(function($) {
						var target_height = $('.$target_selector.').height();
						$("iframe.'.$class.'").height(target_height);
					});
					</script>
				';
			}else{ // set the actual height of the iframe (show all content of the iframe without scroll)
				$return .= '
					<script>
					jQuery(document).ready(function($) {
						$("iframe.'.$class.'").bind("load", function() {
							var embed_height = $(this).contents().find("body").height();
							$(this).height(embed_height);
						});
					});
					</script>
				';
			}
		}
                if( $style != '' ){
			$style_text = 'style="'.$style.'" ';
		}else{
			$style_text = '';
		}
		$return .= "\n".'<!-- powered by Iframe to Embed plugin ver. 1.1 beta (sw-galati.ro) -->'."\n";
		$return .= '<embed '.$style_text.'width="'.$width.'" height="'.$height.'" src="'.$src.$google_map_fix.'"></embed>';
		// &amp;output=embed
		return $return;
	}
	add_shortcode('iframe', 'iframe_embed_shortcode');
	
endif;