<?php
$PageName="ManageLocation";
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
                <?php $BreadCumb="Manage Location"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$LocationId=isset($_GET['LocationId']) ? $_GET['LocationId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$LocationName=$CalledAs=$count1="";
				if($LocationId!="")
				{
					$query1="select * from location where LocationId='$LocationId' and LocationStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$LocationName=$row1['LocationName'];
						$CalledAs=$row1['CalledAs'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=ManageLocation><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateLocationId=$LocationId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteLocationName=$row1['LocationName'];
					}
				}
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Location";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span5">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="Location" id="Location" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Name</label>
												<input tabindex="1" class="span8" id="LocationName" type="text" name="LocationName" value="<?php echo $LocationName; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Called As</label>
												<input tabindex="2" class="span8" id="CalledAs" type="text" name="CalledAs" value="<?php echo $CalledAs; ?>" />
											</div>
										</div>
									</div>
										<input type="hidden" name="Action" value="ManageLocation" readonly>
										<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
										<?php if($count1>0) { ?>
										<input type="hidden" name="LocationId" value="<?php echo $UpdateLocationId; ?>" readonly>
										<?php } ?>
									<?php ActionButton($ButtonContent,3); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span7">
					<?php
					if($Action=="Delete" && $count1>0)
					{
						$query2="select LocationId from location,stockassign,masterentry where
								LocationStatus='Active' and
								Location.LocationId=stockassign.AssignToDetail and
								MasterEntryValue='Location' and
								stockassign.AssignTo=masterentry.MasterEntryId and
								LocationId='$LocationId' ";
						$check2=mysqli_query($CONNECTION,$query2);
						$count2=mysqli_num_rowS($check2);
					?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Location "<?php echo $DeleteLocationName; ?>" ??</span>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php if($count2==0) { ?>
								<form class="form-horizontal" action="ActionDelete" name="DeleteLocationForm" id="DeleteLocationForm" method="Post">
									<br><div class="alert alert-error">You cannot recover it after deletion!!</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Password</label>
												<input class="span8" type="password" name="Password" id="Password" placeholder="Password" />
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DeleteLocation" readonly />
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="LocationId" value="<?php echo $LocationId; ?>" readonly />
									<?php SetDeleteButton($TabIndex=500); ?>
								</form>
								<?php } else { ?>
								<br><div class="alert alert-error">This Location is associated with stock transfer !! Please delete them first!!</div>
								<?php } ?>
							</div>
						</div>
					
					<?php
					}
					$query="select * from location where LocationStatus='Active' order by LocationName ";
					$result=mysqli_query($CONNECTION,$query);
					$count=mysqli_num_rows($result);
					$DATA=array();
					$QA=array();
					while($row=mysqli_fetch_array($result))
					{
						$ListLocationName=$row['LocationName'];	
						$ListCalledAs=$row['CalledAs'];	
						$ListLocationId=$row['LocationId'];	
						$Edit="<a href=ManageLocation/Update/$ListLocationId><span class=\"icon-edit\"></span></a>";
						$Delete="<a href=ManageLocation/Delete/$ListLocationId><span class=\"icomoon-icon-cancel\"></span></a>";
						$QA[]=array($ListLocationName,$ListCalledAs,$Edit,$Delete);
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
					?>
						<div class="box gradient">
							<div class="title">
								<h4><span>Location List</span></h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<table id="LocationTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
									<thead>
										<tr>
											<th>Name</th>
											<th>Called As </th>
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
            </div>
        </div>
	
<script type="text/javascript">
$(document).ready(function() {

	$('#LocationTable').dataTable({
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
	$("#Location").validate({
		rules: {
			LocationName: {
				required: true,
			},
			CalledAs: {
				required: true,
			},
		},
		messages: {
			LocationName: {
				required: "Please enter this!!",
			},
			CalledAs: {
				required: "Please enter this!!",
			}
		}   
	});
	$("#DeleteLocationForm").validate({
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