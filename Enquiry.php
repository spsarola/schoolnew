<?php
$PageName="Enquiry";
$FormRequired=1;
$TableRequired=1;
$TooltipRequired=1;
$SearchRequired=1;
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
                <?php $BreadCumb="Enquiry"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$EId=isset($_GET['EnquiryId']) ? $_GET['EnquiryId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$EnquiryType=$Reference=$EnquiryResponse=$EnquiryDate=$Name=$Mobile=$NoOfChild=$AlternateMobile=$Remarks=$Address=$UpdateEnquiryId=$ResponseDetail=$count1="";
				if($EId!="")
				{
					$query1="select * from enquiry where EnquiryId='$EId' and EnquiryStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$EnquiryType=$row1['EnquiryType'];
						$Reference=$row1['Reference'];
						$EnquiryResponse=$row1['EnquiryResponse'];
						$EnquiryDate=date("d-m-Y H:i",$row1['EnquiryDate']);
						$Name=$row1['Name'];
						$Mobile=$row1['Mobile'];
						$AlternateMobile=$row1['AlternateMobile'];
						$NoOfChild=$row1['NoOfChild'];
						$Address=br2nl($row1['Address']);
						$ResponseDetail=br2nl($row1['ResponseDetail']);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=Enquiry><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateEnquiryId=$EId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteEnquiryName=$row1['Name'];	
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Enquiry";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Manage Enquiry</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
								<div class="content" style="width:98%; margin-bottom:10px; float:left; clear:both; ">
									<form class="form-horizontal" action="Action" name="ManageEnquiry" id="ManageEnquiry" method="Post">
										<div class="span4">
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
													<label class="form-label span4" for="EnquiryType">Enquiry Type</label> 
														<div class="span8 controls sel">   
															<?php
															GetCategoryValue('EnquiryType','EnquiryType',$EnquiryType,'','','','',1,'');
															?>
														</div> 
													</div>
												</div> 
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
													<label class="form-label span4" for="Reference">Reference</label> 
														<div class="span8 controls sel">   
															<?php
															GetCategoryValue('Reference','Reference',$Reference,'','','','',2,'');
															?>
														</div> 
													</div>
												</div> 
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
													<label class="form-label span4" for="EnquiryResponse">Response </label> 
														<div class="span8 controls sel">   
															<?php
															GetCategoryValue('EnquiryResponse','EnquiryResponse',$EnquiryResponse,'','','','',3,'');
															?>
														</div> 
													</div>
												</div> 
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="EnquiryDate">Date of Enquiry</label>
														<input tabindex="4" class="span8" type="text" name="EnquiryDate" id="EnquiryDate" value="<?php echo $EnquiryDate; ?>" readonly />
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="Name">Name</label>
														<input tabindex="5" class="span8" id="Name" type="text" name="Name" value="<?php echo $Name; ?>" />
													</div>
												</div>
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="Mobile">Mobile</label>
														<input tabindex="6" class="span8" id="Mobile" type="text" name="Mobile" value="<?php echo $Mobile; ?>" />
													</div>
												</div>
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="AlternateMobile" readonly>Alternate Mobile</label>
														<input tabindex="7" class="span8" id="AlternateMobile" type="text" name="AlternateMobile" value="<?php echo $AlternateMobile; ?>" />
													</div>
												</div>
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="NoOfChild" readonly>No Of Child</label>
														<input tabindex="8" class="span8" id="NoOfChild" type="text" name="NoOfChild" value="<?php echo $NoOfChild; ?>" />
													</div>
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="Address" readonly>Address</label>
														<div class="span8 controls-textarea">
														<textarea tabindex="9" id="Address" name="Address" class="span12"><?php echo $Address; ?></textarea>
														</div>
													</div>
												</div>
											</div>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="ResponseDetail" readonly>Response</label>
														<div class="span8 controls-textarea">
														<textarea tabindex="10" id="ResponseDetail" name="ResponseDetail" class="span12"><?php echo $ResponseDetail; ?></textarea>
														</div>
													</div>
												</div>
											</div>
											<?php if($count1>0) { echo "<input type=\"hidden\" name=\"EnquiryId\" value=\"$UpdateEnquiryId\" readonly>"; } ?>
											<input type="hidden" name="Action" value="ManageEnquiry" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										    <?php ActionButton($ButtonContent,11); ?>
										</div>
									</form>
								</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">	
					<div class="span12">
					<?php
					$CallEnquiry=0;
					if($Action=="Delete" && $count1>0)
					{
						$query2="select Count(FollowUpId) from followup where FollowUpUniqueId='$EId' and FollowUpStatus='Active' and FollowUpType='Enquiry'";
						
						$check2=mysqli_query($CONNECTION,$query2);
						while($row2=mysqli_fetch_array($check2))
						$CallEnquiry+=$row2['Count(FollowUpId)'];
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Enquiry "<?php echo $DeleteEnquiryName; ?>" ??</span>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php if($CallEnquiry>0) { ?>
								<br><div class="alert alert-error">This enquiry has some follow ups. Delete them first!!</div>
							<?php } else { ?>
								<form class="form-horizontal" action="ActionDelete" name="DeleteEnquiry" id="DeleteEnquiry" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Password">Password</label>
												<input tabindex="21" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteEnquiry" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="EnquiryId" value="<?php echo $EId; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							<?php } ?>
							</div>
						</div>
					<?php
					}
					$query="select * from enquiry,masterentry where enquiry.EnquiryResponse=masterentry.MasterEntryId and EnquiryStatus='Active' order by EnquiryDate";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					$PrintEnquiry3="";
					while($row=mysqli_fetch_array($result))
					{
						$EnquiryId=$row['EnquiryId'];	
						$Name=$row['Name'];	
						$Mobile=$row['Mobile'];	
						$NoOfChild=$row['NoOfChild'];
						$EnquiryResponse=$row['MasterEntryValue'];
						$Tag="<b>$EnquiryResponse</b>";
						$Date=date("d M Y, D h:i a",$row['EnquiryDate']);
						$Edit="<a href=/Enquiry/Update/$EnquiryId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$ActionConfirmMessage="Are you sure want to delete?";
						$ActionConfirm=ActionConfirm($ActionConfirmMessage);
						$Delete="<a href=Enquiry/Delete/$EnquiryId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$Note="<a href=\"Note/Enquiry/$EnquiryId\" data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"silk-icon-notes\"></span></a>";
						$FollowUp="<a href=FollowUp/Enquiry/$EnquiryId><span class=\"brocco-icon-phone tip\" title=\"Follow Up\"></span></a>";
						$Name.=" ($Tag)";
						$QA[]=array($Name,$Mobile,$NoOfChild,$Date,$FollowUp,$Edit,$Delete);
						$PrintEnquiry3.="<tr class=\"odd gradeX\">
								<td>$Name</td>
								<td>$Mobile</td>
								<td>$NoOfChild Child</td>
								<td>$Date</td>
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
									<span>Enquiry List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
										<input type="hidden" name="SessionName" value="PrintEnquiryList" readonly>
										<input type="hidden" name="HeadingName" value="PrintEnquiryHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Enquiry List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php
								$PrintEnquiry1="<table id=\"EnquiryTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Name</th>
											<th>Mobile</th>
											<th>No Of Child</th>
											<th>Date of Enquiry</th>";
											echo $PrintEnquiry1;
											echo "<th><span class=\"brocco-icon-phone tip\" title=\"Follow Up\"></span></th>
											<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
										$PrintEnquiry2="</tr>
									</thead>
									<tbody>";
									echo $PrintEnquiry2;
									$PrintEnquiry4="</tbody>
								</table>";
								echo $PrintEnquiry4;
								$PrintEnquiryList="$PrintEnquiry1 $PrintEnquiry2 $PrintEnquiry3 $PrintEnquiry4";
								$_SESSION['PrintEnquiryList']=$PrintEnquiryList;
								$PrintEnquiryHeading="Showing List of Enquiry";
								$_SESSION['PrintEnquiryHeading']=$PrintEnquiryHeading;
								$_SESSION['PrintCategory']="Enquiry";
								?>
							</div>
						</div>
					</div>
				</div>				
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {
	$('#EnquiryTable').dataTable({
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
	$("#EnquiryType").select2();
	$("#Reference").select2();
	$("#EnquiryResponse").select2();
	if($('#EnquiryDate').length) {
	$('#EnquiryDate').datetimepicker({ yearRange: "-10:+10",dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$('#EnquiryType').select2({placeholder: "Select"});
	$('#EnquiryResponse').select2({placeholder: "Select"});
	$('#Reference').select2({placeholder: "Select"});
	$("#ManageEnquiry").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			EnquiryType: {
				required: true,
			},
			Name: {
				required: true,
			},
			NoOfChild: {
				required: true,
				remote: "RemoteValidation?Action=IsNumeric&Id=NoOfChild"
			},
			EnquiryResponse: {
				required: true,
			},
			Mobile: {
				required: true,
				remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
			},
			AlternateMobile: {
				remote: "RemoteValidation?Action=MobileValidation&Id=AlternateMobile"
			},
			Reference: {
				required: true,
			},
			Address: {
				required: true,
			},
			ResponseDetail: {
				required: true,
			},
			EnquiryDate: {
				required: true,
			}
		},
		messages: {
			EnquiryType: {
				required: "Please select this!!",
			},
			Name: {
				required: "Please enter this!!",
			},
			NoOfChild: {
				required: "Please enter this!!",
				remote: jQuery.format("Only numeric allowed!!"),
			},
			EnquiryResponse: {
				required: "Please select Response!!",
			},
			Mobile: {
				required: "Please enter this!!",
				remote: jQuery.format("Should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			},
			AlternateMobile: {
				remote: jQuery.format("Should be <?php echo $MOBILENUMBERDIGIT; ?> digit Numeric!!"),
			},
			Reference: {
				required: "Please select this!!",
			},
			Address: {
				required: "Please enter this!!",
			},
			ResponseDetail: {
				required: "Please enter this!!",
			},
			EnquiryDate: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#DeleteEnquiry").validate({
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