$(document).ready(function() {
	
	var counter = 0;
	$(".checkbox").prop('checked', false);
	
	function check_queue() {
		
		$.post( ajax_object.ajax_url, { 
			'action': 'check_queue'
			}, parseQueueCallback, "json");
	}
	
	$('table.display').DataTable( {
	    paging: false,
		responsive: true
	} );


	var interval;
	
	$(".checkbox").change(function() {
	    if(this.checked) {
	        
	    	bot_names.push($(this).attr('id'));
	    	counter++;
	    	console.log(counter + " bots selected. Added " + $(this).attr('id'));
	    	if (counter == 1) {
	    		console.log("start pulling queue.");
	    		check_queue();
	    		interval = window.setInterval(check_queue, 180000);
	    	}
	    } else {
	    	counter--;
	    	console.log("deselected a bot. " + counter + " bots remaining.");
	    	var index = bot_names.indexOf($(this).attr('id'));
	    	if (index > -1) {
	    		bot_names.splice(index, 1);
	    	}
	    	if (counter == 0) {
	    		console.log("stop pulling queue.");
	    		window.clearInterval(interval);
	    	}
	    }
	    console.log("currently tracking: " + bot_names);
	});
});

var bot_names = [];
var jsFileLocation = $('script[src*=sscait]').attr('src');  // the js file path
jsFileLocation = jsFileLocation.replace('sscait.js', 'bell.wav');

var parseQueueCallback = function(response) {
	console.log('getting ajax response: ' + response);
	
	var playSound = false;
	var playerNames = "";
	
	for(var j = 1; j <= 2; j++) {
		var entry = response.body[response.body.length - j];
	    console.log("host: " + entry.host + ", guest: " + entry.guest);
	    for (var i = 0; i < bot_names.length; i++) {
	    	if (bot_names[i].localeCompare(entry.host) == 0) {
	    		playerNames += entry.host + " ";
	    		playSound = true;
	    	}
	    	if (bot_names[i].localeCompare(entry.guest) == 0) {
	    		playerNames += entry.guest + " ";
	    		playSound = true;
	    	}
	    }
	};
	
	if (playSound) {
		console.log('player about to play.');
		if ($('#playerAnnouncement').css('display') == 'none') {
			console.log('playing sound');
			var audio = new Audio(jsFileLocation);
			audio.play();
		}
		$('#playerAnnouncement').html(playerNames + "about to play!");
		$('#playerAnnouncement').css('display', 'block');
	} else {
		$('#playerAnnouncement').css('display', 'none');
	}
};
