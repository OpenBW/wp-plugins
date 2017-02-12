<?php
defined('ABSPATH') or die('No script kiddies please!');

function data_table_func() {

	wp_enqueue_style( 'table-stylesheet', 'https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css', array(), '1.10.13', 'all' );
	wp_enqueue_style( 'resp-table-stylesheet', 'https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css', array('table-stylesheet'), '1.10.13', 'all' );
	
	wp_register_script( 'datatables', 'https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js', array('jquery'), '1.10.13', true);
	wp_enqueue_script( 'datatables' );
	wp_register_script( 'resp-datatables', 'https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js', array('datatables'), '1.10.13', true);
	wp_enqueue_script( 'resp-datatables' );
	
	wp_register_script( 'datatables-foundation', 'https://cdn.datatables.net/1.10.13/js/dataTables.foundation.min.js', array('datatables'), '1.10.13', true);
	wp_enqueue_script( 'datatables-foundation' );
	wp_register_script( 'resp-datatables-foundation', 'https://cdn.datatables.net/responsive/2.1.0/js/responsive.foundation.min.js', array('resp-datatables'), '1.10.13', true);
	wp_enqueue_script( 'resp-datatables-foundation' );
	
	wp_register_script( 'data_table', WP_PLUGIN_URL.'/sscait/data_table.js', array(), '1.0', true);
	wp_enqueue_script( 'data_table' );
	
	$header_array = ["Race", "Name", "Size", "Minerals", "Gas", "Build Time", "Supply", "HP", "Shields", "Energy", "Speed", "Acceleration", "Sight", "Ground Weapon", "GW Damage", "GW DPS", "GW Range", "Air Weapon", "AW Damage", "AW DPS", "AW Range", "Armor", "Upgrades", "Spells", "Build Score", "Destroy Score", "GW Base Dmg", "GW Bonus", "GW Factor", "GW Attacks", "GW CoolDown", "AW Base Dmg", "AW Bonus", "AW Factor", "AW Attacks", "AW Cooldown"];
	
	ob_start();
	
	?>
	
	<div id="top" class="callout secondary">	 	 
		<h3>Brood War Data Table</h3>
		<p>All the stats for all the units of the original Brood War game. Brought to you by ScorpionJack.</p>
			<fieldset>
				<legend>Check / uncheck the boxes to show / hide columns.</legend>
				<input id="checkbox_all" type="checkbox"><label for="checkbox_all">Check / uncheck all</label>
				<br/>
				<span id="column_selector">
					<?php for ($i = 1; $i <= sizeof($header_array) - 10; $i++) { ?>
					<input id="checkbox<?php echo $i ?>" type="checkbox"><label for="checkbox<?php echo $i ?>"><?php echo $header_array[$i - 1] ?></label>
					<?php } ?>
				</span>
			</fieldset>

	<table id="data_table" class="display" width="100%" cellspacing="0">
		<thead>
			<tr>
			<?php for ($i = 0; $i < sizeof($header_array) - 10; $i++) { ?>
				<th><?php echo $header_array[$i] ?></th>
			<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php for ($j = 0; $j < 50; $j++) { ?>
				<tr>
				<?php for ($i = 0; $i < sizeof($header_array) - 10; $i++) { ?>
					<td></td>
				<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php 
	$output = ob_get_clean();

	return $output;
}
add_shortcode ( 'dataTable', 'data_table_func' );
