<?php

/* 
 * Setup/Load core framework functions
 */
require_once( dirname(__FILE__) . '/setup.php' );

/* 
 *  Post Formats
 */
add_action( 'after_setup_theme', 'childtheme_formats', 11 );
function childtheme_formats(){
     add_theme_support( 'post-formats', array( 'link' ) );
}


/* 
 *  Set default featured image size and allow cropping
 */
set_post_thumbnail_size( 595, 300, true );


function pagelines_head_scripts(){
	?>
	<script>
	jQuery(document).ready(function($) {

		jQuery(".smooth-scroll").on('click', function(e){		
			e.preventDefault();
			jQuery('html,body').animate({
				scrollTop: jQuery(this.hash).offset().top - 20
			}, 500);
		});
	});
	</script>	
<?php 		
}	

function pagelines_footer_scripts(){
	
	if( 
		( !isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '' ) 
		&& ( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' ) 
	){
		
	}
}
?>