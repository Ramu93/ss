function view_call_detail(call_id){
	switch(g_call_status){
		case 'created':
			loaddata_created(call_id);
		break;
		case 'assigned':
			loaddata_assigned(call_id);
		break;
	}

}

function loaddata_created(call_id){
	$('#li_closecall').show().removeClass('active');;
	$('#li_createticket').show().addClass('active');
	$('#li_callstatus').hide().removeClass('active');
	$('#tab_callstatus').removeClass('active');
	$('#tab_closecall').removeClass('active');
	$('#tab_createticket').addClass('active');
	$('#h_call_id').val(call_id);
    data = 'call_id='+call_id+'&action=fetch_call_details';
	$.ajax({
		url: "callservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "CALLDETAILSUCCESS"){
				//console.log(result);
				product_change(result.call_data.department_id);
				$('#party_master_id').val(result.call_data.party_master_id);
				$('#party_location_id').val(result.call_data.party_location_id);
				$('#department_id').val(result.call_data.department_id);
				$('#call_id').val(call_id);
				$('#customer_div').html(result.call_data.customer_name);
				$('#location_div').html(result.call_data.location_name);
				$('#dept_div').html(result.call_data.department_name);
				$('#unattended_call_div').show();
				$('#attended_call_div').hide().html('');
				$('#process_call_modal').modal('show');
			}else{
				//bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

function loaddata_assigned(call_id){
	$('#li_closecall').hide();
	$('#li_createticket').hide();
	$('#li_callstatus').show().addClass('active');
	$('#tab_callstatus').addClass('active');
	$('#tab_closecall').removeClass('active');
	$('#tab_createticket').removeClass('active');
	$('#h_call_id').val(call_id);
    data = 'call_id='+call_id+'&action=fetch_call_details_assigned';
	$.ajax({
		url: "callservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "CALLDETAILSUCCESS"){
				dp = result.call_info;
				if(result.ticket_id != 0)
					dp += ' <button class="btn btn-success" onclick="window.location = \'process_ticket.php?ticket_status=created&ticket_id='+result.ticket_id+'&mode=boxopen\';">Goto Ticket</button>';
				$('#call_status_div').html(dp);
				$('#process_call_modal').modal('show');
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}

function product_change(department_id){
	//department_id = $('#department_id').val();
    var dp='';
    $.ajax({
        url: "generalservice.php",
        type: "POST",
        data:  'department_id='+department_id+'&action=product_change',
        dataType: 'json',
        success: function(result){
            if(result.infocode == "PRODUCTDATARETREIVED"){
                data = result.product_data;
                if(data.length){
					dp += '<option value="">Select Product</option>';
                    for(c=0; c<data.length;c++){
						dp += '<option value="'+data[c].asset_master_id+'">'+data[c].item_name+'</option>';
                    }
					$('#product_master_id').html(dp);
                    //$('#department_id').removeAttr('disabled');
                }else{
					$('#product_master_id').html('<option value="">No Product added</option>');
                    //$('#department_id').attr('disabled', 'disabled');
                }
            }else{
                //bootbox.alert(result.message);
				$('#product_master_id').html('<option value="">No Product added</option>');
                //$('#department_id').attr('disabled', 'disabled');
            }
        },
        error: function(){}             
    });
    
}

function create_ticket(){
	if($('#addticket_form').valid()){
        var data = $('#addticket_form').serialize();
        $.ajax({
            url: "createticketservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "TICKETADDED"){
                	fetch_call_list();
                    $('#unattended_call_div').hide();
                    dp = 'Ticket has been created successfully<br>Ticket ID : '+result.ticket_id;
                    dp += ' <button class="btn btn-success" onclick="window.location = \'process_ticket.php?ticket_status=created&ticket_id='+result.ticket_id+'&mode=boxopen\';">Goto Ticket</button>';
                    $('#attended_call_div').show().html(dp);
                    $('#li_closecall').hide();
                    $('#li_callstatus').hide();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function status_change(){
    call_status = $('#call_status').val();
    window.location = 'inbound_calls.php?call_status='+call_status;
}

function fetch_call_list(){
	data = 'call_status2='+g_call_status2+'&action=fetch_call_list';
	$.ajax({
		url: "callservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "CALLLISTSUCCESS"){
				d= result.call_data;dp='';
				for(c=0;c<d.length;c++){
					dp += '<tr><td>'+d[c].inbound_call_id+'</td><td>'+d[c].customer_name+'</td><td>'+d[c].location_name+'</td><td><button type="button" class="btn btn-primary btn-lg" onclick="view_call_detail(\''+d[c].inbound_call_id+'\');">View Details</button></td></tr>';
				}
				$('#call_list_tbody').html(dp);
			}else if(result.infocode == "NOCALLLIST"){
				$('#call_list_tbody').html('<tr><td colspan="4">No inbound calls in this category</td></tr>');
			}else{
				location.reload();
			}
		},
		error: function(){}             
	});
    
}

function sc_close_call(){
    call_id = $('#h_call_id').val();
    remarks = $('#closing_remarks').val();
	data = 'call_id='+call_id+'&remarks='+remarks+'&action=sc_close_call';
	$.ajax({
		url: "callservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "CALLUPDATED"){
				fetch_call_list();
				$('#li_closecall').hide();
				$('#li_createticket').hide();
				$('#tab_closecall').removeClass('active');
				$('#tab_createticket').removeClass('active');
				$('#li_callstatus').show().addClass('active');
				$('#tab_callstatus').addClass('active');
				$('#call_status_div').html('This call has been closed');
			}else{
				bootbox.alert(result.message);
			}
		},
		error: function(){}             
	});
    
}