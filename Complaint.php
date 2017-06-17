<?php
$PageName="Complaint";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
$EditorRequired=1;
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
                <?php $BreadCumb="Complaint"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$CId=isset($_GET['ComplaintId']) ? $_GET['ComplaintId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$UpdateComplaintId=$Name=$ComplaintType=$Mobile=$ComplaintStatus=$ResolvedChecked=$DOC=$Act=$Description=$ResponseDetail=$count1="";
				if($CId!="")
				{
					$query1="select * from complaint where ComplaintId='$CId' and ComplaintStatus!='Deleted' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Name=$row1['Name'];
						$Mobile=$row1['Mobile'];
						$ComplaintType=$row1['ComplaintType'];
						$ComplaintStatus=$row1['ComplaintStatus'];
						if($ComplaintStatus=="Resolved")
						$ResolvedChecked="checked=checked";
						$Description=$row1['Description'];
						$Act=$row1['Action'];
						$DOC=date("d-m-Y H:i",$row1['DOC']);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=Complaint><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateComplaintId=$CId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteName=$row1['Name'];	
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Complaint";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content" style="width:98%; margin-bottom:10px; float:left; clear:both; ">
								<form class="form-horizontal" action="Action" name="ManageComplaint" id="ManageComplaint" method="Post">
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
												<label class="form-label span4" for="ComplaintType">Complaint Type </label> 
													<div class="span8 controls sel">   
														<?php
														GetCategoryValue('ComplaintType','ComplaintType',$ComplaintType,'','','','',1,'');
														?>
													</div> 
												</div>
											</div> 
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Name">Name</label>
													<input tabindex="2" class="span8" id="Name" type="text" name="Name" value="<?php echo $Name; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Mobile">Mobile</label>
													<input tabindex="3" class="span8" id="Mobile" type="text" name="Mobile" value="<?php echo $Mobile; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="DOC">Date of Complaint</label>
													<input tabindex="4" class="span8" type="text" name="DOC" id="DOC" value="<?php echo $DOC; ?>" readonly />
												</div>
											</div>
										</div>
										<?php if($count1>0) { ?>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Resolved"></label>
													<input tabindex="5" class="styled" type="checkbox" name="Resolved" id="Resolved" value="Yes" <?php echo $ResolvedChecked; ?> />
													Check if the complaint is solved!!
												</div>
											</div>
										</div>										
										<?php } ?>
										<?php if($count1>0) { echo "<input type=\"hidden\" name=\"ComplaintId\" value=\"$UpdateComplaintId\" readonly>"; } ?>
											<input type="hidden" name="Action" value="ManageComplaint" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										   <?php ActionButton($ButtonContent,10); ?>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<div class="span12 controls-textarea">
													Description<br>
													<textarea tabindex="6" id="Description" name="Description" class="span12 ckeditor"><?php echo $Description; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													Action<br>
													<div class="span12 controls-textarea">
													<textarea tabindex="7" id="Act" name="Act" class="span12 ckeditor"><?php echo $Act; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">	
					<div class="span12">
					<?php
					if($Action=="Delete" && $count1>0)
					{
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Complaint from "<?php echo $DeleteName; ?>" ??</span>
								</h4>
								<br><a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteComplaint" id="DeleteComplaint" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="21" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteComplaint" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="ComplaintId" value="<?php echo $CId; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							</div>
						</div>
					<?php
					}
					$query="select * from complaint,masterentry where complaint.ComplaintType=masterentry.MasterEntryId and complaint.ComplaintStatus!='Deleted' order by DOC";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					$PrintComplaint3="";
					$Tag="";
					while($row=mysqli_fetch_array($result))
					{
						$ListComplaintId=$row['ComplaintId'];
						$ListName=$row['Name'];	
						$ListMobile=$row['Mobile'];	
						$ListComplaintTypeName=$row['MasterEntryValue'];
						$ListComplaintStatus=$row['ComplaintStatus'];
						$ListDescription=$row['Description'];
						$ListAction=$row['Action'];
						if($ListComplaintStatus=="Fresh")
						$Tag="<span class=\"date badge badge-important\">Fresh</span>";
						elseif($ListComplaintStatus=="Resolved")
						$Tag="<span class=\"date badge badge-success\">Resolved</span>";
						$Date=date("d M Y,h:ia",$row['DOC']);
						$Edit="<a href=Complaint/Update/$ListComplaintId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$Delete="<a href=Complaint/Delete/$ListComplaintId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$ListName.=" $Tag";
						$QA[]=array($ListName,$ListMobile,$ListComplaintTypeName,$Date,$ListDescription,$ListAction,$Edit,$Delete);
						$PrintComplaint3.="<tr class=\"odd gradeX\">
								<td>$ListName ($Tag)</td>
								<td>$ListComplaintTypeName</td>
								<td>$Date</td>
								<td>$ListDescription</td>
								<td>$ListAction</td>
							</tr>";
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Complaint List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
										<input type="hidden" name="SessionName" value="PrintComplaintList" readonly>
										<input type="hidden" name="HeadingName" value="PrintComplaintHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Complaint List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintComplaint1="<table id=\"ComplaintTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Name</th>
											<th>Mobile</th>
											<th>Complaint Type</th>
											<th>Date</th>
											<th>Description</th>";
											echo $PrintComplaint1;
											echo "<th>Action</th><th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
											$PrintComplaint2="</tr>
									</thead>
									<tbody>";
									echo $PrintComplaint2;
									$PrintComplaint4="</tbody>
								</table>";
								echo $PrintComplaint4;
								$PrintComplaintList="$PrintComplaint1 $PrintComplaint2 $PrintComplaint3 $PrintComplaint4";
								$_SESSION['PrintComplaintList']=$PrintComplaintList;
								$PrintComplaintHeading="Showing List of Complaint";
								$_SESSION['PrintComplaintHeading']=$PrintComplaintHeading;
								$_SESSION['PrintCategory']="Complaint";
							?>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
 	
<script type="text/javascript">
	$(document).ready(function() {	
		$('#ComplaintTable').dataTable({
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
		$("#ComplaintType").select2();
		if($('#DOC').length) {
		$('#DOC').datetimepicker({ yearRange: "-10:+10",dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$('#ComplaintType').select2({placeholder: "Select"});
		$("#ManageComplaint").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Name: {
					required: true,
				},
				ComplaintType: {
					required: true,
				},
				Mobile: {
					remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
				},
				DOC: {
					required: true,
				}
			},
			messages: {
				Name: {
					required: "Please enter Name!!",
				},
				ComplaintType: {
					required: "Please select this!!",
				},
				Mobile: {
					remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
				},
				DOC: {
					required: "Please enter Date and Time!!",
				}
			}   
		});
		$("#DeleteComplaint").validate({
			rules: {
				Password: {
					required: true,
				}
			},
			messages: {
				Password: {
					required: "Please enter this!!",
				}
			}   
		});
	});
</script>   
<?php
include("Template/Footer.php");
?>