<?php
/*
	Section: Circle Graph (Pretty Photo)
	Author: PageLines
	Author URI: http://pagelines.com
	Description: Simple circular graph to display data
	Class Name: PPCircleGraph
	Filter: Post Layouts
	Cloning: false
*/

class PPCircleGraph extends PageLinesSection {

	function section_styles(){
		wp_enqueue_script( 'easy-pie-chart', $this->base_url . '/js/jquery.easy-pie-chart.js', array( 'jquery' ), '1.2.3', true );
	}
	
	function section_head(){
        $fillingcolor = ($this->opt('filling_color') ) ? pl_hashify( $this->opt('filling_color')) : '#4dc8e3';
        $strokecolor = ($this->opt('stroke_color') ) ? pl_hashify( $this->opt('stroke_color')) : '#f3f3f3';
        $strokewidth = ($this->opt('stroke_width')) ? $this->opt('stroke_width') : 10;
        $circlegraphs = $this->opt('circle_graphs');
	?>
		<script type="text/javascript">
            jQuery(document).ready(function($) {
                var easyAnimation = false;
                $(window).bind("scroll", function(event) {
                   if( easyAnimation){return;}
                   jQuery(".section-ppcirclegraph:in-viewport").each(function() {
                        createChart();
                        easyAnimation = true;
                    });    
                });

                

                createChart = function(){
                    jQuery('.graph-percent').easyPieChart({
                        animate: 1000,
                        size: 200,
                        barColor: '<?php echo pl_hashify( $fillingcolor ) ?>',
                        trackColor : '<?php echo pl_hashify( $strokecolor ) ?>',
                        scaleColor : 'transparent',
                        lineWidth : <?php echo $strokewidth ?>,
                        onStep: function(value) {
                            this.$el.find('span').text(Math.ceil( value )) ;
                        },
                        onStop : function(){
                            <?php for($i=0; $i<$circlegraphs; $i++):?>
                                jQuery('#circle-graph-<?php echo $i ?> div span').text(jQuery('#circle-graph-<?php echo $i ?> div').data('percent'))
                            <?php endfor ?>
                        }
                    });
                }
            });
        </script>
	<?php
	}

    function section_opts(){
        $opts = array(
            array(
                'key'           => 'circle-graph-setup',
                'type'          => 'multi',
                'title'         => 'Settings',
                'label'         => 'Settings',
                'opts' => array(
                    array(
                        'key' => "circle_graphs",
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 12,
                        'label' => 'Number of circle graphs to configure'
                    ),
                    array(
                        'key' => 'graph_span',
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 12,
                        'label' => 'Number of Columns for each graph (12 Col Grid) '
                    ),
                    array(
                        'key' => 'stroke_color',
                        'type' => 'color',
                        'default' => '#f3f3f3',
                        'label' => 'Circle graph stroke color'
                    ),
                    array(
                        'key' => 'filling_color',
                        'type' => 'color',
                        'default' => '#4dc8e3',
                        'label' => 'Circle graph stroke filling color'
                    ),
                    array(
                        'key' => 'stroke_width',
                        'type' => 'count_select',
                        'count_start'   => 1,
                        'count_number'  => 14,
                        'label' => 'Circle graph stroke width'
                    )
                )
            )
        );
        $opts = $this->graph_settings($opts);
        return $opts;
    }

    function graph_settings($opts){
        $loopCount = (  $this->opt('circle_graphs') ) ? $this->opt('circle_graphs') : 1;
        for ($i=0; $i < $loopCount; $i++) {
            $graph = array(
                'key' => 'circle_graph_'.$i,
                'type' =>  'multi',
                'title' => 'Graph ' . ($i+1),
                'label' => 'Settings',
                'opts' => array(
                    array(
                        'key' => 'graph_percent_' .$i,
                        'type' => 'text',
                        'label' => 'Percentage',
                    ),
                    array(
                        'key' => 'graph_label_' .$i,
                        'type' => 'text',
                        'label' => 'Label',
                    ),

                )
            );

            array_push($opts, $graph);

        }
        return $opts;
    }

	function section_template(){
        $circlegraphs = $this->opt('circle_graphs');
        if( $circlegraphs == false){
        ?>
            <div class="row">
                <div class="span3">
                    <div class="graph" id="circle-graph-0">
                        <div class="graph-percent" data-percent="50">
                            <span>50</span>%
                        </div>
                        <div class="graph-label">
                            Awesome Graph
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
	?>
        <div class="row">
            <?php for ($i=0; $i<$circlegraphs; $i++): ?>
                <div class="span<?php echo $this->opt('graph_span') ?>">
                    <div class="graph" id="circle-graph-<?php echo $i ?>">
                        <div class="graph-percent" data-percent="<?php echo $this->opt('graph_percent_' .$i)?>">
                            <span><?php echo $this->opt('graph_percent_' .$i) ?></span>% 
                        </div>
                        <div class="graph-label" data-sync="<?php echo 'graph_label_' .$i ?>">
                            <strong><?php echo $this->opt('graph_label_' .$i) ?></strong>
                        </div>
                    </div>
                </div>
            <?php endfor ?>
        </div>
	<?php
	}
}