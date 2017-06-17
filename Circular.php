<?php
$PageName="Circular";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
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
                <?php $BreadCumb="Circular"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$CircularId=isset($_GET['Id']) ? $_GET['Id'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$Circular=$Title=$count1=$DateReleased="";
				if($CircularId!="")
				{
					if($USERNAME!="masteruser" && $USERNAME!='webmaster')
					$UsernameQuery=" and Username='$USERNAME' ";
					$query1="select * from circular where CircularId='$CircularId' and CircularStatus='Active' $UsernameQuery";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$Circular=$row1['Circular'];
						$Title=$row1['Title'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=Circular><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateCircularId=$CircularId;
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
					$AddButton="Add Circular";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span7">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize tip" title="Minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageCircular" id="ManageCircular" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="Title">Title</label>
												<input tabindex="1" class="span8 tip" title="Mandatory : Any Title (Can be alphanumeric) id="Title" type="text" name="Title" value="<?php echo $Title; ?>" />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<div class="span12 controls-textarea">
												<textarea tabindex="3" class="ckeditor" id="Circular" name="Circular" class="span12"><?php echo $Circular; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="ManageCircular" readonly>
									   <?php if($count1>0) { ?>
									   <input type="hidden" name="CircularId" value="<?php echo $UpdateCircularId; ?>" readonly>											   
									   <?php } ?>
									<?php ActionButton($ButtonContent,5); ?>
								</form>
                            </div>
                        </div>
                    </div>
					
					<div class="span5">
					<?php
					if($Action=="Delete" && $count1>0)
					{
					?>
					
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Delete Circular "<?php echo $DeleteTitle; ?>" ??</span>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteCircular" id="DeleteCircular" method="Post">
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
									<input type="hidden" name="Action" value="DeleteCircular" readonly />
									<input type="hidden" name="CircularId" value="<?php echo $CircularId; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							</div>
						</div>
					
					<?php
					}
					?>
					<?php
						$query="select * from circular where CircularStatus='Active' order by DateReleased desc";
						$result=mysqli_query($CONNECTION,$query);
						$count=mysqli_num_rows($result);
						$DATA=array();
						$QA=array();
						$PrintCircularList3="";
						while($row=mysqli_fetch_array($result))
						{
							$ListCircularId=$row['CircularId'];
							$ListTitle=$row['Title'];	
							$ListDateReleased=date("d M Y",$row['DateReleased']);
							$Edit="<a href=Circular/Update/$ListCircularId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
							$Delete="<a href=Circular/Delete/$ListCircularId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
							$Note="<a href=Note/Circular/$ListCircularId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"silk-icon-notes tip\" title=\"Write Note\"></span></a>";
							$PrintCircularList3.="<tr class=\"odd gradeX\">
									<td>$ListTitle</td>
									<td>$ListDateReleased</td>
								</tr>";
							$QA[]=array($ListTitle,$ListDateReleased,$Edit,$Delete);
						}
						$DATA['aaData']=$QA;
						$fp = fopen('plugins/Data/data1.txt', 'w');
						fwrite($fp, json_encode($DATA));
						fclose($fp);
						?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Circular List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintCircularList" readonly>
										<input type="hidden" name="HeadingName" value="PrintCircularHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Circular List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintCircularList1="<table id=\"CircularTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Title</th>";
											echo $PrintCircularList1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
										$PrintCircularList2="</tr>
									</thead>
									<tbody>";
									echo $PrintCircularList2;
									$PrintCircularList4="</tbody>
								</table>";
									echo $PrintCircularList4;
									$PrintCircularList="$PrintCircularList1 $PrintCircularList2 $PrintCircularList3 $PrintCircularList4";
									$_SESSION['PrintCircularList']=$PrintCircularList;
									$PrintCircularHeading="Showing List of Circular";
									$_SESSION['PrintCircularHeading']=$PrintCircularHeading;
							?>
							</div>
						</div>
					</div>
                </div>				
				
            </div>
        </div>
<script type="text/javascript">
$(document).ready(function() {
	$('#CircularTable').dataTable({
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
	if($('#DateReleased').length) {
	$('#StartTime').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageCircular").validate({
		rules: {
			DateReleased: {
				required: true,
			},
			Title: {
				required: true,
			}
		},
		messages: {
			DateReleased: {
				required: "Please enter Start Time!!",
			},
			Title: {
				required: "Please enter Title!!",
			}
		}   
	});
	$("#DeleteCircular").validate({
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