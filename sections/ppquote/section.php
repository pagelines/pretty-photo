<?php
/*
	Section: Quotes (Pretty Photo)
	Author: PageLines
	Author URI: http://pagelines.com
	Description: Quotes section
	Filter: Components, Quotes
	Class Name: PPQuotes
*/

class PPQuotes extends PageLinesSection {

	var $default_limit = 3;
	var $default_font = '"Lato", Arial, serif';
	const version = '1.0';

	function section_persistent(){

        add_filter( 'pless_vars', array(&$this,'less'));

	}

	function less($less){

		$less['pp-quote-size'] = ( $this->opt('pp_quotes_size') ) ? $this->opt( 'pp_quotes_size' ) : '16px';

		return $less;
	}

	function section_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('pp_quotes',$this->base_url.'/jquery.cycle2.min.js', array('jquery'), self::version, true);
	}

	function section_head() {
		$id     = $this->get_the_id();
		?><script>
		jQuery(document).ready(function(){
			jQuery( '.pp-quote-slideshow.pp-quote-slideshow-<?php echo $id;?>' ).cycle();
		});
		</script><?php

		echo load_custom_font( $this->opt('pp_quotes_font'),'.pp-quotes-item');
	}


	// Draw the columns
	function section_template() {

		$output = '';
		$id     = $this->get_the_id();
		$items  = $this->opt('pp_quotes_count') ? $this->opt('pp_quotes_count') : $this->default_limit;
		$margin = $this->opt('pp_quotes_margin') ? $this->opt('pp_quotes_margin') : '40px 20px';
		$themargin = ($margin) ? sprintf('style="margin:%s;"',$margin) : null;

		$tran = $this->opt('pp_quotes_transition') ? $this->opt('pp_quotes_transition') : 'fade';
		$speed = $this->opt('pp_quotes_speed') ? $this->opt('pp_quotes_speed') : '5000';

		$args = sprintf('data-cycle-fx="%s" data-cycle-timeout="%s" data-cycle-slides="> blockquote" data-cycle-pager=".pp-quotes-pager"', $tran, $speed);

		printf('<div class="pp-quotes-wrap" %s>',$themargin);
			printf('<div class="pp-quotes-wrap pp-quote-slideshow pp-quote-slideshow-%s" %s>', $id,$args);

				for($i = 1; $i <= $items; $i++):

					$quote    = $this->opt('pp_quote_'.$i);
					$cite     = $this->opt('pp_quote_cite_'.$i);
					$link     = $this->opt('pp_quote_link_'.$i);

					$citelink = ($link) ? sprintf('<p class="pp-quote-cite" ><a href="%s">%s</a></p>',$link,$cite) : sprintf('<p class="pp-quote-cite" >%s</p>',$cite);

					if($quote) {

						$output .= sprintf('<blockquote class="pp-quotes-item">%s%s</blockquote>',$quote, $citelink);

					}

				endfor;

				if($output == '') {

					$this->defaults();

				} else {

					echo $output;

				}

			echo '</div>';

			if($this->opt('pp_quotes_pager'))
				printf('<div class="pp-quotes-pager"></div>');

		?></div><?php

	}

	// Quotes defaults
	function defaults() {

		ob_start();

		?><blockquote class="pp-quotes-item">
			<p class="pp-quote"><?php _e('&ldquo;Impossible is a word to be found only in the dictionary of fools.&rdquo;','pp');?></p>
			<small class="pp-quote-cite" ><?php _e('Napoleon Bonaparte','pp');?></small>
		</blockquote>
		<blockquote class="pp-quotes-item">
			<p class="pp-quote"><?php _e('&ldquo;The secret of health for both mind and body is not to mourn for the past, worry about the future, or anticipate troubles but to live in the present moment wisely and earnestly.&rdquo;','pp');?></p>
			<small class="pp-quote-cite" ><?php _e('Buddah','pp');?></small>
		</blockquote>
		<blockquote class="pp-quotes-item">
			<p class="pp-quote"><?php _e('&ldquo;Once you have mastered time, you will understand how true it is that most people overestimate what they can accomplish in a year – and underestimate what they can achieve in a decade!&rdquo;','pp');?>.</p>
			<small class="pp-quote-cite" ><?php _e('Someone famous','pp');?></small>
		</blockquote><?php

		$defaults = ob_get_clean();
		echo $defaults;

	}

	// Quotes options
	function section_opts(){

		$options = array();
		
		$options[] = array(
			'title'                   => __( 'Configuration', 'pp' ),
			'type'	                  => 'multi',
			'opts'	                  => array(
				array(
					'key'			  => 'pp_quotes_count',
					'type' 			  => 'count_select',
					'count_start'	  => 1,
					'count_number'	  => 12,
					'default'		  => 4,
					'label' 	      => __( 'Number of Quotes to Configure', 'pp' ),
				),
				array(
					'key'             => 'pp_quotes_align',
					'type'            => 'select',
					'title'		      => __( 'Quotes Alignment', 'pp' ),
					'opts'            => array(
						'left'	      => array('name' => __( 'Align Left', 'pp' )),
						'center'	  => array('name' => __( 'Align Center', 'pp' )),
						'right'	      => array('name' => __( 'Align Right', 'pp' )),
					),
				),
				array(
					'key'             => 'pp_quotes_transition',
					'type'            => 'select',
					'title'		      => __( 'Transition', 'pp' ),
					'opts'            => array(
						'scrollHorz'  => array('name' => __( 'Horizontal', 'pp' )),
						'fade'	      => array('name' => __( 'Fade', 'pp' )),
						'right'	      => array('name' => __( 'Align Right', 'pp' )),
					),
				),
				array(
					'key'             => 'pp_quotes_margin',
					'type'            => 'text',
					'title'		      => __( 'Margin', 'pp' ),
				),
				array(
					'key'             => 'pp_quotes_size',
					'type'            => 'text',
					'title'		      => __( 'Font Size', 'pp' ),
				),
				array(
					'key'             => 'pp_quotes_speed',
					'type'            => 'text',
					'title'		      => __( 'Speed', 'pp' ),
				),
				array(
					'key'			=> 'pp_quotes_font',
					'type' 			=> 'type',
					'label' 		=> __('Quotes Font', 'pp'),
				),
				array(
					'key'             => 'pp_quotes_pager',
					'type'            => 'check',
					'title'		      => __( 'Enable Pager', 'pp' ),
				),

			)

		);

		$items = ($this->opt('pp_quotes_count')) ? $this->opt('pp_quotes_count') : $this->default_limit;

		for($i = 1; $i <= $items; $i++){

			$opts = array(

				array(
					'key'		      => 'pp_quote_'.$i,
					'label'		      => __( 'Quote', 'pp' ),
					'type'		      => 'textarea'
				),
				array(
					'key'		      => 'pp_quote_cite_'.$i,
					'label'		      => __( 'Cite (Source)', 'pp' ),
					'type'		      => 'text'
				),
				array(
					'key'		      => 'pp_quote_link_'.$i,
					'label'		      => __( 'Link', 'pp' ),
					'type'		      => 'text'
				),

			);

			$options[] = array(
				'title' 	          => __( 'Quote ', 'pp' ) . $i,
				'type' 		          => 'multi',
				'opts' 		          => $opts
			);

		}

		return $options;
	}


}