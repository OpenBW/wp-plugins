jQuery(document).ready( function($) {	
	
	$(document).keyup(function(e) {
		 var code = e.keyCode || e.which;
		 
		 switch(code) {
		 case 32: // space
		 case 80: // p
			 toggle_pause();
			 break;
		 case 65: // a
		 case 85: // u
			 play_faster();
			 break;
		 case 90: // z
		 case 68: // d
			 play_slower();
			 break;
		 case 83:
			 toggle_sound();
			 break;
		 case 8: // backspace
			 jump_back(10);
			 break;
		 case 72: // h
			 $('#quick_help').foundation('open');
			 break;
		 case 49: // 1
			 toggle_info_tab(1);
			 break;
		 case 50: // 2
			 toggle_info_tab(2);
			 break;
		 case 51: // 3
			 toggle_info_tab(3);
			 break;
		 case 52: // 4
			 toggle_info_tab(4);
			 break;
		 case 48: // 0
			 $('#debug_tab').toggle();
			 break;
		 }
	});
	
	$('#game-slider-handle').mousedown(function(){
	    isDown = true;
	});

	$(document).mouseup(function(){
	    if(isDown){
	        isDown = false;
	    }
	}); 
	
	$(window).on('resize', function(){
		document.getElementById("canvas").innerWidth = window.innerWidth;
		document.getElementById("canvas").innerHeight = window.innerHeight - 147;
	});
	
	$('#game-slider').on('moved.zf.slider', function() {
		if (isDown) {
			_replay_set_value(6, document.getElementById("sliderOutput").value / 200);
		}
	});
	
	$('#rv-rc-play').on('click', function() {
		
		toggle_pause();
	});
	
	$('#rv-rc-sound').on('click', function() {
		
		toggle_sound();
	});
	
	$('#rv-rc-faster').on('click', function() {
		
		play_faster();
	});
	
	$('#rv-rc-slower').on('click', function() {
		
		play_slower();
	});
	
	$('#rv-rc-sound').mouseenter(function() {
		$('#volume-slider-wrapper').css("display", "block");
	});
	$('.volume').mouseleave(function() {
	    $('#volume-slider-wrapper').css("display", "none");
	});
	$('#volume-slider').on('moved.zf.slider', function() {
		volume_index = document.getElementById("volumeOutput").value / 100;
		if (volume_index > 0) {
			$('#rv-rc-sound').addClass('rv-rc-sound');
			$('#rv-rc-sound').removeClass('rv-rc-muted');
		} else {
			$('#rv-rc-sound').removeClass('rv-rc-sound');
			$('#rv-rc-sound').addClass('rv-rc-muted');
		}
		Module.set_volume(volume_index);
	});
	
	function drag_start(event) {
	    var style = window.getComputedStyle(event.target, null);
	    event.dataTransfer.setData("text/plain", event.target.id + ',' +
	    (parseInt(style.getPropertyValue("left"),10) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top"),10) - event.clientY));
	} 
	
	function drop(event) {
	    var parameters = event.dataTransfer.getData("text/plain").split(',');
	    var dm = document.getElementById(parameters[0]);
	    dm.style.left = (event.clientX + parseInt(parameters[1],10)) + 'px';
	    dm.style.top = (event.clientY + parseInt(parameters[2],10)) + 'px';
	    event.preventDefault();
	    return false;
	}
	
	document.getElementById('info_tab').addEventListener('dragstart',drag_start,false);
	document.getElementById('debug_tab').addEventListener('dragstart',drag_start,false);
	document.getElementById("canvas").addEventListener('drop', drop, false);
	update_army_tab([]);
	$('#volume-slider-wrapper').css("display", "none");
})	

function toggle_info_tab(tab_nr) {
	
	 if ($('#info_tab').is(":visible")) {
		 
		 if ($('#info_tab_panel' + tab_nr).hasClass("is-active")) {
			 $('#info_tab').toggle();
		 } else {
			 $('#tab_link' + tab_nr).click();
		 }
		 
	 } else {
		 $('#info_tab').toggle();
		 $('#tab_link' + tab_nr).click();
	 }
}

function jump_back(seconds) {
	
	var frame = Math.max(0, _replay_get_value(2) - 24 * seconds);
	_replay_set_value(3, frame);
}

function play_faster() {
	
	var current_speed = _replay_get_value(0);
	if (current_speed < 1024) {
		_replay_set_value(0, current_speed * 2);
	}
}

function play_slower() {
	
	var current_speed = _replay_get_value(0);
	_replay_set_value(0, current_speed / 2);
}

var volume_index;
function toggle_sound() {
	
	$('#rv-rc-sound').toggleClass('rv-rc-sound');
	$('#rv-rc-sound').toggleClass('rv-rc-muted');
	
	if ($('#rv-rc-sound').hasClass('rv-rc-sound')) {
		Module.set_volume(volume_index);
	} else {
		Module.set_volume(0);
	}
}

function toggle_pause() {
	
	$('#rv-rc-play').toggleClass('rv-rc-play');
	$('#rv-rc-play').toggleClass('rv-rc-pause');
	
	update_info_tab();
	
	_replay_set_value(1, (_replay_get_value(1) + 1)%2);
}

function update_speed(speed) {
	
	document.getElementById("rv-rc-speed").innerHTML = "speed: " + speed + "x";
}

function get_image_src(id) {

    return "http://www.openbw.com/bw/production_icons/hd/icon " + id + ".png";
}

function set_icon(tab_nr, parent_element, child_nr, icon_id, percentage, info) {
	
	var img_src = "http://www.openbw.com/bw/production_icons/hd/icon " + icon_id + ".png";
	var element = parent_element.children("div").eq(child_nr);
	var img_element = element.children("img");
	
	if (img_element.attr("src").localeCompare(img_src) != 0) {
		img_element.attr("src", img_src);
	}
	if (tab_nr == 2) {
		element.children("div").html(info);
	} else {
		element.children("div").css("width", Math.round(percentage * 36) + "px");
	}
	if (tab_nr == 3) {
		element.children("span").html(info);
	}
	element.css("display", "inline-block");
}

function clear_icon(parent_element, child_nr) {
	
	var element = parent_element.children("div").eq(child_nr).hide();
}

function update_army_tab(complete_units) {
	
	var unit_types = [[], [], [], [], [], [], [], [], [], [], [], []];
	for (var i = 0; i != complete_units.length; ++i) {
		
		var unit = complete_units[i];
		var type = unit.unit_type().id;
		if (type < 106 && type != 7 && type != 41 && type != 64) {
			
			// tank siege mode hack (assign id for tank tank mode)
			if (type == 30) {
				type = 5;
			}
			
			if (type in unit_types[unit.owner]) {
				unit_types[unit.owner][type] += 1;
			} else {
				unit_types[unit.owner][type] = 1;
			}
		}
	}
	
	var element;
    for (var i = 0; i < players.length; ++i) {
        
    	var type_count = 0;
    	element = $('#army_tab_content' + (i + 1));
    	for (type in unit_types[players[i]]) {
			
			var u_nr = type < 10 ? "0" + type : type;
			if (type < 100) u_nr = "0" + u_nr;
			
			var count = unit_types[players[i]][type];
			
			set_icon(2, element, type_count, u_nr, 1, count);
			++type_count;
		}
    	for (var j = type_count; j < 20; j++) {
    		clear_icon(element, j);
    	}
    }
}

var relevant_research = [0,1,2,3,5,7,8,9,10,11,13,15,16,17,19,20,21,22,24,25,27,30,31,32];
var unused_research = [4, 6, 12, 14, 18, 23, 26, 28, 29, 33, 34];

function update_research_tab(researches) {
	
	var element;
	for (var i = 0; i < researches.length; i++) {
		
		element = $('#research_tab_content' + (i+1));
		var upgrade_count = 1;
		var complete = researches[i][1];
		var index = 0;
		for (var j = 0; j < complete.length; j++) {
			
			if ($.inArray(complete[j].id, unused_research) == -1) {
				set_icon(4, element, index, complete[j].icon, 1, null);
				index++;
			}
		}
		
		var incomplete = researches[i][2];
		for (var j = 0; j < incomplete.length; j++) {
			
			var build_percentage = 1 - incomplete[j].remaining_time / incomplete[j].total_time;
			set_icon(4, element, j + index, incomplete[j].icon, build_percentage, null);
		}
		
		 //clear the unused spots
	    for (var j = incomplete.length + index; j < 20; ++j) {
	    	clear_icon(element, j);
	    }
	}
}

function update_upgrades_tab(upgrades) {
	
	var element;
	for (var i = 0; i < upgrades.length; i++) {
		
		var upgrade_count = 1;
		var complete = upgrades[i][1];
		element = $('#upgrade_tab_content' + (i+1));
		
		for (var j = 0; j < complete.length; j++) {
			
			set_icon(3, element, j, complete[j].icon, 1, complete[j].level);
		}
		
		var incomplete = upgrades[i][2];
		for (var j = 0; j < incomplete.length; j++) {
			
			var build_percentage = 1 - incomplete[j].remaining_time / incomplete[j].total_time;
			set_icon(3, element, j + complete.length, incomplete[j].icon, build_percentage, incomplete[j].level);
		}
		
		 //clear the unused spots
	    for (var j = complete.length + incomplete.length; j < 20; ++j) {
	    	clear_icon(element, j);
	    }
	}
}

function update_production_tab(incomplete_units) {
	
	var unit_names = [[], [], [], [], [], [], [], [], [], [], [], []];
	
	for (var i = 0; i != incomplete_units.length; ++i) {
		var u = incomplete_units[i];
		var t;
		var build_time;
		if (u.build_type()) {
			t = u.build_type().id;
			build_time = u.build_type().build_time;
			
		} else {
			t = u.unit_type().id;
			build_time = u.unit_type().build_time;
		}
		
		var u_nr = t < 10 ? "0" + t : t;
		if (t < 100) u_nr = "0" + u_nr;
		var build_percentage = 1 - u.remaining_build_time / build_time;
		
		unit_names[u.owner].push([u_nr, build_percentage]);
	}
	
	var element;
    for (var i = 0; i < players.length; ++i) {
        
    	element = $('#production_tab_content' + (i + 1));
    	
    	//fill the spots with all units in production for current player
	    for (var j = 0; j != unit_names[players[i]].length; ++j) {
	    	
	    	set_icon(1, element, j, unit_names[players[i]][j][0], unit_names[players[i]][j][1], null);
	    }
	    
	    //clear the unused spots
	    for (var j = unit_names[players[i]].length; j < 100; ++j) {
	    	clear_icon(element, j);
	    }
    }
}

function update_timer(sec_num) {
	
	sec_num = sec_num  * 42 / 1000;
	var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = Math.floor(sec_num - (hours * 3600) - (minutes * 60));

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    
    var time = minutes+':'+seconds;
    if (hours > 0) {
    	time = hours + ':' + time;
    }
	document.getElementById("rv-rc-timer").innerHTML = "time: " + time;
}

var isDown = false;

function update_handle_position(value) {
	
	if (!isDown) {
		document.getElementById("sliderOutput").value = value;
		$('#sliderOutput').trigger("change");
	}
}

function set_map_name(name) {
	document.getElementById("map").innerHTML = name;
}

function set_color(player, color) {
		
	var hex_color;
	switch(color) {
	case 0:
		hex_color = "#f40404";
		break;
	case 1:
		hex_color = "#0c48cc";
		break;
	case 2:
		hex_color = "#2cb494";
		break;
	case 3:
		hex_color = "#88409c";
		break;
	case 4:
		hex_color = "#f88c14";
		break;
	case 5:
		hex_color = "#703014";
		break;
	case 6:
		hex_color = "#cce0d0";
		break;
	case 7:
		hex_color = "#fcfc38";
		break;
	case 8:
		hex_color = "#088008";
		break;
	case 9:
		hex_color = "#fcfc7c";
		break;
	case 10:
		hex_color = "#ecc4b0";
		break;
	case 11:
		hex_color = "#4068d4";
		break;
	}
	$('.player_color' + player).css('background-color', hex_color);
}

function set_nick(player, nick) {
	document.getElementById("nick" + player).innerHTML = nick;
}

function set_supply(player, supply) {
	document.getElementById("supply" + player).innerHTML = supply;
}

function set_minerals(player, minerals) {
	document.getElementById("minerals" + player).innerHTML = minerals;
}

function set_gas(player, gas) {
	document.getElementById("gas" + player).innerHTML = gas;
}

function set_workers(player, workers) {
	document.getElementById("workers" + player).innerHTML = workers;
}

function set_army(player, army) {
	document.getElementById("army" + player).innerHTML = army;
}

var player_race_cache  = [-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1];
function set_race(player, race) {
	
	if (player_race_cache[player] != race) {
		
		player_race_cache[player] = race;
		var race_name;
		if (race == 0) {
			race_name = "zerg";
		} else if (race == 1) {
			race_name = "terran";
		} else if (race == 2) {
			race_name = "protoss";
		}
		console.log("setting race emblem for player " + player);
		$('#race' + player).css("background-image", "url('http://www.openbw.com/wp-content/uploads/2017/01/" + race_name + "_emblem2.png')");
	}
}

function set_apm(player, apm) {
	document.getElementById("apm" + player).innerHTML = apm;
}

// var debug_unit_id = null;
function create_unit(unit_type) {
	
	// TODO insert call to test.js here: 
	// if (debug_unit_id != null) {
	// 		kill_unit(debug_unit_id);
	// }
	// debug_unit_id = create_unit(unit_type, pos_x, pos_y);
	console.log('created unit type ' + unit_type);
}

