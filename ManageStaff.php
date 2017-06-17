<?php
$PageName="ManageStaff";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
$MonthPicker=1;
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
			<?php $BreadCumb="Manage Staff"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); ?>

			<?php
			$GetStaffId=isset($_GET['StaffId']) ? $_GET['StaffId'] : '';
			$Print3="";
			$query10="select StaffName,StaffMobile from staff where 
					StaffId='$GetStaffId' and
					StaffStatus!='Deleted' ";
			$check10=mysqli_query($CONNECTION,$query10);
			$count10=mysqli_num_rows($check10);
			if($count10==1)
			{
			$row10=mysqli_fetch_array($check10);
			$TabName=$row10['StaffName'];
			$TabMobile=$row10['StaffMobile'];
			?>
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo "$TabName Mobile - $TabMobile"; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
									<div style="margin-bottom: 20px;">
										<ul id="myTabs" class="nav nav-tabs pattern">
											<li class="active"><a href="#StaffProfile" data-toggle="tab">Staff Detail</a></li>
											<li><a href="#Qualification" data-toggle="tab">Qualification</a></li>
											<li><a href="#SalarySetup" data-toggle="tab">Salary Setup</a></li>
											<li><a href="#SalaryPayment" data-toggle="tab">Salary Payment</a></li>
											<li><a href="#Photo" data-toggle="tab">Documents</a></li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade in active" id="StaffProfile">
												Loading...
											</div>
											<div class="tab-pane fade" id="Qualification">
												Loading...
											</div>
											<div class="tab-pane fade" id="SalarySetup">
												Loading...
											</div>
											<div class="tab-pane fade" id="SalaryPayment">
												Loading...
											</div>
											<div class="tab-pane fade" id="Photo">
												Loading...
											</div>
										</div>
									</div>
							</div>
						</div>
					</div>
				</div>
			<?php 	
			} 				
					$query="select * from staff,masterentry where staff.StaffPosition=masterentry.MasterEntryId order by StaffDOJ";
					
					$DATA=array();
					$QA=array();
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					while($row=mysqli_fetch_array($result))
					{
						$ListStaffName=$row['StaffName'];	
						$ListStaffId=$row['StaffId'];	
						$ListStaffMobile=$row['StaffMobile'];	
						$ListStaffPostion=$row['MasterEntryValue'];
						$ListStaffDOJ=date("d M Y",$row['StaffDOJ']);
						$ListStaffStatus=$row['StaffStatus'];
						if($ListStaffStatus!='Active')
						$ListStaffStatus="<span class=\"date badge badge-important\">Sleep</span>";
						else
						$ListStaffStatus="<span class=\"date badge badge-info\">Active</span>";
					
						$Print3.="<tr>
								<td>$ListStaffName ($ListStaffStatus)</td>
								<td>$ListStaffMobile</td>
								<td>$ListStaffPostion</td>
								<td>$ListStaffDOJ</td>
							</tr>";	
						$ListStaffName="<a href=ManageStaff/$ListStaffId>$ListStaffName</a> $ListStaffStatus";
						$QA[]=array($ListStaffName,$ListStaffMobile,$ListStaffPostion,$ListStaffDOJ);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);					
					?>	
					
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Add Staff</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="Action" name="ManageStaff" id="ManageStaff" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="StaffPosition">Position</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('StaffPosition','StaffPosition','','','','','',1,'');
													?>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="StaffName">Staff Name</label>
												<input class="span8" tabindex="2" id="StaffName" type="text" name="StaffName" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="StaffMobile">Mobile</label>
												<input tabindex="3" class="span8" id="StaffMobile" type="text" name="StaffMobile" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="StaffDOJ" readonly>Date of Joining</label>
												<input tabindex="4" class="span8" id="StaffDOJ" type="text" name="StaffDOJ" readonly />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageStaff" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php $ButtonContent="Save"; ActionButton($ButtonContent,7); ?>
								</form>
                            </div>
                        </div>
                    </div>					
					<div class="span8">
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Staff List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
										<input type="hidden" name="SessionName" value="PrintStaffList" readonly>
										<input type="hidden" name="HeadingName" value="PrintStaffHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Staff List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<?php
								$Print1="<table id=\"StaffTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Staff Name</th>
											<th>Mobile</th>
											<th>Designation</th>
											<th>Date of Joining</th>
										</tr>
									</thead>
									<tbody>";
									echo $Print1;
									$Print2="</tbody>
								</table>";
								echo $Print2;
								$PrintList="$Print1 $Print3 $Print2";
								$_SESSION['PrintStaffList']=$PrintList;
								$PrintHeading="Showing List of Staff";
								$_SESSION['PrintStaffHeading']=$PrintHeading;
								$_SESSION['PrintCategory']="Staff List Session : $CURRENTSESSION";
								?>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
	
		$('#StaffTable').dataTable({
			"sPaginationType": "two_button",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,  
			"bProcessing": true,
			"bDeferRender": true,
			"sAjaxSource": "plugins/Data/data1.txt",
			"fnInitComplete": function(oSettings, json) {
			  $('.dataTables_filter>label>input').attr('id', 'search');
			}
		});
	
		$("#StaffPosition").select2();
		if($('#StaffDOJ').length) {
		$('#StaffDOJ').datepicker({ yearRange: "-100:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$('#StaffPosition').select2({placeholder: "Select"});
		$("#ManageStaff").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				StaffPosition: {
					required: true,
				},
				StaffName: {
					required: true,
				},
				StaffMobile: {
					required: true,
					//remote: "RemoteValidation?Action=MobileValidation&Id=StaffMobile",
				},
				StaffDOJ: {
					required: true,
				}
			},
			messages: {
				StaffPosition: {
					required: "Please select this!!",
				},
				StaffName: {
					required: "Please enter this!!",
				},
				StaffMobile: {
					required: "Please enter this!!",
					//remote: jQuery.format("<?php echo $MOBILENUMBERDIGIT; ?> Digit Mobile number!!")
				},
				StaffDOJ: {
					required: "Please enter this!!",
				}
			}   
		});  
		$(function() {
			var baseURL = 'StaffAjaxTab';
			$('#StaffProfile').load(baseURL+'?Action=StaffProfile&Id=<?php echo $GetStaffId; ?>', function() {
				$('#myTabs').tab();
			});    
			$('#myTabs').bind('show', function(e) {    
			   var pattern=/#.+/gi
			   var contentID = e.target.toString().match(pattern)[0];
				$(contentID).load(baseURL+contentID.replace('#','?Id=<?php echo $GetStaffId; ?>&Action='), function(){
					$('#myTabs').tab();
				});
			});
		});		
	});
</script>
<?php
include("Template/Footer.php");
?>