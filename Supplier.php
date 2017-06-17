<?php
$PageName="Supplier";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
IsLoggedIn();
include("Template/HTML.php");
?>    

<?php
include("Template/Header.php");
?>

<?php
include("Template/Sidebar.php");
?>

        <div id="content" class="clearfix">
            <div class="contentwrapper">
                <?php $BreadCumb="Manage Supplier"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$SupplierId=isset($_GET['SupplierId']) ? $_GET['SupplierId'] : '';
				$SupplierName=$SupplierMobile=$SupplierRemarks=$SupplierAddress=$ButtonContent=$ButtonContentSet="";
				$count1=0;
				if($SupplierId!="")
				{
					$query1="select * from supplier where SupplierId='$SupplierId' and SupplierStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$SupplierName=$row1['SupplierName'];
						$SupplierMobile=$row1['SupplierMobile'];
						$SupplierRemarks=$row1['SupplierRemarks'];
						$SupplierAddress=br2nl($row1['SupplierAddress']);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=Supplier><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateSupplierId=$SupplierId;
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Supplier";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="Supplier" id="Supplier" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Name</label>
												<input tabindex="1" class="span8" id="SupplierName" type="text" name="SupplierName" value="<?php echo $SupplierName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Mobile</label>
												<input tabindex="2" class="span8" id="SupplierMobile" type="text" name="SupplierMobile" value="<?php echo $SupplierMobile; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Address</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="3" name="SupplierAddress" id="SupplierAddress"><?php echo $SupplierAddress; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="Supplier" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SupplierId" value="<?php echo $UpdateSupplierId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span8">
					<?php
					$query="select * from supplier where SupplierStatus='Active' order by SupplierName ";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ListSupplierName=$row['SupplierName'];	
						$ListSupplierMobile=$row['SupplierMobile'];	
						$ListSupplierId=$row['SupplierId'];	
						$ListSupplierAddress=$row['SupplierAddress'];	
						$Edit="<a href=Supplier/Update/$ListSupplierId><span class=\"icon-edit\"></span></a>";
						$Note="<a href=Note/Supplier/$ListSupplierId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
						$Delete="<a href=DeletePopUp/DeleteSupplier/$ListSupplierId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
						$QA[]=array($ListSupplierName,$ListSupplierMobile,$ListSupplierAddress,$Edit,$Delete,$Note);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					?>
						<div class="box gradient">
							<div class="title">
								<h4><span>Supplier List</span></h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<table id="SupplierTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Mobile</th>
											<th>Address</th>
											<th><span class="icon-edit"></span></th>
											<th><span class="icomoon-icon-cancel"></span></th>
											<th><span class="icomoon-icon-clipboard-3 tip" title="Note"></span></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {

	$('#SupplierTable').dataTable({
		"sPaginationType": "two_button",
		"bJQueryUI": false,
		"bAutoWidth": false,
		"bLengthChange": false,  
		"bProcessing": true,
		"bDeferRender": true,
		"sAjaxSource": "plugins/Data/data1.txt",
		"fnInitComplete": function(oSettings, json) {
		  $('.dataTables_filter>label>input').attr('id', 'search');
			$('#myModal').modal({ show: false});
			$('#myModal').on('hidden', function () {
				console.log('modal is closed');
			})
			$("a[data-toggle=modal]").click(function (e) {
			lv_target = $(this).attr('data-target');
			lv_url = $(this).attr('href');
			$(lv_target).load(lv_url);
			});	
		}
	});
		
	$("input, textarea, select").not('.nostyle').uniform();
	$("#Supplier").validate({
		rules: {
			SupplierName: {
				required: true,
			},
			SupplierMobile: {
				remote: "RemoteValidation?Action=MobileValidation&Id=SupplierMobile"
			}
		},
		messages: {
			SupplierName: {
				required: "Please enter this!!",
			},
			SupplierMobile: {
				remote: jQuery.format("<?php echo $MOBILENUMBERDIGIT; ?> Digit Mobile number!!")
			}
		}   
	});
	$("#DeleteSupplierForm").validate({
		rules: {
			Password : {
				required: true,
			}
		},
		messages: {
			Password : {
				required: "Please enter Password!!",
			}
		}   
	});
});
</script>				
    
<?php
include("Template/Footer.php");
?>