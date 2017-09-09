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
				bootbox.alert(result.message, function(){
					location.reload();
				});
				/*$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
				$('#assign_engineer_div').hide();
				$('#assigned_info').html('Assigned to Engineer');
				$('#reassign_engineer_div').show();*/
			}else{
				bootbox.alert(result.message);
				//$('#result_msg').fadeIn(100).fadeOut(5000).html(result.message);
			}
		},
		error: function(){}             
	});
    
}

function add_spare_details(){
    if($('#addspare_form').valid()){
        var data = $('#addspare_form').serialize();
        $.ajax({
            url: "spareservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "SPAREADDED"){
                    bootbox.alert(result.message);
                    $('#addspare_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function spare_ticket(ticket_id){
    spare_id = $('#select_'+ticket_id).val();
	data = 'ticket_id='+ticket_id+'&spare_id='+spare_id+'&action=spare_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				bootbox.alert(result.message, function(){
					location.reload();
				});
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

function addspare_ticket(spare_id){
	$('#h_ticket_id').val(spare_id);
	$('#modal_close_ticket').modal('show');
}

function scc_close_ticket(spare_id){
    spare_id = $('#h_ticket_id').val();
    remarks = $('#remarks').val();
	data = 'spare_id='+spare_id+'product='+product+'spare_name='+spare_name+'quantity='+quantity+'&remarks='+remarks+'&action=scc_close_ticket';
	$.ajax({
		url: "createticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				bootbox.alert(result.message, function(){
					location.reload();
				});
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

function engr_close_ticket(){
	data = $('#call_report_form').serialize();
    ticket_id = $('#h_ticket_id').val();
    //remarks = $('#closing_remarks').val();
	data += '&ticket_id='+ticket_id+'&action=close_ticket';
	$.ajax({
		url: "ticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TICKETUPDATED"){
				bootbox.alert(result.message, function(){
					location.reload();
				});
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

function status_change(){
    ticket_status = $('#ticket_status').val();
    window.location = 'process_ticket.php?ticket_status='+ticket_status;
}

function view_ticket_detail(ticket_id, ticket_status){
	switch(ticket_status){
		case 'assigned':
			loaddata_assigned(ticket_id);
			//$('#request_spare_nope_div').hide();
			$('#request_spare_div').show();
			$('#requested_spare_list_div').show();
			$('#enter_call_report_div').show();
			$('#show_call_report_div').hide();
		break;
		case 'engineerclosed':
			loaddata_engineerclosed(ticket_id);
			$('#request_spare_div').hide();
			$('#requested_spare_list_div').show();
			$('#enter_call_report_div').hide();
			$('#show_call_report_div').show();
		break;
	}
	$('#process_ticket_modal').modal('show');
	//console.log('Ticket id :'+ticket_id);
	$('#h_ticket_id').val(ticket_id);
	$('#ticket_id_h4').html('Ticket ID : '+ticket_id);
}

gc=1;
function add_another_row(){
    template = $('#tr_spare_template').html();
    $('#tbody_spare').append('<tr id="tr_'+gc+'">'+template+'<td><button type="button" class="btn btn-danger" onclick="remove_row(\''+gc+'\');">Remove </button></td></tr>');
    gc++;
}

function remove_row(rowid){
	$('#tr_'+rowid).remove();
}

function loaddata_assigned(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_assigned';
	$.ajax({
		url: "ticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			if(result.infocode == "DATALOADED"){
				//console.log(result);
				//$('#assigned_info').html('Assigned to '+result.engineer_data.employee_name+' to visit on '+result.engineer_data.visit_date);
				data = result.spare_data;op='';
				if(data.length){
					for(c=0;c<data.length;c++){
						op += '<tr><td>'+(c+1)+'</td><td>'+data[c].item_name+'</td><td>'+data[c].quantity+'</td><td>'+data[c].raised_by+'</td><td>'+data[c].spare_status+'</td></tr>';
					}
					$('#requested_spare_list_tbody').html(op);
					
				}
				$('#request_spare_div').show();
				$('#requested_spare_list_div').show();
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
	$('#request_spare_div').show();
	$('#requested_spare_list_div').show();
    
}

function loaddata_engineerclosed(ticket_id){
    data = 'ticket_id='+ticket_id+'&action=loaddata_engineerclosed';
	$.ajax({
		url: "ticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		//async: false,
		success: function(result){
			if(result.infocode == "DATALOADED"){
				//console.log(result);
				//$('#assigned_info').html('Assigned to '+result.engineer_data.employee_name+' to visit on '+result.engineer_data.visit_date);
				data = result.spare_data;op='';
				if(data.length){
					for(c=0;c<data.length;c++){
						op += '<tr><td>'+(c+1)+'</td><td>'+data[c].item_name+'</td><td>'+data[c].quantity+'</td><td>'+data[c].raised_by+'</td><td>'+data[c].spare_status+'</td></tr>';
					}
					$('#requested_spare_list_tbody').html(op);
					
				}
				$('#requested_spare_list_div').show();

				op2 = '<label> Opening Reading : '+result.call_report.opening_reading;
				op2 += '<label> Closing Reading : '+result.call_report.closing_reading;
				op2 += '<label> Visit Date : '+result.call_report.visit_date;
				op2 += '<label> Start Time : '+result.call_report.start_time;
				op2 += '<label> End Time : '+result.call_report.end_time;
				op2 += '<label> Remarks : '+result.call_report.remarks;
				$('#show_call_report_div').html(op2);
				$('#show_call_report_div').show();
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
	$('#requested_spare_list_div').show();
}

function request_spare(){
	data = $('#request_spare_form').serialize();
    ticket_id = $('#h_ticket_id').val();
	data += '&ticket_id='+ticket_id+'&action=request_spare';
	$.ajax({
		url: "ticketservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "SPAREREQSUCCESS"){
				bootbox.alert(result.message, function(){
					location.reload();
				});
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
}