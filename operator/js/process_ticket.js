function assign_ticket(ticket_id){
    engineer_id = $('#select_'+ticket_id).val();
	data = 'ticket_id='+ticket_id+'&engineer_id='+engineer_id+'&action=assign_ticket';
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
function close_ticket(ticket_id){
	$('#h_ticket_id').val(ticket_id);
	$('#modal_close_ticket').modal('show');
}
function sc_close_ticket(ticket_id){
    ticket_id = $('#h_ticket_id').val();
    remarks = $('#remarks').val();
	data = 'ticket_id='+ticket_id+'&remarks='+remarks+'&action=sc_close_ticket';
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

function status_change(){
    ticket_status = $('#ticket_status').val();
    window.location = 'process_ticket.php?ticket_status='+ticket_status;
    
}

function view_ticket_detail(ticket_id){
	$('#myModal').modal('show');
	//console.log('Ticket id :'+ticket_id);
	$('#h_ticket_id').val(ticket_id);
}

function add_another_row(){
    template = $('#tr_spare_template').html();
    $('#tbody_spare').append('<tr>'+template+'</tr>');
    
}