<?php
/*
	Section: Projects (Pretty Photo)
	Author: PageLines
	Author URI: http://pagelines.com
	Description: Portfolio custom post type for Pretty Photo
	Class Name: PPProjects
	Filter: Post Layouts
	Cloning: false
*/

class PPProjects extends PageLinesSection {

    var $ptID = 'projects';
    var $taxID = 'project-categories';
    const version = '1.0';

    function section_persistent(){

        $this->post_type_setup();
        $this->post_meta_setup();

    }

    function section_scripts() {

        global $pagelines_ID;
        $oset = array('post_id' => $pagelines_ID);
        $filtering = $this->opt('pp_projects_filtering_on');

        // Load Projects JS
        if($filtering)
            wp_enqueue_script('projects-filter-js',$this->base_url.'/js/jquery.projects.filter.js',array('jquery'),self::version,true);

    }


    function section_head(){

    }


    function post_type_setup(){
		
        $args = array(
            'label'             	=> __('Projects', 'prettyphoto'),
			'public' => __('true', 'prettyphoto'),
		    'publicly_queryable' => __('true', 'prettyphoto'),
		    'capability_type' => __('post', 'prettyphoto'),
		    'has_archive' => __('true', 'prettyphoto'),
		    'hierarchical' => __('false', 'prettyphoto'),
            'singular_label'    	=> __('Project', 'prettyphoto'),
            'description'       	=> __('For creating projects', 'prettyphoto'),
            'menu_icon'         	=> $this->icon,
        );
        $taxonomies = array(
            $this->taxID => array(
                "label" => __('Categories', 'prettyphoto'),
                "singular_label" => __('Category', 'prettyphoto'),
            )
        );

        $columns = array(
            "cb"            => "<input type=\"checkbox\" />",
            "title"         => "Title",
            "description"   => "Text",
            "event-categories"    => "Categories"
        );

        $this->post_type = new PageLinesPostType( $this->ptID, $args, $taxonomies,$columns,array(&$this, 'column_display'));

    }


    function post_meta_setup(){


    }


    function taxo_post_class() {

        $custom_terms = get_the_terms(0, $this->taxID);

        if ($custom_terms) {
          foreach ($custom_terms as $custom_term) {
            $out = $custom_term->slug;
          }
        }

        return $out;
    }

    function section_template() {

        global $post;


        $pplayout = $this->opt('pp_projects_layout') ? $this->opt('pp_projects_layout') : '2col';
        $filtering = $this->opt('pp_projects_filtering_on');
        $cats = $this->opt('pp_projects_category');
		$ppdate = get_the_date('M. Y') ;

        if($filtering) {
            $this->get_project_cats();
        }

        printf('<ul class="unstyled pp-projects-wrap">');

            if($cats)
                $args = array('post_type' => $this->ptID,'posts_per_page' => 100,'tax_query' => array(array('taxonomy' => $this->taxID,'field' => 'slug','terms' => array($cats))));
            else
                $args = array('post_type' => $this->ptID,'posts_per_page' => 100, 'public' => true,	'has_archive' => true, );

                $loop = new WP_Query( $args);
				

                while ( $loop->have_posts() ) : $loop->the_post();

					$pppermalink = get_permalink($post->ID);

                    printf('<li class="pp-project-single pp-project-%s %s">', $pplayout, $this->taxo_post_class());

                        $img = get_the_post_thumbnail(get_the_ID(), array(595,300));

                        printf('<div class="item">
									<a href="%1s"><span class="thumbnail">%s</span></a>
									<span class="title">%s</span>
									<span class="date clear">%s</span>
								</div>', 
								$pppermalink, 
								$img, 
								get_the_title(), 
								$ppdate
								);

                    echo '</li>';

                endwhile;

                wp_reset_query();

        printf('</ul>');
    }


    function get_project_cats(){

        global $post;
        $orderby = $this->opt('pp_projects_filter_orderby',$this->oset) ? $this->opt('pp_projects_filter_orderby',$this->oset) : 'id';
        $excludeid = $this->opt('pp_projects_filter_exclude',$this->oset);
        $args = array('orderby'     => $orderby,'exclude'     => array($excludeid),);
        $custom_terms = get_terms($this->taxID,$args);

        ?><nav class="pp-projects-filter-nav-wrap fix">
            <ul class="unstyled pp-projects-filter">

                <li class="active"><a class="all" href="#"><i class="icon-picture"></i>&nbsp; all</a></li><?php foreach ($custom_terms as $custom_term) {
                    printf('<li data-filter="%s"><a href="#">%s</a></li>',$custom_term->slug,$custom_term->name);
                } ?>

            </ul>
        </nav><?php
    }

    function section_optionator($settings) {
        $settings = wp_parse_args($settings, $this->optionator_default);

        $tab = array(
           
            'pp_projects_category'  => array(
                'title'           => 'View Control',
                'shortexp'        => __('Select which Project category to show on this page','projects'),
                'type'            => 'select',
                'selectvalues'    => $this->get_option_terms(),
                'exp'             => __('Shows all unless specified','projects'),
            ),
            'pp_projects_layout' => array(
                'title'    =>'Projects Layout',
                'shortexp'  =>'Control the display of items',
                'type'          => 'select',
                'selectvalues'  => array(
                    '2col'   => array('name' => '2 Column'),
                    '3col'   => array('name' => '3 Column'),
                    '4col' => array('name' => '4 Column'),
                ),
            ),
            'pp_projects_filter_options' => array(
                'type' => 'multi_option',
                'title' => 'Filter Options',
                'shortexp' => 'Options for sorting and filtering projects',
                'selectvalues' => array(
                    'pp_projects_filtering_on' => array(
                        'type' => 'check',
                        'inputlabel' => 'Enable Filtering'
                    ),
                    'pp_projects_filter_exclude' => array(
                        'type' => 'text',
                        'inputlabel' => 'ID\'s to exclude'
                    ),
                    'pp_projects_filter_orderby' => array(
                        'type' => 'select',
                        'inputlabel' => 'Order by (default is date created)',
                        'selectvalues' => array(
                            'id' => array('name' => 'Date Created'),
                            'count' => array('name' => 'Count'),
                            'name' => array('name' => 'Name'),
                            'slug' => array('name' => 'Slug'),
                            'none' => array('name' => 'None'),
                        ),
                    ),
                ),
                'exp' => 'Displays the Project Categories. You can exclude categories via ID, and additional sort them. More info below.'
            ),
        );

        $tab_settings = array(
            'id'        => 'projects_meta',
            'name'      => 'Projects',
            'icon'      => $this->icon,
            'clone_id'  => $settings['clone_id'],
            'active'    => $settings['active']
        );

        register_metatab( $tab_settings, $tab);
    }


    function column_display($column){
        global $post;

        switch ($column){
            case "description":
                the_excerpt();
                break;
            case "event-categories":
                $this->get_tags();
                break;
        }
    }

    // List terms for option array dropdown
    function get_option_terms() {

        $terms = get_terms($this->taxID);
           foreach( $terms as $term )
               $categories[ $term->slug ] = array( 'name' => $term->name );

           return ( isset( $categories) ) ? $categories : array();
    }

    // fetch the tags for the columns in admin
    function get_tags() {
        global $post;

        $terms = wp_get_object_terms($post->ID, $this->taxID);
        $terms = array_values($terms);

        for($term_count=0; $term_count<count($terms); $term_count++) {

            echo $terms[$term_count]->slug;

            if ($term_count<count($terms)-1){
                echo ', ';
            }
        }
    }

}