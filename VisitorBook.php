<?php
$PageName="VisitorBook";
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
                <?php $BreadCumb="Visitor Book"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$Id=isset($_GET['VisitorBookId']) ? $_GET['VisitorBookId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$UpdateId=$Name=$Purpose=$Mobile=$InDateTime=$NoOfPeople=$Description=$DOC=$OutDateTime=$count1="";
				if($Id!="")
				{
					$query1="select * from visitorbook where VisitorBookId='$Id' and VisitorBookStatus!='Deleted' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Name=$row1['Name'];
						$Mobile=$row1['Mobile'];
						$Purpose=$row1['Purpose'];
						$NoOfPeople=$row1['NoOfPeople'];
						$Description=$row1['Description'];
						$InDateTime=date("d-m-Y H:i",$row1['InDateTime']);
						if($row1['OutDateTime']!="")
						$OutDateTime=date("d-m-Y H:i",$row1['OutDateTime']);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=VisitorBook><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateId=$Id;
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
					$AddButton="Add Visitors";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span3">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content">
								<form class="form-horizontal" action="Action" name="ManageVisitorBook" id="ManageVisitorBook" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="Purpose">Purpose</label> 
												<div class="span8 controls sel">   
													<?php
													GetCategoryValue('GuestVistingPurpose','Purpose',$Purpose,'','','','',1,'');
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
												<label class="form-label span4" for="NoOfPeople">No of People</label>
												<input tabindex="3" class="span8" id="NoOfPeople" type="text" name="NoOfPeople" value="<?php echo $NoOfPeople; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="InDateTime">In Date Time</label>
												<input tabindex="4" class="span8" type="text" name="InDateTime" id="InDateTime" value="<?php echo $InDateTime; ?>" readonly />
											</div>
										</div>
									</div>
									<?php if($count1>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="OutDateTime">Out Date Time</label>
												<input tabindex="5" class="span8" type="text" name="OutDateTime" id="OutDateTime" value="<?php echo $OutDateTime; ?>" readonly />
											</div>
										</div>
									</div>									
									<?php } ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Description">Description</label>
												<div class="span8 controls-textarea">
												<textarea tabindex="6" id="Description" name="Description" class="span12"><?php echo $Description; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php if($count1>0) { echo "<input type=\"hidden\" name=\"VisitorBookId\" value=\"$UpdateId\" readonly>"; } ?>
										<input type="hidden" name="Action" value="ManageVisitorBook" readonly>
									   <?php ActionButton($ButtonContent,10); ?>
								</form>
							</div>
						</div>
					</div>
					<div class="span9">
					<?php
					if($Action=="Delete" && $count1>0)
					{
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Visiting Record of "<?php echo $DeleteName; ?>" ??</span>
								</h4>
								<br><a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteVisitorBook" id="DeleteVisitorBook" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="21" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="DeleteVisitorBook" readonly />
									<input type="hidden" name="VisitorBookId" value="<?php echo $Id; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							</div>
						</div>
					<?php
					}
					$query="select * from visitorbook,masterentry where visitorbook.Purpose=masterentry.MasterEntryId and visitorbook.VisitorBookStatus!='Deleted' order by InDateTime";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					$PrintVisitorBook3="";
					while($row=mysqli_fetch_array($result))
					{
						$ListVisitorBookId=$row['VisitorBookId'];
						$ListName=$row['Name'];	
						$ListMobile=$row['Mobile'];	
						$ListPurpose=$row['MasterEntryValue'];
						$ListDescription=$row['Description'];
						$ListNoOfPeople=$row['NoOfPeople'];
						$ListInDateTime=GetDateFormat($row['InDateTime']);
						$ListOutDateTime=GetDateFormat($row['OutDateTime']);
						$Edit="<a href=VisitorBook/Update/$ListVisitorBookId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$Delete="<a href=VisitorBook/Delete/$ListVisitorBookId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$QA[]=array($ListName,$ListMobile,$ListPurpose,$ListDescription,$ListNoOfPeople,$ListInDateTime,$ListOutDateTime,$Edit,$Delete);
						$PrintVisitorBook3.="<tr class=\"odd gradeX\">
								<td>$ListName</td>
								<td>$ListMobile</td>
								<td>$ListPurpose</td>
								<td>$ListDescription</td>
								<td>$ListNoOfPeople</td>
								<td>$ListInDateTime</td>
								<td>$ListOutDateTime</td>
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
									<span>Visitor Book Record</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
										<input type="hidden" name="SessionName" value="PrintVisitorBookList" readonly>
										<input type="hidden" name="HeadingName" value="PrintVisitorBookHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Visitor Book List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintVisitorBook1="<table id=\"VisitorBookTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Name</th>
											<th>Mobile</th>
											<th>Purpose</th>
											<th>Description</th>
											<th>People</th>
											<th>In Time</th>
											<th>Out Time</th>";
											echo $PrintVisitorBook1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
											$PrintVisitorBook2="</tr>
									</thead>
									<tbody>";
									echo $PrintVisitorBook2;
									$PrintVisitorBook4="</tbody>
								</table>";
								echo $PrintVisitorBook4;
								$PrintVisitorBookList="$PrintVisitorBook1 $PrintVisitorBook2 $PrintVisitorBook3 $PrintVisitorBook4";
								$_SESSION['PrintVisitorBookList']=$PrintVisitorBookList;
								$PrintVisitorBookHeading="Showing List of Visitor Book";
								$_SESSION['PrintVisitorBookHeading']=$PrintVisitorBookHeading;
								$_SESSION['PrintCategory']="VisitorBook";
							?>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
 	
<script type="text/javascript">
	$(document).ready(function() {	
		$('#VisitorBookTable').dataTable({
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
		$("#Purpose").select2();
		if($('#InDateTime').length) {
		$('#InDateTime').datetimepicker({ yearRange: "-10:+10",dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		if($('#OutDateTime').length) {
		$('#OutDateTime').datetimepicker({ yearRange: "-10:+10",dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$('#Purpose').select2({placeholder: "Select"});
		$("#ManageVisitorBook").validate({
			ignore: 'input[type="hidden"]',
			rules: {
				Name: {
					required: true,
				},
				Purpose: {
					required: true,
				},
				Mobile: {
					required: true,
					remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
				},
				InDateTime: {
					required: true,
				},
				NoOfPeople: {
					required: true,
					remote: "/RemoteValidation?Action=IsAmountWithoutZero&Id=NoOfPeople"
				}
			},
			messages: {
				Name: {
					required: "Please enter Name!!",
				},
				Purpose: {
					required: "Please select this!!",
				},
				Mobile: {
					required: "Please enter this!!",
					remote: jQuery.format("Mobile should be <?php echo $MOBILENUMBERDIGIT;?> digit Numeric!!"),
				},
				InDateTime: {
					required: "Please enter Date and Time!!",
				},
				NoOfPeople: {
					required: "Please enter this!!",
					remote: jQuery.format("Should be numeric!!"),
				}
			}   
		});
		$("#DeleteVisitorBook").validate({
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