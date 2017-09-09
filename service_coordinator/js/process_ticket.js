function assign_ticket(){
    engineer_id = $('#assign_engineer_id').val();
    ticket_id = $('#h_ticket_id').val();
    visit_date = $('#visit_date').val();
	data = 'ticket_id='+ticket_id+'&engineer_id='+engineer_id+'&visit_date='+visit_date+'&action=assign_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
				$('#assign_engineer_div').hide();
				$('#assigned_info').html('Assigned to '+result.engineer_name+' to visit on '+visit_date);
				$('#h_engr_id').val(engineer_id);
				$('#reassign_engineer_div').show();
				$('#request_spare_nope_div').hide();
				$('#request_spare_div').show();
				$('#requested_spare_list_div').show();
				refresh_ticket_list();
			}else{
				bootbox.alert(result.message);
				//$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
			}
		},
		error: function(){}             
	});
    
}

function reassign_ticket(){
    engineer_id = $('#assign_engineer_id_re').val();
    ticket_id = $('#h_ticket_id').val();
    visit_date = $('#visit_date_re').val();
	data = 'ticket_id='+ticket_id+'&engineer_id='+engineer_id+'&visit_date='+visit_date+'&action=assign_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
				$('#assigned_info').html('Assigned to '+result.engineer_name+' to visit on '+visit_date);
				$('#h_engr_id').val(engineer_id);
			}else{
				bootbox.alert(result.message);
				//$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
			}
		},
		error: function(){}             
	});
    
}

function sc_close_ticket(){
    ticket_id = $('#h_ticket_id').val();
    remarks = $('#closing_remarks').val();
	data = 'ticket_id='+ticket_id+'&remarks='+remarks+'&action=sc_close_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				$('#li_engineer').hide();
				$('#li_closeticket').hide();
				$('#tab_closeticket').removeClass('active');
				$('#reassign_engineer_div').hide();
				$('#assign_engineer_div').hide();
				$('#request_spare_nope_div').hide();
				$('#request_spare_div').hide();
				$('#requested_spare_list_div').show();
				$('#li_spares').addClass('active');
				$('#tab_spares').addClass('active');
				refresh_ticket_list();
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});    
}

function status_change(){
    ticket_status = $('#ticket_status').val();
    if(ticket_status == 'assigned'){
    	$('#daterange_div').hide();
    	$('#pendingdate_div').show();
    }else{
    	$('#pendingdate_div').hide();
    	$('#daterange_div').show();
    }
    //window.location = 'process_ticket.php?ticket_status='+ticket_status;
}

function list_tickets(){
	ticket_status = $('#ticket_status').val();
    if(ticket_status == 'assigned'){
    	pending_days = $('#pending_days').val();
    	window.location = 'process_ticket.php?ticket_status='+ticket_status+'&pending_days='+pending_days;
    }else{
    	from_date = $('#from_date').val();
    	to_date = $('#to_date').val();
    	window.location = 'process_ticket.php?ticket_status='+ticket_status+'&from_date='+from_date+'&to_date='+to_date;
    }
}

function view_ticket_detail(ticket_id){
	show_customer_detail(ticket_id);
	remove_active_tabs();
	switch(g_ticket_status){
		case 'created':
			show_loader();
			loaddata_unassigned(ticket_id);
			$('#assign_engineer_div').show();
			$('#reassign_engineer_div').hide();
			$('#request_spare_nope_div').show();
			$('#request_spare_div').hide();
			$('#requested_spare_list_div').hide();
			$('#li_engineer').addClass('active');
			$('#tab_engineer').addClass('active');
		break;
		case 'assigned':
			show_loader();
			loaddata_assigned(ticket_id);
			$('#reassign_engineer_div').show();
			$('#assign_engineer_div').hide();
			$('#request_spare_nope_div').hide();
			$('#request_spare_div').show();
			$('#requested_spare_list_div').show();
			$('#li_spares').addClass('active');
			$('#tab_spares').addClass('active');
		break;
		case 'engineerclosed':
			show_loader();
			loaddata_engineerclosed(ticket_id);
			$('#reassign_engineer_div').hide();
			$('#assign_engineer_div').hide();
			$('#request_spare_nope_div').hide();
			$('#request_spare_div').hide();
			$('#requested_spare_list_div').show();
			$('#li_callreport').addClass('active');
			$('#tab_callreport').addClass('active');
		break;
		case 'closed':
			show_loader();
			loaddata_engineerclosed(ticket_id);
			$('#li_closeticket').hide();
			$('#reassign_engineer_div').hide();
			$('#assign_engineer_div').hide();
			$('#request_spare_nope_div').hide();
			$('#request_spare_div').hide();
			$('#requested_spare_list_div').show();
			$('#li_callreport').addClass('active');
			$('#tab_callreport').addClass('active');
		break;
		case 'sparespending':
			show_loader();
			loaddata_sparespending(ticket_id);
			$('#reassign_engineer_div').show();
			$('#assign_engineer_div').hide();
			$('#request_spare_nope_div').hide();
			$('#request_spare_div').show();
			$('#requested_spare_list_div').show();
			$('#li_spares').addClass('active');
			$('#tab_spares').addClass('active');
		break;
	}
	$('#process_ticket_modal').modal('show');
	//console.log('Ticket id :'+ticket_id);
	$('#h_ticket_id').val(ticket_id);
	$('#ticket_id_h4').html('Ticket ID : '+ticket_id);
}

gc=2;
function add_another_row(){
    template = $('#tr_spare_template').html();
    template2 = template.replace('spare_name_1','spare_name_'+gc)
    					.replace('engineer_stock_1','engineer_stock_'+gc)
    					.replace('store_stock_1','store_stock_'+gc)
    					.replace('quantity_1','quantity_'+gc)
    					.replace('change_spare(1)','change_spare('+gc+')');
    $('#tbody_spare').append('<tr id="tr_'+gc+'">'+template2+'<td><button type="button" class="btn btn-danger" onclick="remove_row(\''+gc+'\');">Remove </button></td></tr>');
    $('#engineer_stock_'+gc).html('');
    $('#store_stock_'+gc).html('');
    $('#quantity_'+gc).val('');
    gc++;
}

function remove_row(rowid){
	$('#tr_'+rowid).remove();
}

function change_spare(counter){
	item_master_id = $('#spare_name_'+counter).val();
	engineer_id = $('#h_engr_id').val();
	data = 'item_master_id='+item_master_id+'&engineer_id='+engineer_id+'&action=fetch_material_stock';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			hide_loader();
			if(result.infocode == "DATALOADED"){
				$('#engineer_stock_'+counter).html(result.engineer_stock);
				$('#store_stock_'+counter).html(result.store_stock);
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){
			hide_loader();
		}             
	});
}

function loaddata_unassigned(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_unassigned';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			hide_loader();
			if(result.infocode == "DATALOADED"){
				display_asset_history(result.asset_history);
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){
			hide_loader();
		}             
	});
}

function loaddata_assigned(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_assigned';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			hide_loader();
			if(result.infocode == "DATALOADED"){
				//console.log(result);
				$('#assigned_info').html('Assigned to '+result.engineer_data.employee_name+' to visit on '+result.engineer_data.visit_date);
				$('#h_engr_id').val(result.engineer_data.employee_master_id);
				display_spare_details(result.spare_data);
				display_asset_history(result.asset_history);
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){
			hide_loader();
		}             
	});
}

function loaddata_engineerclosed(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_engineerclosed';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			hide_loader();
			if(result.infocode == "DATALOADED"){
				//console.log(result);
				//$('#assigned_info').html('Assigned to '+result.engineer_data.employee_name+' to visit on '+result.engineer_data.visit_date);
				display_spare_details(result.spare_data);
				$('#requested_spare_list_div').show();
				if(!($.isEmptyObject(result.call_report))){
					op2 = '<label> Opening Reading : '+result.call_report.opening_reading;
					op2 += '<label> Closing Reading : '+result.call_report.closing_reading;
					op2 += '<label> Visit Date : '+result.call_report.visit_date;
					op2 += '<label> Start Time : '+result.call_report.start_time;
					op2 += '<label> End Time : '+result.call_report.end_time;
					op2 += '<label> Remarks : '+result.call_report.remarks;
				}else{
					op2 = "Call report details unavailable";
				}
				$('#show_call_report_div').html(op2);
				$('#show_call_report_div').show();
				display_asset_history(result.asset_history);
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){
			hide_loader();
		}             
	});
	$('#requested_spare_list_div').show();
	$('#li_engineer').hide();
	$('#li_callreport').show().addClass('active');
	$('#tab_callreport').addClass('active');
}

function loaddata_sparespending(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_sparespending';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		async: false,
		success: function(result){
			hide_loader();
			if(result.infocode == "DATALOADED"){
				//console.log(result);
				$('#assigned_info').html('Assigned to '+result.engineer_data.employee_name+' to visit on '+result.engineer_data.visit_date);
				$('#h_engr_id').val(result.engineer_data.employee_master_id);
				display_spare_details(result.spare_data);
				$('#requested_spare_list_div').show();
				if(!($.isEmptyObject(result.call_report))){
					op2 = '<label> Opening Reading : '+result.call_report.opening_reading;
					op2 += '<label> Closing Reading : '+result.call_report.closing_reading;
					op2 += '<label> Visit Date : '+result.call_report.visit_date;
					op2 += '<label> Start Time : '+result.call_report.start_time;
					op2 += '<label> End Time : '+result.call_report.end_time;
					op2 += '<label> Remarks : '+result.call_report.remarks;
				}else{
					op2 = "Call report details unavailable";
				}
				$('#show_call_report_div').html(op2);
				$('#show_call_report_div').show();
				display_asset_history(result.asset_history);
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){
			hide_loader();
		}             
	});
	$('#requested_spare_list_div').show();
	$('#li_callreport').show();
	//$('#tab_callreport').addClass('active');
}

function request_spare(){
	data = $('#request_spare_form').serialize();
    ticket_id = $('#h_ticket_id').val();
	data += '&ticket_id='+ticket_id+'&action=request_spare';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "SPAREREQSUCCESS"){
				display_spare_details(result.spare_data);
				clear_spare_request();
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
}

function show_customer_detail(ticket_id){
	dp = $('#td_cust_name_'+ticket_id).html();
	dp += ' , '+$('#td_location_name_'+ticket_id).html();
	$('#customer_detail_h5').html(dp);
}
g_asset_history = new Array;
function display_asset_history(asset_history){
	var dp1 = '', dp2 ='';
	g_asset_history = asset_history;
	if(!($.isEmptyObject(asset_history))){
		//for(i=0; i<Object.keys(asset_history).length; i++){
		for(i in asset_history){
			opening_reading = 0; closing_reading =0;
			dp1 += '<div class="history_item"><h5>Ticket ID : '+asset_history[i].ticket_id+'</h5><p> Creation Date : '+asset_history[i].creation_date+' , Complaint : '+asset_history[i].complaint_name+'</p>';
			dp1 += '<p> Coordinator : '+asset_history[i].sc_name+' , Engineer : '+asset_history[i].engr_name+'</p>';
			if('call_report' in asset_history[i]){
				for(k in asset_history[i].call_report){
					opening_reading = asset_history[i].call_report[k].opening_reading;
					closing_reading = asset_history[i].call_report[k].closing_reading;
					break;
				}
			}
			dp1 += '<p> Opening Reading : '+opening_reading+' , Closing Reading : '+closing_reading+'</p>';
			if('spare_data' in asset_history[i]){
				dp1 += '<h6>Material List</h6><table><tr><th>Material Name</th><th>Replaced Quantity</th></tr>';
				//for(j=0; j<asset_history[i].spare_data.length; j++){
				for(j in asset_history[i].spare_data){
					dp1 += '<tr><td>'+asset_history[i].spare_data[j].item_name+'</td><td>'+asset_history[i].spare_data[j].replaced_quantity+'</td></tr>';
				}
				dp1 += '</table>';
			}
			dp1 +='</div>';
		}
		$('#show_history_div').html(dp1).show();
	}else{
		$('#show_history_div').html('No History available for this asset').show();
	}

}

function display_spare_details(spare_data){
	op='';
	data = spare_data;
	if(data.length){
		for(c=0;c<data.length;c++){
			if(data[c].spare_status == 'pending'){
				op += '<tr><td>'+(c+1)+'</td><td>'+data[c].item_name+'</td><td>'+data[c].quantity+'</td><td>'+data[c].raised_by+'</td><td>'+data[c].spare_status+'</td>\
				<td><button type="button" class="btn btn-success" onclick="approve_reject_spare(\'StoresPending\' ,'+data[c].spare_request_id+')";>Approve</button>&nbsp;<button type="button" class="btn btn-danger" onclick="approve_reject_spare(\'rejected\' ,'+data[c].spare_request_id+')";>Reject</button></td></tr>';
			}else{
				op += '<tr><td>'+(c+1)+'</td><td>'+data[c].item_name+'</td><td>'+data[c].quantity+'</td><td>'+data[c].raised_by+'</td><td>'+data[c].spare_status+'</td><td></td></tr>';
			}
		}
		$('#requested_spare_list_tbody').html(op);
	}else{
		$('#requested_spare_list_tbody').html('<tr><td colspan="6">No requested spares</td></tr>');
	}
}

function refresh_ticket_list(){
	ticket_status = $('#ticket_status').val();
    if(ticket_status == 'assigned'){
    	pending_days = $('#pending_days').val();
    	data = 'ticket_status='+ticket_status+'&pending_days='+pending_days+'&action=refresh_ticket_list';
    }else{
    	from_date = $('#from_date').val();
    	to_date = $('#to_date').val();
    	data = 'ticket_status='+ticket_status+'&from_date='+from_date+'&to_date='+to_date+'&action=refresh_ticket_list';
    }
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		async: false,
		success: function(result){
			if(result.infocode == "DATALOADED"){
				dp = '';
				data = result.ticket_list_data;
				if(!($.isEmptyObject(data))){
					for(c=0;c<data.length;c++){
						dp += "<tr><td>"+data[c].ticket_id+"</td>";
						dp += "<td id='td_cust_name_"+data[c].ticket_id+"'>"+data[c].customer_name+"</td>";
                        dp += "<td id='td_location_name_"+data[c].ticket_id+"'>"+data[c].location_name+"</td>";
						dp += "<td>"+data[c].item_name+"</td>";	
						dp += "<td>"+data[c].complaint_name+"</td>";
						dp += "<td>"+data[c].creation_date+"</td>";
						dp += '<td><button type="button" class="btn btn-primary btn-lg" onclick="view_ticket_detail(\''+data[c].ticket_id+'\');">View Details</button>&nbsp;</td></tr>';
					}
					$('#ticket_list_tbody').html(dp);
				}else{
					$('#ticket_list_tbody').html('<tr><td colspan="7">No tickets available in this category</td></tr>');
				}
			}else{
				location.reload();
			}
		},
		error: function(){}
	});
}

function approve_reject_spare(spare_status, spare_request_id){
	ticket_id = $('#h_ticket_id').val();
	data = 'ticket_id='+ticket_id+'&spare_status='+spare_status+'&spare_request_id='+spare_request_id+'&action=approve_reject_spare'
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "APRROVEREJECTSUCCESS"){
				display_spare_details(result.spare_data);
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
}

function show_loader(){
	$('#loader').show();
}

function hide_loader(){
	$('#loader').hide();
}

function remove_active_tabs(){
	var tablist = ['li_engineer', 'li_spares', 'li_callreport', 'li_closeticket', 'li_history'];
	for(c in tablist){
		$('#'+tablist[c]).removeClass('active');
	}
	var tablist2 = ['tab_engineer', 'tab_spares', 'tab_callreport', 'tab_closeticket', 'tab_history'];
	for(c in tablist2){
		$('#'+tablist2[c]).removeClass('active');
	}
	clear_spare_request();
}

function clear_spare_request(){
	for(c=2;c<=gc;c++){
		$('#tr_'+c).remove();
	}
	gc=2;
	$('#spare_name_1').val('');
	$('#engineer_stock_1').html('');
    $('#store_stock_1').html('');
    $('#quantity_1').val('');
}