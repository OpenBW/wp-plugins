<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once 'Unirest.php';
require_once 'data_table.php';
require_once 'config.php';

/**
 Plugin Name: OpenBW SSCAIT Widget
 Plugin URI: http://www.openbw.com
 Description: OpenBW SSCAIT Widget with integrated replay viewing.
 Author: IMP
 Version: 1.0
 Author URI: http://www.openbw.com
 */
class Sscait_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Sscait_Widget', // Base ID
			__( 'SSCAIT Widget', 'text_domain' ), // Name
			array( 'description' => __( 'SSCAIT bots and games listing', 'text_domain' ), ) // Args
		);
		
	}

	/**
	 * Display the main content.
	 */
	public function show_listing() {
		
		wp_enqueue_style( 'table-stylesheet', 'https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css', array(), '1.10.13', 'all' );
		
		wp_register_script( 'datatables', 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js', array('jquery'), '1.10.13', true);
		wp_enqueue_script( 'datatables' );
		wp_register_script( 'datatables-foundation', 'https://cdn.datatables.net/1.10.13/js/dataTables.foundation.min.js', array('datatables'), '1.10.13', true);
		wp_enqueue_script( 'datatables-foundation' );
		wp_register_script( 'sscait', WP_PLUGIN_URL.'/sscait/sscait.js', array('datatables-foundation'), '1.0', true);
		wp_enqueue_script( 'sscait' );
		
		wp_localize_script( 'sscait', 'ajax_object', array(
				'ajax_url' 			=> admin_url( 'admin-ajax.php' )
		) );
		
		$playerlist = Unirest\Request::get("https://certicky-sscait-student-starcraft-ai-tournament-v1.p.mashape.com/api/bots.php",
				array(
						"X-Mashape-Key" => SSCAIT_KEY,
						"Accept" => "application/json"
				)
				);
		
		$gamelist = Unirest\Request::get("https://certicky-sscait-student-starcraft-ai-tournament-v1.p.mashape.com/api/games.php?future=false&count=200",
				array(
						"X-Mashape-Key" => SSCAIT_KEY,
						"Accept" => "application/json"
				)
				);
		
		$playerarray = json_decode($playerlist->raw_body, true);
		$gamearray = json_decode($gamelist->raw_body, true);
		
		?>
		<span>Check the checkbox of a player to be alerted via sound and callout once the player is about to play on <a href="https://www.twitch.tv/certicky">stream</a>!</span>
		<div id="playerAnnouncement" class="callout warning" style="display: none"></div>
		<ul class="tabs" data-tabs id="sscait-tabs">
		  <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Players</a></li>
		  <li class="tabs-title"><a href="#panel2">Replays</a></li>
		</ul>
		<div class="tabs-content" style="width: 100%" data-tabs-content="sscait-tabs">
		
			<div class="tabs-panel is-active" id="panel1">
				<table id="playertable" class="display" width="100%" cellspacing="0">
					<thead>
						<tr><th>Name</th><th>Race</th><th>Win</th><th>Loss</th><th>Last Update</th><th>Recent Replays</th></tr>
					</thead>
					<tbody>
					<?php 
					$entry_counter = 0;
					foreach ($playerarray as $entry) {
						if ($entry["status"] == "Enabled") {
							$entry_counter++;
							
							?>
							<tr>
								<td><input class="checkbox" id="<?php echo $entry["name"]?>" type="checkbox">
									<a data-toggle="name-dropdown<?php echo $entry_counter?>"><?php echo $entry["name"]?></a>
									<div class="dropdown-pane" id="name-dropdown<?php echo $entry_counter?>" data-dropdown>
									  <?php echo $entry["description"]?>
									</div>
								</td>
								<td><?php echo $entry["race"]?></td>
								<td><?php echo $entry["wins"]?></td>
								<td><?php echo $entry["losses"]?></td>
								<td><?php echo $entry["update"]?></td>
								<td>
									<?php 
									$rep_counter = 0;
									$replay_list = "";
									foreach ($gamearray as $game) {
										if ($game["replay"] != '' &&
												($game["host"] === $entry["name"] || $game["guest"] === $entry["name"])) {
													
											$rep_counter++;
											$replay_list = $replay_list."<a href='../replay-viewer/?rep=".urlencode($game["replay"])."'>".$game["host"]." vs. ".$game["guest"]."</a></br>";//." on ".$game["map"];
										}
									}
									
									if ($rep_counter == 0) {
										echo "no replays";
									} else {
									?>
										<a class="button" data-toggle="replay-dropdown<?php echo $entry_counter; ?>">Replays</a>
										<div class="dropdown-pane" id="replay-dropdown<?php echo $entry_counter; ?>" data-dropdown>
											<?php echo $replay_list; ?>
										</div>
								<?php } ?>
								</td>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
			
			<div class="tabs-panel" id="panel2">
				<table id="replaytable" class="display" width="100%" cellspacing="0">
					<thead>
						<tr><th>Players</th><th>Map</th><th>Timestamp</th><th>Winner</th><th>Replay</th></tr>
					</thead>
					<tbody>
						<?php 
						foreach ($gamearray as $game) {
							if ($game["replay"] != '') {
								?>
								<tr>
									<td><?php echo $game["host"]; ?> vs. <?php echo $game["guest"]; ?></td>
									<td><?php echo substr($game["map"], 11); ?></td>
									<td><?php echo date('Y-m-d H:i:s', $game["timestamp"]); ?></td>
									<td><?php echo $game["result"] == '1' ? $game["host"] : $game["guest"]; ?></td>
									<td><a class="button" href="../replay-viewer/?rep=<?php echo urlencode($game["replay"]); ?>">Watch</a></td>
								</tr>
					<?php	}
						}
						
						?>
					</tbody>
				</table>
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
		
		$this->show_listing ();
		
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
function register_sscait_widget() {
    register_widget( 'Sscait_Widget' );
}
add_action( 'widgets_init', 'register_sscait_widget' );

function sscait_func($atts, $content = null) {

	// read parameters from tag: ID and Style
	$a = shortcode_atts( array(
			'id' => '0',
			'style' => 'default',
	), $atts );
	$widget_id = $a['id'];
	$widget_style = $a['style'];

	ob_start();
	the_widget('Sscait_Widget');
	$output = ob_get_clean();
	
	return $output;
}
add_shortcode ( 'sscaitListings', 'sscait_func' );

/**
 * Ajax callback to process answer and create a new DRPM item.
 */
function check_queue_func() {

	$gamelist = file_get_contents("https://cachedsscaitscores.krasi0.com/nextGames/nextGames.json");

	echo json_encode($gamelist);
	wp_die();
}
add_action('wp_ajax_nopriv_check_queue', 'check_queue_func');
add_action('wp_ajax_check_queue', 'check_queue_func');

