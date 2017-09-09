engineer_template = '<tr><td>[sno]</td><td>[item_name]</td><td style="text-align:center;">[avail_qty]</td><td style="text-align:center;"><input type="text" id="[returned_qty]" style="width:50px;"/></td><td>[engineer_list]</td><td style="text-align:center;"><input type="button" name="btn_transfer" class="btn btn-success" value="Transfer" onclick="transfer_material([check_value])">\
				 <input type="hidden" name="[h_esid]" value="[h_esid_val]"><input type="hidden" name="[h_avail_qty]" value="[h_avail_qty_val]"><input type="hidden" id="[h_item_master_id]" value="[h_item_master_id_val]"></td>';

function fetch_engineer_stock(){
    engineer_id = $('#engineer_id').val().trim(); var dp='';
	data = 'engineer_id='+engineer_id+'&action=fetch_engineer_stock';
	if(engineer_id!='' || engineer_id!=0){
		$.ajax({
			url: "stockservices.php",
			type: "POST",
			data:  data,
			dataType: 'json',
			success: function(result){
				if(result.infocode == "DATALOADED"){
					elist_op = ''
					engineer_list = result.engineer_list;
					elist_op += '<select id="[elist_select]" class="form-control">';
					for(d=0;d<engineer_list.length;d++){
						elist_op += '<option value="'+engineer_list[d].employee_master_id+'">'+engineer_list[d].employee_name+'</option>';
					}
					elist_op += '</select>';

					data = result.engineer_data;
					for(c=0;c<data.length;c++){
						elist = elist_op.replace('[elist_select]','select_'+data[c].engineer_stock_id);
						dp += engineer_template.replace('[sno]',c+1)
												.replace('[item_name]',data[c].item_name)
												.replace('[avail_qty]',data[c].current_stock)
												.replace('[check_value]',data[c].engineer_stock_id)
												.replace('[h_esid]','h_esid_'+data[c].engineer_stock_id)
												.replace('[h_esid_val]',data[c].engineer_stock_id)
												.replace('[h_avail_qty]','h_avail_qty_'+data[c].engineer_stock_id)
												.replace('[h_avail_qty_val]',data[c].current_stock)
												.replace('[h_item_master_id]','h_item_master_id_'+data[c].engineer_stock_id)
												.replace('[h_item_master_id_val]',data[c].item_master_id)
												.replace('[returned_qty]','transfer_qty_'+data[c].engineer_stock_id)
												.replace('[engineer_list]',elist)
					}
					$('#internal_list_tbody').html(dp);
					$('#btn_receive_material').removeAttr('disabled');
				}else{
					//bootbox.alert(result.message);
					$('#internal_list_tbody').html('<tr><td colspan="5">No stock available for this engineer</td></tr>');
					$('#btn_receive_material').attr('disabled','disabled');
				}
			},
			error: function(){}             
		});
	}
    $("label.error").hide();
}

//$("label.error").hide();

function receive_material(){
	if($('#receive_material_form').valid()){
		data = $('#receive_material_form').serialize();
		mrn_type = $('#mrn_type').val();
		$.ajax({
			url: "stockservices.php",
			type: "POST",
			data:  data,
			dataType: 'json',
			success: function(result){
				if(result.infocode == "RECEIVEMATERIALSUCCESS"){
					bootbox.alert(result.message, function(){
						window.location = 'mrn.php?mrn_type='+mrn_type;
					});
				}else{
					bootbox.alert(result.message);
					//$('#btn_receive_material').attr('disabled','disabled');
				}
			},
			error: function(){}             
		});
	}
}

function transfer_material(stock_id){
	from_engineer_id = $('#engineer_id').val();
	item_master_id = $('#h_item_master_id_'+stock_id).val();
	transfer_qty = $('#transfer_qty_'+stock_id).val();
	to_engineer_id = $('#select_'+stock_id).val();
	data = 'from_engineer_id='+from_engineer_id+'&to_engineer_id='+to_engineer_id+'&transfer_qty='+transfer_qty+'&item_master_id='+item_master_id+'&action=transfer_material';
	$.ajax({
		url: "stockservices.php",
		type: "POST",
		data:  data,
		dataType: 'json',
		success: function(result){
			if(result.infocode == "TRANSFERMATERIALSUCCESS"){
				bootbox.alert(result.message, function(){
					window.location = 'internal_transfer.php?engineer_id='+from_engineer_id;
				});
			}else{
				bootbox.alert(result.message);
				//$('#btn_receive_material').attr('disabled','disabled');
			}
		},
		error: function(){}             
	});
}