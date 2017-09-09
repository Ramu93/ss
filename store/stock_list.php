<?php 

session_start();
include('../commonmethods.php');
if(!isset($_SESSION['login'])){
    header('Location: '.HOMEPATH);exit();
}

include('..'.DIRECTORY_SEPARATOR.'dbconfig.php');
include('..'.DIRECTORY_SEPARATOR.'header.php');
include('..'.DIRECTORY_SEPARATOR.'topbar.php');
include('..'.DIRECTORY_SEPARATOR.'sidebar.php');


?>

<!--main-container-part-->
<div id="content">
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="<?php echo HOMEURL; ?>dashboard.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
<!--End-breadcrumbs-->

	<div class="widget-box widget-box-90">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>Stock List View</h5>
        </div>
        <div class="widget-content nopadding">
			<table id="spare_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        
                        <th>S.No</th>
						<th>Spare Name</th>
						<th>Current Stock</th>
						<!--th>View</th-->
                    </tr>
                </thead>
				<tbody>
					<?php 
                        //$select_query = "SELECT a.spare_id,a.spare_status,b.item_master_id,b.item_name FROM spare_request a,item_master b WHERE spare_status = 'StoresPending' AND a.spare_id = b.item_master_id";
                        $select_query = "SELECT a.item_name, IFNULL(b.current_stock,0) as current_stock 
                        FROM item_master a LEFT JOIN store_material_stock b 
                        ON a.item_master_id = b.item_master_id 
                        WHERE a.item_type = 'SPARES'";
                        $result = mysqli_query($dbc,$select_query);
                        $row_counter = 0;
						$datatableflag = false;
                        if(mysqli_num_rows($result) > 0) {
							$datatableflag = true;
                            while($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                    echo "<td>".++$row_counter."</td>";
									
									echo "<td>".$row['item_name']."</td>";
									echo "<td>".$row['current_stock']."</td>";
									//echo "<td><a href='spare_edit.php?spare_id={$row['spare_id']}&item_master_id={$row['item_master_id']}'>View</a></td>";
									                                
								echo "</tr>";
                            }
                        }else{
                            echo '<tr><td colspan="4">No spare list found</td></tr>';
                        }
                    ?>				
                </tbody>
            </table>
		</div>
	</div>
</div>
<!--end-main-container-part-->

<?php include('..'.DIRECTORY_SEPARATOR.'footer_js.php'); ?>
<script type="text/javascript">
//write all js here	
$(document).ready(function() {
    <?php if($datatableflag){ ?>
    $('#spare_list').DataTable();
    <?php } ?>
    $('#li_store_menu').addClass('active open');
    $('#li_sc_ticketmenu').addClass('active open');
});

</script>
<?php include('..'.DIRECTORY_SEPARATOR.'footer.php'); ?>