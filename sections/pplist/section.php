<?php
/*
	Section: List (Pretty Photo)
	Author: PageLines
	Author URI: http://www.pagelines.com
	Description: An amazing, professional Features List section.
	Class Name: PPList
	Filter: component
	Loading: active
	Edition: pro
*/


class PPList extends PageLinesSection {

	var $default_limit = 4;

	function section_styles(){
		
	}


	function section_opts(){
		$options = array();

		$options[] = array(

			'title' => __( 'List Configuration', 'pagelines' ),
			'type'	=> 'multi',
			'opts'	=> array(
				array(
					'key'		=> 'list_head',
					'label'		=> __( 'Heading', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'list_subhead',
					'label'		=> __( 'Subheading', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'			=> 'list_count',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> 6,
					'label' 	=> __( 'Number of Lists to Configure', 'pagelines' ),
				),
				array(
					'key'			=> 'list_cols',
					'type' 			=> 'count_select',
					'count_start'	=> 1,
					'count_number'	=> 12,
					'default'		=> '4',
					'label' 	=> __( 'Number of Columns for Each List (12 Col Grid)', 'pagelines' ),
				),
				array(
					'key'		=> 'list_icons',
					'label'		=> __( 'List Icons', 'pagelines' ),
					'type'		=> 'text',
					'help'		=> '<strong>Ex. icon-ok-sign <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/">Icon Reference</a></strong>',
				),
			)

		);

		$slides = ($this->opt('list_count')) ? $this->opt('list_count') : $this->default_limit;
	
		for($i = 1; $i <= $slides; $i++){

			$opts = array(

				array(
					'key'		=> 'pp_list_title_'.$i,
					'label'		=> __( 'List Title', 'pagelines' ),
					'type'		=> 'text'
				),
				array(
					'key'		=> 'pricing_attributes_'.$i,
					'label'	=> __( 'Pricing Attributes', 'pagelines' ),
					'type'	=> 'textarea',
					'help'	=> __( 'Add each attribute on a new line. Add a "*" in front to add emphasis.', 'pagelines' ),
				),
			);


			$options[] = array(
				'title' 	=> __( 'List ', 'pagelines' ) . $i,
				'type' 		=> 'multi',
				'opts' 		=> $opts,

			);

		}

		return $options;
	}


   function section_template( ) { 
		
		$listhead = $this->opt('list_head') ? $this->opt('list_head') : 'List Heading';
		$listsubhead = $this->opt('list_subhead') ? $this->opt('list_subhead') : 'List Subheading';

		$headings = sprintf(
			'<div class="row pl-animation pl-appear fix">
				<h3 class="head">%1$s</h3>
				<p class="subhead">%2$s</p>
			</div>',
			$listhead,
			$listsubhead
		);
	
	
		$cols = ($this->opt('list_cols')) ? $this->opt('list_cols') : 4;
		$num = ($this->opt('list_count')) ? $this->opt('list_count') : $this->default_limit;
		$listicons = ($this->opt('list_icons')) ? $this->opt('list_icons') : 'icon-ok-sign';
		$width = 0;
		$output = '';
	
		$master = array();
		for($i = 1; $i <= $num; $i++){
			
			$master[$i]['title'] = ($this->opt('pp_list_title_'.$i)) ? $this->opt('pp_list_title_'.$i) : 'List Title'; 
			
			$master[$i]['attr'] = ($this->opt('pricing_attributes_'.$i)) ? $this->opt('pricing_attributes_'.$i) : '';
			
		}

		foreach($master as $i => $list){
			
			
			$title 		= $list['title'];
			$attr 		= $list['attr']; 
		
		
			$attr_list = ''; 
			
			if($attr != ''){
				
				$attr_array = explode("\n", $attr);
				
				foreach($attr_array as $at){
					
					if(strpos($at, '*') === 0){
						$at = str_replace('*', '', $at); 
						$attr_list .= sprintf('<li class="emphasis"><i class="' . $listicons . '"></i> %s</li>', $at); 
					} else {
						$attr_list .= sprintf('<li><i class="' . $listicons . '"></i> %s</li>', $at); 
					}
					
				}
				
			} 
		
			
			$attr_list = $attr_list; 
			
			$formatted_attr = ($attr_list != '') ? sprintf('<div class="pp-attributes"><ul>%s</ul></div>', $attr_list) : '';
		
			if($width == 0)
				$output .= '<div class="row fix">';

			$output .= sprintf(
				'<div class="span%1$s pp-list pl-animation pl-appear fix">
					<div class="pp-header">
						<div class="pp-title" data-sync="pp_list_title_%4$s">
							%2$s
						</div>
					</div>
					%3$s
				</div>',
				$cols,
				$title,
				$formatted_attr, 
				$i
			);

			$width += $cols;

			if($width >= 12 || $i == $num){
				$width = 0;
				$output .= '</div>';
			}


		 }
	
	
	?>
	
	<div class="pp-list-wrap pl-animation-group">
		<?php echo $headings; ?>
		<?php echo $output; ?>
	</div>

<?php }


}
