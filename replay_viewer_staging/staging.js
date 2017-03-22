jQuery(document).ready( function($) {	
	
	$(document).keyup(function(e) {
		 var code = e.keyCode || e.which;
		 
		 switch(code) {
		 case 48: // 0
			 console.log("debug");
			 $('#debug_tab').toggle();
			 break;
		 }
	});
	
	function drag_start(event) {
	    var style = window.getComputedStyle(event.target, null);
	    event.dataTransfer.setData("text/plain", event.target.id + ',' +
	    (parseInt(style.getPropertyValue("left"),10) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top"),10) - event.clientY));
	} 
	
	document.getElementById('debug_tab').addEventListener('dragstart',drag_start,false);
	
})

var IMG_URL1 = "http://www.openbw.com/bw/production_icons/hd/icon ";
var IMG_URL2 = ".png";

// var debug_unit_id = null;
function create_unit(unit_type) {
	
	// TODO insert call to test.js here: 
	// if (debug_unit_id != null) {
	// 		kill_unit(debug_unit_id);
	// }
	// debug_unit_id = create_unit(unit_type, pos_x, pos_y);
	console.log('created unit type ' + unit_type);
}
