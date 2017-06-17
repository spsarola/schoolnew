<?php
$PageName="FollowUp";
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
                <?php $BreadCumb="Follow Up"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
				$FollowUpType=isset($_GET['Action']) ? $_GET['Action'] : '';
				$FAction=isset($_GET['FAction']) ? $_GET['FAction'] : '';
				$FollowUpUniqueId=isset($_GET['Id']) ? $_GET['Id'] : '';
				$FId=isset($_GET['FId']) ? $_GET['FId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$table=$field=$fieldStatus=$Name=$Mobile=$FId=$NextFollowUpDate=$DOF=$Remarks=$Address=$ResponseDetail=$count20=$count10="";
				if($FollowUpType=="Call")
				{
					$table="calling";
					$field="CallId";	
					$fieldStatus="CallStatus";
				}	
				elseif($FollowUpType=="Enquiry")
				{
					$table="enquiry";
					$field="EnquiryId";		
					$fieldStatus="EnquiryStatus";
				}
				
				$query20="select * from $table where $field='$FollowUpUniqueId' ";
				$check20=mysqli_query($CONNECTION,$query20);
				$count20=mysqli_num_rows($check20);
				if($count20==0)
				{
					echo "<div class=\"row-fluid\">
						<div class=\"span12\">";
					$Message="This is not a valid URL!!";
					$Type="alert-error";
					ShowNotification($Message,$Type);
					echo "</div>
						</div>";
				}
				else
				{
				$row20=mysqli_fetch_array($check20);
				$Name=$row20['Name'];
				$Mobile=$row20['Mobile'];
				if($FId!="")
				{
					$query10="select * from followup where FollowUpId='$FId' and FollowUpStatus='Active' ";
					$check10=mysqli_query($CONNECTION,$query10);
					$count10=mysqli_num_rows($check10);
					if($count10>0 && $FAction=="Update")
					{
						$row10=mysqli_fetch_array($check10);
						$ResponseDetail=$row10['ResponseDetail'];
						$Remarks=$row10['Remarks'];
						$NextFollowUpDate=$row10['NextFollowUpDate'];
						if($NextFollowUpDate!="")
						$NextFollowUpDate=date("d-m-Y H:i",$row10['NextFollowUpDate']);
						$DOF=date("d-m-Y H:i",$row10['DOF']);
						$ButtonContent="Update";
						$UpdateFollowUpId=$FId;
						$ButtonContentSet=1;
						$AddButton="Update <a href=FollowUp/$FollowUpType/$FollowUpUniqueId><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
					}
					elseif($count10>0 && $FAction=="Delete")
					{
						$DeleteFollowUpName=$row20['Name'];	
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span4">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Follow Up of <?php echo "$Name ($Mobile) $AddButton"; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>				
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageFollowUp" id="ManageFollowUp" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="DOF">Follow Up Date</label>
												<input tabindex="1" class="span8" type="text" name="DOF" id="DOF" value="<?php echo $DOF; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="NextFollowUpDate">Next Follow Up Date</label>
												<input tabindex="2" class="span8" type="text" name="NextFollowUpDate" id="NextFollowUpDate" value="<?php echo $NextFollowUpDate; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="ResponseDetail" readonly>Response</label>
												<div class="controls-textarea span8">
												<textarea tabindex="3" id="ResponseDetail" name="ResponseDetail" class="span12"><?php echo $ResponseDetail; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Remarks" readonly>Remarks</label>
												<div class="controls-textarea span8">
												<textarea tabindex="4" id="Remarks" name="Remarks" class="span12"><?php echo $Remarks; ?></textarea>
												</div>
											</div>
										</div>
									</div>
										<?php if($count10>0) { echo "<input type=\"hidden\" name=\"FollowUpId\" value=\"$UpdateFollowUpId\" readonly>"; } ?>
											<input type="hidden" name="Action" value="ManageFollowUp" readonly>
											<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
											<input type="hidden" name="FollowUpType" value="<?php echo $FollowUpType; ?>" readonly>
											<input type="hidden" name="FollowUpUniqueId" value="<?php echo $FollowUpUniqueId; ?>" readonly>
										   <?php ActionButton($ButtonContent,5); ?>
								</form>
							</div>
						</div>
					</div>
					<div class="span8">
					<?php
					if($FAction=="Delete" && $count10>0)
					{
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Follow Up for "<?php echo $DeleteFollowUpName; ?>" ??</span>
								</h4>
								<br><a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteFollowUp" id="DeleteFollowUp" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input tabindex="21" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteFollowUp" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="FollowUpId" value="<?php echo $FId; ?>" readonly />
									<input type="hidden" name="FollowUpType" value="<?php echo $FollowUpType; ?>" readonly />
									<input type="hidden" name="FollowUpUniqueId" value="<?php echo $FollowUpUniqueId; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							</div>
						</div>
					<?php
					}
					$query="select * from followup where FollowUpType='$FollowUpType' and FollowUpUniqueId='$FollowUpUniqueId' and FollowUpStatus='Active' order by DOF";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ResponseDetail=$row['ResponseDetail'];	
						$Remarks=$row['Remarks'];	
						$FollowUpId=$row['FollowUpId'];	
						$NextFollowUpDate=$row['NextFollowUpDate'];
						$Date=date("d M Y, D h:i a",$row['DOF']);
						if($NextFollowUpDate!="")
						$NextFollowUpDate=date("d M Y, D h:i a",$NextFollowUpDate);
						else
						$NextFollowUpDate="No";
						$Edit="<a href=FollowUp/$FollowUpType/$FollowUpUniqueId/Update/$FollowUpId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$Delete="<a href=FollowUp/$FollowUpType/$FollowUpUniqueId/Delete/$FollowUpId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
						$QA[]=array($ResponseDetail,$Remarks,$Date,$NextFollowUpDate,$Edit,$Delete);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Follow Up List of <?php echo "$Name ($Mobile)"; ?></span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<table id="FollowUpTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
								<thead>
									<tr>
										<th>Response</th>
										<th>Remarks</th>
										<th>Date of Follow Up</th>
										<th>Next Follow Up</th>
										<th><span class="icon-edit"></span></th>
										<th><span class="icomoon-icon-cancel"></span></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
				
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {
	$('#FollowUpTable').dataTable({
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
	if($('#DOF').length) {
	$('#DOF').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	if($('#NextFollowUpDate').length) {
	$('#NextFollowUpDate').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageFollowUp").validate({
		rules: {
			ResponseDetail: {
				required: true,
			},
			DOF: {
				required: true,
			}
		},
		messages: {
			ResponseDetail: {
				required: "Please enter response!!",
			},
			DOF: {
				required: "Please select date!!",
			}
		}   
	});
	$("#DeleteFollowUp").validate({
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