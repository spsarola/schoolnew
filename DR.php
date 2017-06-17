<?php
$PageName="DR";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
$DRType=isset($_GET['DRType']);
if(($DRType!="Dispatch" && $DRType!="Receiving") || $DRType=="Dispatch")
{
$DRTypeName="<a href=DR/Receiving><span class=\"badge badge-success\">Receiving Register</span></a>";
$DRType="Dispatch";
$DRTypeRegister="Dispatch Register";
$Address="AddressTo";
}
else
{
$DRTypeName="<a href=DR/Dispatch><span class=\"badge badge-success\">Dispatch Register</span></a>";
$DRType="Receiving";
$DRTypeRegister="Receiving Register";
$Address="AddressFrom";
}
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
                <?php $BreadCumb="$DRTypeRegister $DRTypeName"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<?php
				$Action=isset($_GET['Action']);
				$Id=isset($_GET['Id']);
				$ButtonContent=$ButtonContentSet=$AddButton=$UpdateId=$count1=$AddressValue=$Print3=$Remarks=$D=$DRType=$Title=$Reference="";
				if($Id!="")
				{
					$query1="select * from drregister where Id='$Id' and DRStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Reference=$row1['Reference'];
						$Title=$row1['Title'];
						$DRType=$row1['DRType'];
						$D=date("d-m-Y H:i",$row1['Date']);
						$Remarks=br2nl($row1['Remarks']);
						if($DRType=="Dispatch")
						$AddressValue=br2nl($row1['AddressTo']);
						else
						$AddressValue=br2nl($row1['AddressFrom']);
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=DR/$DRType><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateId=$Id;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteTitle=$row1['Title'];	
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add";
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
								<form class="form-horizontal" action="Action" name="ManageDRRegister" id="ManageDRRegister" method="Post">
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
												<label class="form-label span4" for="Reference">Reference </label> 
													<input tabindex="1" class="span8" id="Reference" type="text" name="Reference" value="<?php echo $Reference; ?>" />
												</div>
											</div> 
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Name">Title</label>
													<input tabindex="2" class="span8" id="Title" type="text" name="Title" value="<?php echo $Title; ?>" />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="D"><?php echo $DRType; ?> Date</label>
													<input tabindex="3" class="span8" type="text" name="D" id="D" value="<?php echo $D; ?>" readonly />
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Address"><?php echo $Address; ?></label>
													<div class="span8 controls-textarea">
													<textarea tabindex="4" id="Address" name="Address" class="span12"><?php echo $AddressValue; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="span4">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="Remarks">Remarks</label>
													<div class="span8 controls-textarea">
													<textarea tabindex="5" id="Remarks" name="Remarks" class="span12"><?php echo $Remarks; ?></textarea>
													</div>
												</div>
											</div>
										</div>
										<?php if($count1>0) { echo "<input type=\"hidden\" name=\"Id\" value=\"$UpdateId\" readonly>"; } ?>
										<input type="hidden" name="Action" value="ManageDRRegister" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<input type="hidden" name="DRType" value="<?php echo $DRType; ?>" readonly>
										<?php ActionButton($ButtonContent,6); ?>
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
									<span>Delete "<?php echo $DeleteTitle; ?>" ??</span>
								</h4>
								<br><a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteDRRegister" id="DeleteDRRegister" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="11" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteDRRegister" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Id" value="<?php echo $Id; ?>" readonly />
									<?php SetDeleteButton(12); ?>
								</form>
							</div>
						</div>
					<?php
					}
					$query="select * from drregister where DRStatus='Active' and DRType='$DRType' order by Date";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ListId=$row['Id'];	
						$ListTitle=$row['Title'];
						$ListReference=$row['Reference'];	
						$ListDRType=$row['DRType'];
						if($ListDRType=="Dispatch")
						$ListAddress=$row['AddressTo'];
						else
						$ListAddress=$row['AddressFrom'];
						$ListRemarks=$row['Remarks'];
						$ListDate=GetDateFormat($row['Date']);
						$Edit="<a href=DR/$DRType/Update/$ListId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$Delete="<a href=DR/$DRType/Delete/$ListId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$QA[]=array($ListReference,$ListTitle,$ListAddress,$ListDate,$ListRemarks,$Edit,$Delete);
						$Print3.="<tr class=\"odd gradeX\">
								<td>$Reference</td>
								<td>$Title</td>
								<td>$ListAddress</td>
								<td>$ListDate</td>
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
									<span><?php echo $DRType; ?> List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintDRList" readonly>
										<input type="hidden" name="HeadingName" value="PrintDRHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print <?php echo $DRType; ?> List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$Print1="<table id=\"DRTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Reference</th>
											<th>Title</th>
											<th>$DRType Address</th>
											<th>$DRType Date</th>
											<th>Remarks</th>";
											echo $Print1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
											$Print2="</tr>
									</thead>
									<tbody>";
									echo $Print2;
									$Print4="</tbody>
								</table>";
								echo $Print4;
								$PrintList="$Print1 $Print2 $Print3 $Print4";
								$_SESSION['PrintDRList']=$PrintList;
								$PrintHeading="Showing List of $DRType";
								$_SESSION['PrintDRHeading']=$PrintHeading;
							?>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
 	
<script type="text/javascript">
	$(document).ready(function() {	
		$('#DRTable').dataTable({
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
		if($('#D').length) {
		$('#D').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}
		$("input, textarea, select").not('.nostyle').uniform();
		$("#ManageDRRegister").validate({
			rules: {
				Reference: {
					required: true,
				},
				Title: {
					required: true,
				},
				Address: {
					required: true,
				},
				D: {
					required: true,
				}
			},
			messages: {
				Reference: {
					required: "Please enter this!!",
				},
				Title: {
					required: "Please enter this!!",
				},
				Address: {
					required: "Please enter this!!",
				},
				D: {
					required: "Please enter Date and Time!!",
				}
			}   
		});
		$("#DeleteDRRegister").validate({
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