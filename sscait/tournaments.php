<?php
defined('ABSPATH') or die('No script kiddies please!');

require('BinaryBeast.php');

function tournaments_func() {

	$bb = new BinaryBeast();
	
	$tournament = $bb->tournament('xBW1701030');
	$tournament->embed();
	
	$teams = $tournament->teams();
	
	ob_start();
	?>
	<table>
		<thead>
			<tr><th>Player</th><th>Race</th><th>Status</th></tr>
		</thead>
		<tbody>
		<?php  foreach($teams as $team) { ?>
			<tr>
				<td><?php echo $team->display_name ?></td>
				<td><?php echo $team->race_id ?></td>
				<td><?php echo BBHelper::translate_team_status($team->status) ?></td>
			</tr>
		<?php  } ?>
		</tbody>
	</table>
	<?php 
	
	$output = ob_get_clean();

	return $output;
}
add_shortcode ( 'tournaments', 'tournaments_func' );
