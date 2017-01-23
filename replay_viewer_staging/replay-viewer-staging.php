<?php
defined('ABSPATH') or die('No script kiddies please!');

/**
 Plugin Name: OpenBW Replay Viewer Staging
 Plugin URI: http://www.openbw.com
 Description: OpenBW Replay Viewer Staging Area
 Author: IMP
 Version: 1.0
 Author URI: http://www.openbw.com
 */
class Replay_Viewer_Staging extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Replay_Viewer_Staging', // Base ID
			__( 'Replay Viewer Staging', 'text_domain' ), // Name
			array( 'description' => __( 'Replay Viewer Staging', 'text_domain' ), ) // Args
		);
		
	}

	private function add_debug_tab() {
		
		?>
		<aside draggable="true" id="debug_tab" class="info_tab">
			<ul class="tabs" data-tabs id="debug-tabs">
				<li class="tabs-title is-active"><a href="#debug_tab_panel1"
					aria-selected="true">Terran</a></li>
				<li class="tabs-title"><a href="#debug_tab_panel2">Zerg</a></li>
				<li class="tabs-title"><a href="#debug_tab_panel3">Protoss</a></li>
			</ul>
			<div class="tabs-content" data-tabs-content="debug-tabs">
				
				<?php 
				$unit_ids = [[0,1,2,3,5,7,8,9,11,12,13,30,32,34,58,106,107,108,109,110,111,112,113,114,115,116,117,118,120,122,123,124,125],
						[35,37,38,39,40,41,42,43,44,45,46,47,50,59,62,103,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,146,149],
						[60,61,63,64,65,66,67,68,69,70,71,72,73,83,84,85,154,155,156,157,159,160,162,163,164,165,166,167,169,170,171,172]
				];
					for($j = 0; $j < 3; $j++) {
						$active = $j == 0 ? ' is-active' : '';
						echo '<div class="tabs-panel'.$active.'" id="debug_tab_panel'.($j + 1).'">';
						for ($i = 0; $i < sizeof($unit_ids[$j]); $i++) {
							$id = $unit_ids[$j][$i];
							$id_str = $id < 10 ? '0'.$id : $id;
							$id_str = $id < 100 ? '0'.$id_str : $id_str;
							echo '<a href="javascript:create_unit('.$id.')"><img src="http://www.openbw.com/bw/production_icons/icon '.$id_str.'.bmp"/></a>';
							if ($i % 6 == 5) echo '<br/>';
						}
						echo '</div>';
					}
				?>	
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
					<div>
						<div class="info_tab_player_color player_color1"></div>
						<div id="production_tab_content1" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 100; $i++) { 
							echo '<div><img src=""/><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<div>
						<div class="info_tab_player_color player_color2"></div>
						<div id="production_tab_content2" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 100; $i++) { 
							echo '<div><img src=""/><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
				</div>
				<div class="tabs-panel" id="info_tab_panel2">
					<div>
						<div class="info_tab_player_color player_color1"></div>
						<div id="army_tab_content1" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""/><div class="army_counter_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<div>
						<div class="info_tab_player_color player_color2"></div>
						<div id="army_tab_content2" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""/><div class="army_counter_bar"></div></div>';
						}
						?>
						</div>
					</div>
				</div>
				<div class="tabs-panel" id="info_tab_panel3">
					<div>
						<div class="info_tab_player_color player_color1"></div>
						<div id="upgrade_tab_content1" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""/><span></span><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<div>
						<div class="info_tab_player_color player_color2"></div>
						<div id="upgrade_tab_content2" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""><span></span><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
				</div>
				<div class="tabs-panel" id="info_tab_panel4">
					<div>
						<div class="info_tab_player_color player_color1"></div>
						<div id="research_tab_content1" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""/><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
					<div>
						<div class="info_tab_player_color player_color2"></div>
						<div id="research_tab_content2" class="info_tab_content">
						<?php 
						for ($i = 0; $i < 20; $i++) { 
							echo '<div><img src=""><div class="prod_prog_bar"></div></div>';
						}
						?>
						</div>
					</div>
				</div>
			</div> 
		</aside>
		<?php
	}
	
	/**
	 * Display the main content
	 */
	public function show_viewer_ui() {

		wp_register_script( 'info-bar-staging', WP_PLUGIN_URL.'/replay_viewer_staging/info-bar.js', array('jquery'), '1.0', true);
		wp_enqueue_script( 'info-bar-staging' );
		
		wp_register_script( 'start-staging', WP_PLUGIN_URL.'/replay_viewer_staging/start.js', array('info-bar-staging'), '1.0', true);
		wp_enqueue_script( 'start-staging' );
		
		wp_register_script( 'test-staging', WP_PLUGIN_URL.'/replay_viewer_staging/test.js', array('start-staging'), '1.0', true);
		wp_enqueue_script( 'test-staging' );
		
		$rep_url = $_GET['rep'];
		wp_localize_script( 'start-staging', 'ajax_object',
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
				<?php $this->add_debug_tab() ?>
		</div>
		<div class="expanded row align-justify infobar-container">
			<div id="infobar" class="column infobar">
				<div class="row infobar-player">
					<div class="small-1 columns rv_ib_race" id="race1">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick player_color1" id="nick1">-</div>
					<div class="small-2 columns rv_ib_supply" id="supply1">-</div>
					<div class="small-1 columns rv_ib_minerals" id="minerals1">-</div>
					<div class="small-1 columns rv_ib_gas" id="gas1">-</div>
					<div class="small-1 columns rv_ib_worker" id="workers1">-</div>
					<div class="small-1 columns rv_ib_army" id="army1">-</div>
					<div class="small-2 columns rv_ib_apm" id="apm1">-</div>
				</div>
				<div class="row infobar-text">
					<div class="small-1 columns rv_ib_race" id="map_label">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick" id="map">&nbsp;</div>
					<div class="small-2 columns rv_ib_supply">SUPPLY</div>
					<div class="small-1 columns rv_ib_minerals">MINERALS</div>
					<div class="small-1 columns rv_ib_gas">GAS</div>
					<div class="small-1 columns rv_ib_worker">WORKERS</div>
					<div class="small-1 columns rv_ib_army">ARMY</div>
					<div class="small-2 columns rv_ib_apm">APM</div>
				</div>
				<div class="row infobar-player">
					<div class="small-1 columns rv_ib_race" id="race2">&nbsp;</div>
					<div class="small-3 columns rv_ib_nick player_color2" id="nick2">-</div>
					<div class="small-2 columns rv_ib_supply" id="supply2">-</div>
					<div class="small-1 columns rv_ib_minerals" id="minerals2">-</div>
					<div class="small-1 columns rv_ib_gas" id="gas2">-</div>
					<div class="small-1 columns rv_ib_worker" id="workers2">-</div>
					<div class="small-1 columns rv_ib_army" id="army2">-</div>
					<div class="small-2 columns rv_ib_apm" id="apm2">-</div>
				</div>
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
function register_replay_viewer_staging() {
	register_widget( 'Replay_Viewer_Staging' );
}
add_action( 'widgets_init', 'register_replay_viewer_staging' );

function replay_viewer_staging_func($atts, $content = null) {

	// read parameters from tag: ID and Style
	$a = shortcode_atts( array(
			'id' => '0',
			'style' => 'default',
	), $atts );
	$widget_id = $a['id'];
	$widget_style = $a['style'];

	ob_start();
	the_widget('Replay_Viewer_Staging');
	$output = ob_get_clean();
	
	return $output;
}
add_shortcode ( 'replayViewer_Staging', 'replay_viewer_staging_func' );
