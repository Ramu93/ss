engineer_template = '<tr><td>[sno]</td><td>[item_name]</td><td style="text-align:center;">[avail_qty]</td><td style="text-align:center;"><input type="text" name="[returned_qty]" style="width:50px;"/></td><td style="text-align:center;"><input type="checkbox" name="picked_list[]" class="form-control" value="[check_value]">\
				 <input type="hidden" name="[h_esid]" value="[h_esid_val]"><input type="hidden" name="[h_avail_qty]" value="[h_avail_qty_val]"><input type="hidden" name="[h_item_master_id]" value="[h_item_master_id_val]"></td>';

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
					data = result.engineer_data;
					for(c=0;c<data.length;c++){
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
												.replace('[returned_qty]','returned_qty_'+data[c].engineer_stock_id)
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

function mrn_type_change(){
	mrn_type = $('#mrn_type').val();
	if(mrn_type == 'internal'){
		$('#po_div').hide();
		//$('#without_po_div').hide();
		$('#purchase_list').hide();
		$('#engineer_list_div').show();
		$('#internal_list').show();
		$("label.error").hide();
	}else{
		$('#engineer_list_div').hide();
		$('#internal_list').hide();
		$('#po_div').show();
		//$('#without_po_div').show();
		$('#purchase_list').show();
		$("label.error").hide();
	}
}

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