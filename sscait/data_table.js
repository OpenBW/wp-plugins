var bw_unit_data = JSON.parse($('#unit_data_raw').text());

$(document).ready(function() {
	
    $('#checkbox_all').change(function() {
        
        var nodes = document.getElementById('column_selector').getElementsByTagName("input");
        for (var i = 1; i <= nodes.length; i++) {
        	$('#checkbox' + i).prop('checked', $('#checkbox_all').prop('checked'));
        	$('#checkbox' + i).trigger('change');
        }
    });
    
    $('#column_selector > input').change(function() {
        
    	var id = this.id.replace('checkbox', '');
    	if ($(this).prop('checked')) {
	    	$('#data_table > thead > tr > th:nth-child(' + id + ')').show();
	    	$('#data_table > tbody > tr > td:nth-child(' + id + ')').show();
    	} else {
    		$('#data_table > thead > tr > th:nth-child(' + id + ')').hide();
	    	$('#data_table > tbody > tr > td:nth-child(' + id + ')').hide();
    	}
    });
    
    for (var i = 0; i < bw_unit_data.length; i++) {
    	for (var j = 0; j < bw_unit_data[i].length - 10; j++) {
    		
    		var content = bw_unit_data[i][j];
    		if (content == null || content == '') {
    			content = ' - ';
    		} else if (j == 6) {
    			content /= 2;
    		}
    		
    		var element = $('#data_table > tbody > tr:nth-child(' + (i+1) + ') > td:nth-child(' + (j+1) + ')');
    		element.html(content);
    	}

    	var offset = bw_unit_data[i].length - 10;
    	
    	// only fill in a value if a base damage is defined
    	if (bw_unit_data[i][offset] != "") {
    		var gw_dmg = bw_unit_data[i][offset];
    		
    		// if the multiplier factor is > 1 then write it explicitely, else leave it out
    		if (bw_unit_data[i][offset+2] * bw_unit_data[i][offset+3] > 1) {
    			gw_dmg = bw_unit_data[i][offset+2] * bw_unit_data[i][offset+3] + 'x' + gw_dmg;
    		}
    		
    		// if there is a bonus damage > 0 then write it explicitely, else leave it out
    		if (bw_unit_data[i][offset+1] != null && bw_unit_data[i][offset+1] > 0) {
    			gw_dmg += ' (+' + bw_unit_data[i][offset+1] + ')';
    		}
    		$('#data_table > tbody > tr:nth-child(' + (i+1) + ') > td:nth-child(15)').html(gw_dmg);
    		var gw_dps = bw_unit_data[i][offset+2] * bw_unit_data[i][offset+3] * bw_unit_data[i][offset] / bw_unit_data[i][offset+4];
    		
    		// show DPS only if it is a number and not a one-time dmg unit
    		if (!isNaN(gw_dps) && i != 7 && i != 14 && i != 24 && i != 45 && i != 46) {
    			$('#data_table > tbody > tr:nth-child(' + (i+1) + ') > td:nth-child(16)').html((gw_dps * 1000 / 42).toFixed(3));
    		}
    	}
    	
    	if (bw_unit_data[i][offset+5] != "") {
    		var aw_dmg = bw_unit_data[i][offset+5];
    		if (bw_unit_data[i][offset+7] * bw_unit_data[i][offset+8] > 1) {
    			aw_dmg = bw_unit_data[i][offset+7] * bw_unit_data[i][offset+8] + 'x' + aw_dmg;
    		}
    		if (bw_unit_data[i][offset+6] != null && bw_unit_data[i][offset+6] > 0) {
    			aw_dmg += ' (+' + bw_unit_data[i][offset+6] + ')';
    		}
    		$('#data_table > tbody > tr:nth-child(' + (i+1) + ') > td:nth-child(19)').html(aw_dmg);
    		var aw_dps = bw_unit_data[i][offset+7] * bw_unit_data[i][offset+8] * bw_unit_data[i][offset+5] / bw_unit_data[i][offset+9];
    		if (!isNaN(aw_dps) && i != 31) {
    			$('#data_table > tbody > tr:nth-child(' + (i+1) + ') > td:nth-child(20)').html((aw_dps * 1000 / 42).toFixed(3));
    		}
    	}
    }
    
    $('#checkbox_all').prop('checked', true);
    $('#checkbox_all').trigger('change');
    
    $('table.display').DataTable( {
	    paging: false,
		responsive: true
	} );
} );
