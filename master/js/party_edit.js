function update_party_details(){
    if($('#edit_party').valid()){
        var data = $('#edit_party').serialize();
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "PARTYUPDATED"){
                    bootbox.alert(result.message, function(){
                        window.location = 'party_view.php';
                    });
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function addlocation_ticket(department_id){
	$('#h_ticket_id').val(department_id);
	$('#modal_close_ticket').modal('show');
}

function add_department_details(){
    if($('#adddepartment_form').valid()){
        var data = $('#adddepartment_form').serialize();
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "DEPARTMENTADDED"){
                    display_department_table(result.dept_data);
                    bootbox.alert(result.message);
                    $('#adddepartment_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function add_partylocation_details(){
    if($('#addpartylocation_form').valid()){
        var data = $('#addpartylocation_form').serialize();
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "PARTYLOCATIONADDED"){
                    display_location_table(result.location_data);
                    bootbox.alert(result.message);
                    $('#addpartylocation_form')[0].reset();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function show_department_list(){
    dept_party_location_id = $('#dept_party_location_id').val();
    var data = 'dept_party_location_id='+dept_party_location_id+'&action=display_department';
    $.ajax({
        url: "partyservices.php",
        type: "POST",
        data:  data,
        dataType: 'json',
        success: function(result){
            if(result.infocode == "DEPTLISTSUCCESS"){
                display_department_table(result.dept_data);
                
            }else{
                $('#department_list_tbody').html('<tr><td colspan="4">No Department added in this location</td></tr>');

           }
        },
        error: function(){}             
    });
    
}


function show_partylocation(){
	$('#myModal').modal('show');
    $('#add_location_div').show();
    $('#edit_location_div').hide();
}

function display_location_table(location_data){
    dp='';
    if(location_data.length){
        for(c=0;c<location_data.length;c++){
            key = location_data[c].party_location_id;
            dp += '<tr><td>'+location_data[c].location_name+'</td><td>'+location_data[c].sc_name+'</td><td><button type="button" class="btn btn-warning" onclick="edit_location(\''+location_data[c].party_location_id+'\');">Edit</button></td></tr>';
            dp += '<input type="hidden" id="e_party_location_id_'+key+'" value="'+location_data[c].party_location_id+'"><input type="hidden" id="e_sc_id_'+key+'" value="'+location_data[c].sc_id+'"><input type="hidden" id="e_location_name_'+key+'" value="'+location_data[c].location_name+'">';
            //dp += '<tr><td>'+location_data[c].location_name+'</td><td>'+location_data[c].address+'</td><td>'+location_data[c].sc_name+'</td><td><button type="button" class="btn btn-danger" onclick="delete_location(\''+location_data[c].party_location_id+'\');">Delete</button></td></tr>';
        }
    }else{
        dp ='<tr><td colspan="4">No Location added for this Customer</td></tr>';
    }
    $('#location_data_tbody').html(dp);
}

function display_department_table(dept_data){
    dp='';
    if(dept_data && dept_data.length){
        for(c=0;c<dept_data.length;c++){
            dp += '<tr><td>'+dept_data[c].department_name+'</td><td>'+dept_data[c].contact_person+'</td><td>'+dept_data[c].contact_number+'</td><td><button type="button" class="btn btn-danger" onclick="delete_location(\''+dept_data[c].department_id+'\');">Delete</button></td></tr>';
        }
    }else{
        dp = '<tr><td colspan="4">No Department added in this location</td></tr>';
    }
    $('#department_list_tbody').html(dp);
}

function delete_location(party_location_id){
    bootbox.confirm("You sure you want to delete this location?",function(result){
        if(result){
            party_master_id = $('#h_party_master_id').val();
            var data = "party_location_id="+party_location_id+"&h_party_master_id="+party_master_id+"&action=delete_location";
            $.ajax({
                url: "partyservices.php",
                type: "POST",
                data:  data,
                dataType: 'json',
                success: function(result){
                    if(result.infocode == "LOCATIONDELETED"){
                        display_location_table(result.location_data);
                    }else{
                        bootbox.alert(result.message);
                    }
                },
                error: function(){}             
            });
        }
    });
}

function update_partylocation_details(){
    if($('#addpartylocation_form').valid()){
        //var data = $('#addpartylocation_form').serialize();
        party_master_id = $('#h_party_master_id').val();
        location_name = $('#location_name_edit').val();
        party_location_id = $('#h_party_location_id').val();
        sc_id = $('#assign_sc_id_edit').val();
        data = 'location_name='+location_name+'&sc_id='+sc_id+'&party_location_id='+party_location_id+'&party_master_id='+party_master_id+'&action=update_partylocation';
        $.ajax({
            url: "partyservices.php",
            type: "POST",
            data:  data,
            dataType: 'json',
            success: function(result){
                if(result.infocode == "PARTYLOCATIONUPDATED"){
                    display_location_table(result.location_data);
                    bootbox.alert(result.message);
                    //$('#addpartylocation_form')[0].reset();
                    $('#add_location_div').show();
                    $('#edit_location_div').hide();
                    $('#h_party_location_id').val();
                    $('#location_name_edit').val();
                    $('#assign_sc_id_edit').val();
                }else{
                    bootbox.alert(result.message);
                }
            },
            error: function(){}             
        });
    }
}

function edit_location(party_location_id){
    $('#add_location_div').hide();
    $('#edit_location_div').show();
    $('#h_party_location_id').val($('#e_party_location_id_'+party_location_id).val());
    $('#location_name_edit').val($('#e_location_name_'+party_location_id).val());
    $('#assign_sc_id_edit').val($('#e_sc_id_'+party_location_id).val());
}

function cancel_location_update(){
    $('#add_location_div').show();
    $('#edit_location_div').hide();
    $('#h_party_location_id').val();
    $('#location_name_edit').val();
    $('#assign_sc_id_edit').val();
}