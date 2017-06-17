<?php
$PageName="MasterEntry";
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
                <?php $BreadCumb="Master Entry"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>

				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$MasterEntryId=isset($_GET['MasterEntryId']) ? $_GET['MasterEntryId'] : '';
				$ButtonContentSet=$AddButton=$MasterEntryName=$MasterEntryValue=$count1="";
				if($MasterEntryId!="")
				{
					if($USERTYPE=="Webmaster")
					$query1="select * from masterentry where MasterEntryId='$MasterEntryId' ";
					else
					$query1="select * from masterentry,masterentrycategory where MasterEntryId='$MasterEntryId' and masterentry.MasterEntryName=masterentrycategory.MasterEntryCategoryValue and Permission!='Webmaster' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$MasterEntryName=$row1['MasterEntryName'];
						$MasterEntryValue=$row1['MasterEntryValue'];
						$MasterEntryStatus=$row1['MasterEntryStatus'];
						if($MasterEntryStatus=="Active")
						$MasterEntryStatusChecked="Checked=checked";
						else
						$MasterEntryStatusChecked="";
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=MasterEntry><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateMasterEntryId=$MasterEntryId;
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Entry";
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
								<form class="form-horizontal" action="Action" name="MasterEntry" id="MasterEntry" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="MasterEntryName">Select Name</label> 
												<div class="controls sel span8">   
													<select tabindex="1" name="MasterEntryName" id="MasterEntryName" class="nostyle" style="width:100%;">
														<option></option>
														<?php
															if($USERTYPE=="Webmaster")
															$checkMasterEntry=mysqli_query($CONNECTION,"select * from masterentrycategory order by MasterEntryCategoryName");
															else
															$checkMasterEntry=mysqli_query($CONNECTION,"select * from masterentrycategory where Permission!='Webmaster' order by MasterEntryCategoryName");
															$Selected="";
															while($rowMasterEntry=mysqli_fetch_array($checkMasterEntry))
															{
																$MasterEntryCategoryName=$rowMasterEntry['MasterEntryCategoryName'];
																$MasterEntryCategoryValue=$rowMasterEntry['MasterEntryCategoryValue'];
																if($MasterEntryCategoryValue==$MasterEntryName)
																$Selected="Selected";
																else
																$Selected="";
																echo "<option value=\"$MasterEntryCategoryValue\" $Selected>$MasterEntryCategoryName</option>";
															}
														?>
													</select>
												</div> 
											</div>
										</div> 
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="MasterEntryValue">Value</label>
												<input tabindex="2" class="span8" id="MasterEntryValue" type="text" name="MasterEntryValue" value="<?php echo $MasterEntryValue; ?>" />
											</div>
										</div>
									</div>
									<?php if($count1>0) { ?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Status</label>
												<input tabindex="2" class="styled" id="MasterEntryStatus" type="checkbox" name="MasterEntryStatus" <?php echo $MasterEntryStatusChecked; ?> value="Active" />
											</div>
										</div>
									</div>
									<?php } ?>
										<input type="hidden" name="Action" value="MasterEntry" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="MasterEntryId" value="<?php echo $UpdateMasterEntryId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,3); ?>
								</form>
                            </div>
                        </div>
						
						<?php if($USERTYPE=="Webmaster") { ?>
						<div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Master Entry Category</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="MasterEntryCategory" id="MasterEntryCategory" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="MasterEntryCategoryName">Name</label>
												<input tabindex="4" class="span8" id="MasterEntryCategoryName" type="text" name="MasterEntryCategoryName" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="MasterEntryCategoryValue">Value</label>
												<input tabindex="5" class="span8" id="MasterEntryCategoryValue" type="text" name="MasterEntryCategoryValue" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Permission">Permission</label>
												<input tabindex="6" class="span8" id="Permission" type="text" name="Permission" placeholder="Webmaster" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="MasterEntryCategory" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<?php $ButtonContent="Add"; ActionButton($ButtonContent,7); ?>
								</form>
                            </div>
                        </div>
						<?php } ?>
                    </div>		

<?php
	if($USERTYPE=="Webmaster")
	$query="select * from masterentry order by MasterEntryValue";
	else
	$query="select * from masterentry,masterentrycategory where masterentry.MasterEntryName=masterentrycategory.MasterEntryCategoryValue and Permission!='Webmaster' order by MasterEntryValue";

	$DATA=array();
	$QA=array();
	$result=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($result);
	while($row=mysqli_fetch_array($result))
	{
		$ListMasterEntryName=$row['MasterEntryName'];	
		$ListMasterEntryId=$row['MasterEntryId'];	
		$ListMasterEntryValue=$row['MasterEntryValue'];
		$ListMasterEntryStatus=$row['MasterEntryStatus'];
		if($ListMasterEntryStatus=="Active")
		$ListMasterEntryStatus="<span class=\"badge badge-success\">Active<span>";
		else
		$ListMasterEntryStatus="<span class=\"badge badge-important\">In Active<span>";
		$Edit="<a href=MasterEntry/Update/$ListMasterEntryId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
		$Note="<a href=Note/MasterEntry/$ListMasterEntryId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
		$ListMasterEntryName.=" $ListMasterEntryStatus";
		$QA[]=array($ListMasterEntryId,$ListMasterEntryName,$ListMasterEntryValue,$Edit,$Note);
	}
	$DATA['aaData']=$QA;
	$fp = fopen('plugins/Data/data1.txt', 'w');
	fwrite($fp, json_encode($DATA));
	fclose($fp);
?>					
					<div class="span8">
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Master Entry Value</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="MasterEntryTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Master Entry Id</th>
											<th>Master Entry Name</th>
											<th>Master Entry Value</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
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
$("#MasterEntryName").select2();
$('#MasterEntryTable').dataTable({
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
	$('#MasterEntryName').select2({placeholder: "Select"});
	$("#MasterEntry").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			MasterEntryName: {
				required: true,
			},
			MasterEntryValue: {
				required: true,
			}
		},
		messages: {
			MasterEntryName: {
				required: "Please select Name!!",
			},
			MasterEntryValue: {
				required: "Please enter Value!!",
			}
		}   
	});
	$("#MasterEntryCategory").validate({
		rules: {
			MasterEntryCategoryName: {
				required: true,
			},
			MasterEntryCategoryValue: {
				required: true,
			}
		},
		messages: {
			MasterEntryCategoryName: {
				required: "Please select Name!!",
			},
			MasterEntryCategoryValue: {
				required: "Please enter Value!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>