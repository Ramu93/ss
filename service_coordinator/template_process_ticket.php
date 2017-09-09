<div class="modal fade large" id="process_ticket_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

                </button>
                 <h4 class="modal-title" id="myModalLabel">Process Ticket</h4>

            </div>
            <div class="modal-body">
            	<div id="loader" class="overlay" style="display: none;"><div class="overlay2"><img src="<?php echo HOMEURL ?>img/loader.gif" style="height:100px;width: 100px;" /></div></div>
            	<h4 id="ticket_id_h4"></h4>
            	<h5 id="customer_detail_h5"></h5>
                <div role="tabpanel">
                    <!-- Nav tabs -->
					<div class="widget-title">
					<ul class="nav nav-tabs">
						<li class="active" id="li_engineer"><a data-toggle="tab" href="#tab_engineer">Engineer</a></li>
						<li id="li_spares"><a data-toggle="tab" href="#tab_spares">Spares Request</a></li>
						<li id="li_callreport" style="display: none;"><a data-toggle="tab" href="#tab_callreport">Call Report</a></li>
						<li id="li_closeticket"><a data-toggle="tab" href="#tab_closeticket">Close Ticket</a></li>
						<li id="li_history" style=""><a data-toggle="tab" href="#tab_history">Asset History</a></li>
					</ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top: 15px;">
                        <div role="tabpanel" class="tab-pane active" id="tab_engineer">
                        	<div id="assign_engineer_div" style="display: none;">
								<div class="control-group">
									<label class="control-label">Engineer Name :</label>
									<div class="controls">
										<?php $query = "SELECT * FROM employee_master WHERE rolename='engr'";
												$result = mysqli_query($dbc,$query);
												if(mysqli_num_rows($result)>0){
													echo '<select name="assign_engineer_id" id="assign_engineer_id" class="form-control required" onchange="">';
													echo '<option value="" selected="selected">Select Engineer</option>';
													while($row=mysqli_fetch_array($result)){
														echo '<option value="'.$row['employee_master_id'].'">'.$row['employee_name'].'</option>';
													}
													echo '</select>';
												}
										?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Visiting Date:</label>
									<div class="controls">
										<div  class="input-append">
											<input data-date-format="yyyy-mm-dd" class="span11" type="text" id="visit_date">
											<!--span class="add-on"><i class="icon-th"></i></span--> 
										</div>
									</div>
								</div><button type="button" class="btn btn-success" onclick="assign_ticket();">Assign</button>
							</div>
							<div id="reassign_engineer_div" style="display: none;">
								<div class="control-group">
									<label id="assigned_info"></label>
									<label class="control-label">Engineer Name :</label>
									<div class="controls">
										<?php $query = "SELECT * FROM employee_master WHERE rolename='engr'";
												$result = mysqli_query($dbc,$query);
												if(mysqli_num_rows($result)>0){
													echo '<select name="assign_engineer_id_re" id="assign_engineer_id_re" class="form-control required" onchange="">';
													echo '<option value="" selected="selected">Select Engineer</option>';
													while($row=mysqli_fetch_array($result)){
														echo '<option value="'.$row['employee_master_id'].'">'.$row['employee_name'].'</option>';
													}
													echo '</select>';
												}
										?>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Visiting Date:</label>
									<div class="controls">
										<div  class="input-append">
											<input data-date-format="yyyy-mm-dd" class="span11" type="text" id="visit_date_re">
											<!--span class="add-on"><i class="icon-th"></i></span--> 
										</div>
									</div>
								</div><button type="button" class="btn btn-success" onclick="reassign_ticket();">Re-Assign</button>
							</div>
						</div>
                        <div role="tabpanel" class="tab-pane" id="tab_spares">
                        	<div id="request_spare_div" style="display: none;">
								<form id="request_spare_form" name="request_spare_form" method="post" class="form-horizontal" action="" onsubmit="return false;">
									<table>
										<thead>
											<tr style="text-align: center;padding:10px;"><th>Spare</th><th>Engineer Stock</th><th>Store Stock</th><th>Needed Qty</th></tr>
										</thead>
										<tbody id="tbody_spare">
											<tr id="tr_spare_template">
											<td style="width:250px;">
												<?php $query = "SELECT * FROM  item_master WHERE item_type IN ('SPARES','CS')";
													$result = mysqli_query($dbc,$query);
													if(mysqli_num_rows($result)>0){
														echo '<select id="spare_name_1" name="spare_name[]" onchange="change_spare(1);">';
														echo '<option value="" selected="selected">Select Spare</option>';
														while($row=mysqli_fetch_array($result)){
															echo '<option value="'.$row['item_master_id'].'">'.$row['item_name'].'</option>';
														}
														echo '</select>';
													}
												?>
											</td>
											<td>
												<label style="margin-top:5px;" id="engineer_stock_1"></label>
											</td>
											<td>
												<label style="margin-top:5px;" id="store_stock_1"></label>
											</td>
											<td >
												<input class="form-control" name="quantity[]" id="quantity_1" type="text" placeholder="Qty" style="width:50px;">
											</td>
											</tr>
										</tbody>
									</table>
									<div class="" style="text-align: left;margin-top: 10px;">
										<button type="button" class="btn btn-primary" onclick="add_another_row();">Add </button>
										<button type="button" class="btn btn-success" onclick="request_spare();">Request Material</button>
									</div>
								</form>
							</div>
							<div id="requested_spare_list_div" style="display: none;padding-top: 20px;">
								<h4>Requested Material List</h4>
								<table class="table table-bordered table-striped">
									<thead><tr><th>S.No.</th><th>Material Name</th><th>Requested Qty</th><th>Requested By</th><th>Status</th><th>Action</th></tr></thead>
									<tbody id="requested_spare_list_tbody"><tr><td colspan="6">No requested spares</td></tr></tbody>
								</table>
							</div>
							<div id="request_spare_nope_div" style="display: none;">
								<label>Please assign Engineer to Request Spares</label>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_closeticket">
							<label class="control-label">Remarks:</label>
							<div class="controls">
								<div  class="input-append">
									<input class="span22" type="text" id="closing_remarks" placeholder="Closing Remarks">
									<!--span class="add-on"><i class="icon-th"></i></span--> 
								</div>
							</div>
							<button type="button" class="btn btn-success" onclick="sc_close_ticket();" style="margin: 5px 0 5px 0;">Close Ticket</button>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_callreport">
							<div id="show_call_report_div" style="display: none;">
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab_history">
							<div id="show_history_div" style="display: none;">
							</div>
						</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            	<span class="align-left" id="result_msg"></span>
            	<input class="form-control" name="h_ticket_id" id="h_ticket_id" type="hidden">
            	<input class="form-control" name="h_engr_id" id="h_engr_id" type="hidden">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--button type="button" class="btn btn-primary save">Save changes</button-->
            </div>
        </div>
    </div>
</div>