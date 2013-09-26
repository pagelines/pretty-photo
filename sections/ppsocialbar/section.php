<?php
/*
Section: Social Bar (Pretty Photo)
Author: PageLines
Author URI: http://pagelines.com
Description: Simple Social Bar with most of the common social media websites
Filter: component, social
Loading: active
Class Name: PPSocialBar
*/

class PPSocialBar extends PageLinesSection {

	function section_opts()
	{
		$opts = array(
			array(
				'key' => 'tm_cb_social',
				'type'			=> 'multi',
				'title'			=> __('Social Sites URL ex.http://www.site.com', 'prettyphoto'),
				'label'		=> __('In the follow fields please, enter the social URL, if the URL field is empty, nothing will show.', 'prettyphoto'),
				'opts'	=> $this->get_social_fields()
			),
		);
		return $opts;
	}
	
	
	var $domain = 'pp-social-bar';

	function section_persistent(){
	}
	
	function section_head(){ ?>
		<script>
		jQuery(document).ready(function($) {
			jQuery(".pp-menu a").tooltip({
				placement:"top"
			});

		});
		</script>
		<?php 
	}

 	function section_template()
 		{
			$socials    = array();
			foreach ($this->get_valid_social_sites() as $key => $social) {
				if( $this->opt( $social . '-url' ) ){
					array_push($socials, array('site' => $social, 'url' => $this->opt( $social . '-url' )));
				}
			}
 	?>
				<div class="pp-icons">
						<ul class="pp-menu">
							<?php foreach ($socials as $social): ?>
								<li class="<?php echo $social['site'] ?>">
									<a href="<?php echo $social['url'] ?>" title="<?php echo ucfirst($social['site']) ?>" target="_blank"></a>
								</li>
							<?php endforeach ?>
						</ul>
						<div class="clear"></div>
				</div>
	<?php
 	}

	function get_social_fields()
	{
		$out = array();
		foreach ($this->get_valid_social_sites() as $social => $name)
		{
			$out[$name . '-url'] = array(
				'key' => $name . '-url',
				'label' => __(ucfirst($name)),
				'type' => 'text'
			);
		}
		return $out;
	}

	function get_valid_social_sites()
	{
		return array("facebook","twitter","googleplus","dribbble","instagram","behance","youtube","digg","flickr","forrst","html5","lastfm","linkedin","paypal","picasa","pinterest","rss","skype","stumbleupon","tumblr","vimeo","wordpress","yahoo"
		);
	}


}