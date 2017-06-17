<?php
$PageName="ManageSCIndicator";
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
                <?php $BreadCumb="Manage Scholastic Co-Scholastic Indicators"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification();?>
				

				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$UniqueId=isset($_GET['UniqueId']) ? $_GET['UniqueId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$SCIndicatorName=$SCAreaId=$count1="";
				if($UniqueId!="" && ($Action=="UpdateSCIndicator" || $Action=="DeleteSCIndicator"))
				{
					$query1="select * from scindicator where SCIndicatorId='$UniqueId' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="UpdateSCIndicator")
					{
						$row1=mysqli_fetch_array($check1);
						$SCIndicatorName=$row1['SCIndicatorName'];
						$SCAreaId=$row1['SCAreaId'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageSCIndicator><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateSCIndicatorId=$UniqueId;
					}
				}

				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add SC Indicator";
				}

					$query2="select SCAreaName,SCAreaId,MasterEntryValue from scarea,masterentry where 
						scarea.SCPartId=masterentry.MasterEntryId and masterentry.MasterEntryStatus='Active' and
						scarea.SCAreaStatus='Active' and scarea.Session='$CURRENTSESSION' order by SCAreaName";
					$check2=mysqli_query($CONNECTION,$query2);
					$ListAllSCArea="";
					while($row2=mysqli_fetch_array($check2))
					{
						$SelectSCAreaName=$row2['SCAreaName'];
						$SelectPartName=$row2['MasterEntryValue'];
						$SelectSCAreaId=$row2['SCAreaId'];
						if($SCAreaId==$SelectSCAreaId)
						$Selected="selected";
						else
						$Selected="";
						$ListAllSCArea.="<option value=\"$SelectSCAreaId\" $Selected>$SelectSCAreaName ($SelectPartName)</option>";
					}
					$query="select SCIndicatorName,SCAreaName,SCIndicatorId,MasterEntryValue from scarea,scindicator,masterentry where 
						Session='$CURRENTSESSION' and 
						SCAreaStatus='Active' and
						scarea.SCAreaId=scindicator.SCAreaId and
						scarea.SCPartId=masterentry.MasterEntryId 
						order by SCAreaName";
					$DATA=array();
					$QA=array();
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					while($row=mysqli_fetch_array($result))
					{
						$ListSCAreaName=$row['SCAreaName'];	
						$ListSCIndicatorName=$row['SCIndicatorName'];	
						$ListSCIndicatorId=$row['SCIndicatorId'];	
						$ListSCPartName=$row['MasterEntryValue'];	
						$ListSCAreaName.=" <b>($ListSCPartName)</b>";
						$Edit="<a href=ManageSCIndicator/UpdateSCIndicator/$ListSCIndicatorId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
						$QA[]=array($ListSCAreaName,$ListSCIndicatorName,$Edit);
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
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageSCIndicator" id="ManageSCIndicator" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SCIndicatorName">Indicator Name</label>
												<input tabindex="1" class="span8" id="SCIndicatorName" type="text" name="SCIndicatorName" value="<?php echo $SCIndicatorName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SCAreaId">Area</label>
												<div class="controls sel span8">  
												<select tabindex="3" name="SCAreaId" id="SCAreaId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllSCArea; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageSCIndicator" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="SCIndicatorId" value="<?php echo $UpdateSCIndicatorId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,4); ?>
								</form>
                            </div>
						</div>
					</div>
					<div class="span8">
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Listing All Scholastic Co-Scholastic Indicators</span>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content clearfix noPad">
								<table id="SCIndicatorTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Area Name</th>
											<th>Indicator Name</th>
											<th><span class="icon-edit tip" title="Update"></span></th>
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
$("#SCAreaId").select2();
$('#SCAreaId').select2({placeholder: "Select"}); 	
$('#SCIndicatorTable').dataTable({
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

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageSCIndicator").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SCAreaId: {
				required: true,
			},
			SCIndicatorName: {
				required: true,
			}
		},
		messages: {
			SCAreaId: {
				required: "Please select this!!",
			},
			SCIndicatorName: {
				required: "Please enter this!!",
			}
		}   
	});
});
</script>
		
<?php
include("Template/Footer.php");
?>