<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 Plugin Name: OpenBW Replay Viewer Widget
 Plugin URI: http://www.openbw.com
 Description: OpenBW Replay Viewer Widget with Info Bar
 Author: IMP
 Version: 1.0
 Author URI: http://www.openbw.com
 */
class Replay_Viewer_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Replay_Viewer_Widget', // Base ID
			__( 'Replay Viewer Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Replay Viewer Widget with Info Bar', 'text_domain' ), ) // Args
		);
		
	}

	private function add_graphs() {
		?>
		<aside draggable="true" id="graphs_tab" class="info_tab">
			<ul class="tabs" data-tabs id="graphs-tabs">
				<li class="tabs-title is-active"><a id="graphs_link1" href="#graph_tab_panel1"
					aria-selected="true">Resources</a></li>
				<!-- <li class="tabs-title"><a id="graphs_link2" href="#graph_tab_panel2">Unused</a></li>
				 -->
			</ul>
			<div class="tabs-content" data-tabs-content="graphs-tabs">
				<div class="tabs-panel is-active" id="graphs_tab_panel1">
					<canvas id="infoChartCanvas"></canvas>
				</div>
			</div> 
		</aside>
		<?php
	}
		
	private function add_info_tab() {
	
		?>
		<aside draggable="true" id="info_tab" class="info_tab">
			<ul class="tabs" data-tabs id="info-tabs">
				<li class="tabs-title is-active"><a id="tab_link1" href="#info_tab_panel1"
					aria-selected="true">Production</a></li>
				<li class="tabs-title"><a id="tab_link2" href="#info_tab_panel2">Army</a></li>
				<li class="tabs-title"><a id="tab_link3" href="#info_tab_panel3">Upgrades</a></li>
				<li class="tabs-title"><a id="tab_link4" href="#info_tab_panel4">Research</a></li>
			</ul>
			<div class="tabs-content" data-tabs-content="info-tabs">
				<div class="tabs-panel is-active" id="info_tab_panel1">
					<?php for ($i = 1; $i < 9; $i++) { ?>
					<div class="per-player-info<?php echo $i?>" style="display:none">
						<div class="info_tab_player_color player_color<?php echo $i?>"></div>
						<div id="production_tab_content<?php echo $i?>" class="info_tab_content">
						<?php 
						for ($j = 0; $j < 100; $j++) {
							echo '<div><img src=""/><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<?php }?>
				</div>
				<div class="tabs-panel" id="info_tab_panel2">
					<?php for ($i = 1; $i < 9; $i++) { ?>
					<div class="per-player-info<?php echo $i?>" style="display:none">
						<div class="info_tab_player_color player_color<?php echo $i?>"></div>
						<div id="army_tab_content<?php echo $i?>" class="info_tab_content">
						<?php 
						for ($j = 0; $j < 20; $j++) {
							echo '<div><img src=""/><div class="army_counter_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<?php }?>
				</div>
				<div class="tabs-panel" id="info_tab_panel3">
					<?php for ($i = 1; $i < 9; $i++) { ?>
					<div class="per-player-info<?php echo $i?>" style="display:none">
						<div class="info_tab_player_color player_color<?php echo $i?>"></div>
						<div id="upgrade_tab_content<?php echo $i?>" class="info_tab_content">
						<?php 
						for ($j = 0; $j < 20; $j++) {
							echo '<div><img src=""/><span></span><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<?php }?>
				</div>
				<div class="tabs-panel" id="info_tab_panel4">
					<?php for ($i = 1; $i < 9; $i++) { ?>
					<div class="per-player-info<?php echo $i?>" style="display:none">
						<div class="info_tab_player_color player_color<?php echo $i?>"></div>
						<div id="research_tab_content<?php echo $i?>" class="info_tab_content">
						<?php 
						for ($j = 0; $j < 20; $j++) {
							echo '<div><img src=""/><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<?php }?>
				</div>
			</div> 
		</aside>
		<?php
	}
		
	/**
	 * Display the main content
	 */
	public function show_viewer_ui() {
		
		wp_register_script( 'chartjs','https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js', '2.5.0', true);
		wp_enqueue_script( 'chartjs' );
		
		wp_register_script( 'info-bar', WP_PLUGIN_URL.'/replay_viewer/info-bar.js', array('jquery'), '1.0.1', true);
		wp_enqueue_script( 'info-bar' );
		
		wp_register_script( 'start', WP_PLUGIN_URL.'/replay_viewer/start.js', array('info-bar'), '1.0.1', true);
		wp_enqueue_script( 'start' );
		
		wp_register_script( 'test', WP_PLUGIN_URL.'/replay_viewer/test.js', array('start'), '1.0.1', true);
		wp_enqueue_script( 'test' );
		
		$rep_url = $_GET['rep'];
		wp_localize_script( 'start', 'ajax_object',
				array( 'replay_file' => $rep_url ) );
		
		?>
		<div class="reveal" id="rv_modal" data-reveal>
		 	<h3></h3>
			<p></p>
			<span id="mpq_specify" style="display:none">
				<input id="mpq_files" type="file" accept=".mpq" multiple />
				<ul id="list">
				</ul>
			</span>
			<button class="close-button" data-close aria-label="Close reveal" type="button">
		    	<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="canvas-area" class="canvas-area">
			<canvas class="emscripten" id="canvas"
				oncontextmenu="event.preventDefault()"></canvas>
				<?php $this->add_info_tab() ?>
				<?php $this->add_graphs() ?>
		</div>
		<div class="expanded row align-justify infobar-container">
			<div id="infobar" class="column infobar">
				<div class="row infobar-text 5player" style="display:none">
					<div class="small-1 columns rv_ib_race" id="map_label">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick" id="map">&nbsp;</div>
					<div class="small-2 columns rv_ib_supply">SUPPLY</div>
					<div class="small-1 columns rv_ib_minerals">MINERALS</div>
					<div class="small-1 columns rv_ib_gas">GAS</div>
					<div class="small-1 columns rv_ib_worker">WORKERS</div>
					<div class="small-1 columns rv_ib_army">ARMY</div>
					<div class="small-2 columns rv_ib_apm">APM</div>
				</div>
				<div class="row infobar-player" id="infobar_row_player1">
					<div class="small-1 columns rv_ib_race" id="race1">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick player_color1" id="nick1">-</div>
					<div class="small-2 columns rv_ib_supply" id="supply1">-</div>
					<div class="small-1 columns rv_ib_minerals" id="minerals1">-</div>
					<div class="small-1 columns rv_ib_gas" id="gas1">-</div>
					<div class="small-1 columns rv_ib_worker" id="workers1">-</div>
					<div class="small-1 columns rv_ib_army" id="army1">-</div>
					<div class="small-2 columns rv_ib_apm" id="apm1">-</div>
				</div>
				<div class="row infobar-text 2player">
					<div class="small-1 columns rv_ib_race" id="map_label">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick" id="map">&nbsp;</div>
					<div class="small-2 columns rv_ib_supply">SUPPLY</div>
					<div class="small-1 columns rv_ib_minerals">MINERALS</div>
					<div class="small-1 columns rv_ib_gas">GAS</div>
					<div class="small-1 columns rv_ib_worker">WORKERS</div>
					<div class="small-1 columns rv_ib_army">ARMY</div>
					<div class="small-2 columns rv_ib_apm">APM</div>
				</div>
				<?php for ($i = 2; $i < 9; $i++) { ?>
					<div class="row infobar-player per-player-info<?php echo $i?>" style="display:none">
						<div class="small-1 columns rv_ib_race" id="race<?php echo $i?>">&nbsp;</div>
						<div class="small-3 columns rv_ib_nick player_color<?php echo $i?>" id="nick<?php echo $i?>">-</div>
						<div class="small-2 columns rv_ib_supply" id="supply<?php echo $i?>">-</div>
						<div class="small-1 columns rv_ib_minerals" id="minerals<?php echo $i?>">-</div>
						<div class="small-1 columns rv_ib_gas" id="gas<?php echo $i?>">-</div>
						<div class="small-1 columns rv_ib_worker" id="workers<?php echo $i?>">-</div>
						<div class="small-1 columns rv_ib_army" id="army<?php echo $i?>">-</div>
						<div class="small-2 columns rv_ib_apm" id="apm<?php echo $i?>">-</div>
					</div>
				
				<?php } ?>
			</div>
			
			<div class="shrink column replay-control">
				
				<div class="row rv-rc-controls">
					<div class="small-3 columns volume">
						<div id="volume-slider-wrapper">
							<div id="volume-slider" class="slider vertical" data-slider data-initial-start='50' data-end='100' data-vertical="true">
								<span id="volume-slider-handle" class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="volumeOutput"></span>
								<span class="slider-fill" data-slider-fill></span>
								<input type="hidden" id="volumeOutput">
							</div>
						</div>
						<button id="rv-rc-sound" type="button" class="rv-rc-sound"></button>
					</div>
					<div class="small-3 columns">
						<button id="rv-rc-play" type="button" class="rv-rc-pause"></button>
					</div>
					<div class="small-3 columns">
						<button id="rv-rc-slower" type="button" class="rv-rc-slower"></button>
					</div>
					<div class="small-3 columns">
						<button id="rv-rc-faster" type="button" class="rv-rc-faster"></button>
					</div>
				</div>
				<div class="row rv-rc-timer">
					<div id="rv-rc-timer" class="small-6 columns">-</div>
					<div id="rv-rc-speed" class="small-6 columns">-</div>
				</div>
				<div class="row rv-rc-progress-bar">
					<div class="column">
						<div id="game-slider" class="slider" data-slider data-initial-start="0" data-end="200">
							<span id="game-slider-handle" class="slider-handle"  data-slider-handle role="slider" tabindex="1" aria-controls="sliderOutput"></span>
						  	<span class="slider-fill" data-slider-fill></span>
						  	<input type="hidden" id="sliderOutput">
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php 

	}
	
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args
	 *        	Widget arguments.
	 * @param array $instance
	 *        	Saved values from database.
	 */
	public function widget($args, $instance) {
		echo $args ['before_widget'];
		if (! empty ( $instance ['title'] )) {
			echo $args ['before_title'] . apply_filters ( 'widget_title', $instance ['title'] ) . $args ['after_title'];
		}
		
		$this->show_viewer_ui ();
		
		echo $args ['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // end class

// register Replay Viewer widget
function register_replay_viewer_widget() {
    register_widget( 'Replay_Viewer_Widget' );
}
add_action( 'widgets_init', 'register_replay_viewer_widget' );

function replay_viewer_func($atts, $content = null) {

	// read parameters from tag: ID and Style
	$a = shortcode_atts( array(
			'id' => '0',
			'style' => 'default',
	), $atts );
	$widget_id = $a['id'];
	$widget_style = $a['style'];

	ob_start();
	the_widget('Replay_Viewer_Widget');
	$output = ob_get_clean();
	
	return $output;
}
add_shortcode ( 'replayViewer', 'replay_viewer_func' );
