<?php
$PageName="Calendar";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
$ColorPicker=1;
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
                <?php $BreadCumb="Calendar"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				
				<?php
				$Action=isset($_GET['Action']) ? $_GET['Action'] : '';
				$CalendarId=isset($_GET['CalendarId']) ? $_GET['CalendarId'] : '';
				$ButtonContentSet=$ButtonContent=$AddButton=$EndTime=$StartTime=$Color=$Title=$count1="";
				if($CalendarId!="")
				{
					$query1="select * from calendar where Username='$USERNAME' and CalendarId='$CalendarId' and CalendarStatus='Active' ";
					$check1=mysqli_query($CONNECTION,$query1);
					$count1=mysqli_num_rows($check1);
					if($count1>0 && $Action=="Update")
					{
						$row1=mysqli_fetch_array($check1);
						$EndTime=date("d-m-Y H:i",$row1['EndTime']);
						$StartTime=date("d-m-Y H:i",$row1['StartTime']);
						$Color=$row1['Color'];
						$Title=$row1['Title'];
						$ButtonContent="Update";
						$ButtonContentSet=1;
						$AddButton="Update <a href=Calendar><span class=\"cut-icon-plus-2 addbutton\"> Add</span></a>";
						$UpdateCalendarId=$CalendarId;
					}
					elseif($count1>0 && $Action=="Delete")
					{
						$row1=mysqli_fetch_array($check1);
						$DeleteTitle=$row1['Title'];	
					}
				}
				if($Color=="")
				$Color="#123456";
				if($ButtonContentSet!=1)
				{
					$ButtonContent="Add";
					$AddButton="Add Calendar";
				}
				?>
				
                <div class="row-fluid">
                    <div class="span3">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo $AddButton; ?></span>
                                </h4>
                                <a href="#" class="minimize tip" title="Minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="Action" name="ManageCalendar" id="ManageCalendar" method="Post">
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
												<label class="form-label span4" for="Color">Color</label>
												<input tabindex="2" class="span8 tip" title="Mandatory : Hexadecimal Value of Color" type="text" name="Color" id="Color" value="<?php echo $Color; ?>" readonly /><div class="picker"></div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="StartTime">Start Time</label>
												<input tabindex="3" class="span8 tip" title="Mandatory : Start Time of the Event (To be picked from the Calendar) " type="text" name="StartTime" id="StartTime" value="<?php echo $StartTime; ?>" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="EndTime">End Time</label>
												<input tabindex="5" class="span8 tip" title="Mandatory : End Time of the Event (To be picked from the Calendar) " id="EndTime" type="text" name="EndTime" value="<?php echo $EndTime; ?>" readonly />
											</div>
										</div>
									</div>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="ManageCalendar" readonly>
									   <?php if($count1>0) { ?>
									   <input type="hidden" name="CalendarId" value="<?php echo $UpdateCalendarId; ?>" readonly>											   
									   <?php } ?>
									<?php ActionButton($ButtonContent,5); ?>
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
									<span>Delete Calendar "<?php echo $DeleteTitle; ?>" ??</span>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<form class="form-horizontal" action="ActionDelete" name="DeleteCalendar" id="DeleteCalendar" method="Post">
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
									<input type="hidden" name="Action" value="DeleteCalendar" readonly />
									<input type="hidden" name="CalendarId" value="<?php echo $CalendarId; ?>" readonly />
									<?php SetDeleteButton(22); ?>
								</form>
							</div>
						</div>
					
					<?php
					}
					?>
					<?php
						$query="select * from calendar where Username='$USERNAME' and CalendarStatus='Active' order by StartTime";
						$result=mysqli_query($CONNECTION,$query);
						$count=mysqli_num_rows($result);
						$DATA=array();
						$QA=array();
						$PrintCalendarList3="";
						while($row=mysqli_fetch_array($result))
						{
							$ListCalendarId=$row['CalendarId'];
							$ListTitle=$row['Title'];	
							$ListStartTime=date("d M Y, D h:i a",$row['StartTime']);	
							$ListEndTime=date("d M Y, D h:i a",$row['EndTime']);
							$Edit="<a href=Calendar/Update/$ListCalendarId><span class=\"icon-edit tip\" title=\"Update\"></span></a>";
							$Delete="<a href=Calendar/Delete/$ListCalendarId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
							$Note="<a href=Note/Calendar/$ListCalendarId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"silk-icon-notes tip\" title=\"Write Note\"></span></a>";
							$PrintCalendarList3.="<tr class=\"odd gradeX\">
									<td>$ListTitle</td>
									<td>$ListStartTime</td>
									<td>$ListEndTime</td>
								</tr>";
							$QA[]=array($ListTitle,$ListStartTime,$ListEndTime,$Edit,$Delete);
						}
						$DATA['aaData']=$QA;
						$fp = fopen('plugins/Data/data1.txt', 'w');
						fwrite($fp, json_encode($DATA));
						fclose($fp);
						?>
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>My Calendar List</span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintCalendarList" readonly>
										<input type="hidden" name="HeadingName" value="PrintCalendarHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Calendar List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
								<a href="#" class="minimize tip" title="Minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$PrintCalendarList1="<table id=\"CalendarTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Title</th>
											<th>Start Time</th>
											<th>End Time</th>";
											echo $PrintCalendarList1;
											echo "<th><span class=\"icon-edit tip\" title=\"Update\"></span></th>
											<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
										$PrintCalendarList2="</tr>
									</thead>
									<tbody>";
									echo $PrintCalendarList2;
									$PrintCalendarList4="</tbody>
								</table>";
									echo $PrintCalendarList4;
									$PrintCalendarList="$PrintCalendarList1 $PrintCalendarList2 $PrintCalendarList3 $PrintCalendarList4";
									$_SESSION['PrintCalendarList']=$PrintCalendarList;
									$PrintCalendarHeading="Showing List of Calendars";
									$_SESSION['PrintCalendarHeading']=$PrintCalendarHeading;
							?>
							</div>
						</div>
					</div>
                </div>				
				
            </div>
        </div>
<script type="text/javascript">
$(document).ready(function() {
	$('#CalendarTable').dataTable({
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
	if($('#StartTime').length) {
	$('#StartTime').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	if($('#EndTime').length) {
	$('#EndTime').datetimepicker({ dateFormat: 'dd-mm-yy' });
	}
	if($('div').hasClass('picker')){
		$('.picker').farbtastic('#Color');
	}	
	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageCalendar").validate({
		rules: {
			StartTime: {
				required: true,
			},
			EndTime: {
				required: true,
			},
			Title: {
				required: true,
			},
			Color: {
				required: true,
			}
		},
		messages: {
			StartTime: {
				required: "Please enter Start Time!!",
			},
			EndTime: {
				required: "Please enter End Time!!",
			},
			Title: {
				required: "Please enter Title!!",
			},
			Color: {
				required: "Please select Color!!",
			}
		}   
	});
	$("#DeleteCalendar").validate({
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