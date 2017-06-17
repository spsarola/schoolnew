<?php
$PageName="IssueReport";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
$MaterialType=isset($_GET['MaterialType']) ? $_GET['MaterialType'] : '';
if($MaterialType=="" || ($MaterialType!="Books" && $MaterialType!="Uniform" && $MaterialType!="Other") )
$MaterialType="Books";
include("Include.php");
IsLoggedIn();
$ListReport="";
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
                <?php $SchoolMaterialCategory="<a href=IssueReport/Books><div class=\"badge badge-important\">Books</div></a>
										<a href=IssueReport/Uniform><div class=\"badge badge-info\">Uniform</div></a>
										<a href=IssueReport/Other><div class=\"badge badge-success\">Other</div></a>";
				$BreadCumb="Issue Report $SchoolMaterialCategory"; BreadCumb($BreadCumb);  ?>
				<?php DisplayNotification(); 
				$FromDate=isset($_GET['FromDate']) ? $_GET['FromDate'] : '';
				$ToDate=isset($_GET['ToDate']) ? $_GET['ToDate'] : '';
				$DateddMMyyyy=date("d-m-Y",strtotime($Date));
				if($FromDate=="")
				$FromDate=$DateddMMyyyy;
				if($ToDate=="")
				$ToDate=$DateddMMyyyy;
				$PreviousDay=date("d M Y", strtotime(date("Y-m-d", strtotime($FromDate)) . " -1 day"));
				$PreviousDayName=$PreviousDay;
				$PreviousDay="$PreviousDay 23:59";
				$FromDateStart="$FromDate 00:00";
				$ToDateEnd="$ToDate 23:59";
				$FDTS=strtotime($FromDateStart);
				$TDTS=strtotime($ToDateEnd);
				$PDTS=strtotime($PreviousDay);
				$Date=strtotime($Date);
				$CheckDateFD=CheckDateFormat($FromDate);
				$CheckDateTD=CheckDateFormat($ToDate);
				if($CheckDateFD===false)
				{
					$Error=1;
					$Msg="<div class=\"alert alert-error\">From date is not a valid date!!</div>";
				}
				elseif($CheckDateTD===false)
				{
					$Error=1;
					$Msg="<div class=\"alert alert-error\">To date is not a valid date!!</div>";
				}
				elseif($FDTS>$TDTS)
				{
					$Error=1;
					$Msg="<div class=\"alert alert-error\">From Date cannot be greater than to date!!</div>";
				}
				elseif($FDTS<$SCHOOLSTARTDATE)
				{
					$Error=1;
					$Msg="<div class=\"alert alert-error\">School was setup on $SCHOOLSTARTDATE, You have to select date after $SCHOOLSTARTDATE!!</div>";
				}
				else
				{
					$Error=0;
					$Msg="<div class=\"alert alert-success\">Showing issue report from $FromDate to $ToDate !!</div>";
				}	

				if($Error==0)
				{
					if($MaterialType=="Books")
					{
						$query001="Select ClassName,ClassId from class where ClassStatus='Active' ";
						$check001=mysqli_query($CONNECTION,$query001);
						while($row001=mysqli_fetch_array($check001))
						{
							$ClassIdArray[]=$row001['ClassId'];
							$ClassNameArray[]=$row001['ClassName'];
						}	
					}
					
					$query2="select IssueId,StudentName,FatherName,Mobile,issue.ClassId,issue.Session,Total,Paid,issue.Remarks,DOI from issue,registration,admission where
						IssueStatus='Active' and
						issue.MaterialType='$MaterialType' and 
						issue.AdmissionId=admission.AdmissionId and
						admission.RegistrationId=registration.RegistrationId and 
						DOI>='$FDTS' and
						DOI<='$TDTS' order by IssueId";
					$check2=mysqli_query($CONNECTION,$query2);
					$DATA=array();
					$QA=array();
					while($row2=mysqli_fetch_array($check2))
					{
						$IssueId=$row2['IssueId'];
						$StudentName=$row2['StudentName'];
						$FatherName=$row2['FatherName'];
						$Mobile=$row2['Mobile'];
						$Class=$row2['ClassId'];
						if($MaterialType=="Books")
						{
							$ClassSearchIndex=array_search($Class,$ClassIdArray);
							$ClassName=$ClassNameArray[$ClassSearchIndex];
						}
						$Session=$row2['Session'];
						$Total=round($row2['Total'],2);
						$Paid=round($row2['Paid'],2);
						$Remarks=$row2['Remarks'];
						$DOI=date("d M Y,h:ia",$row2['DOI']);
						$Detail="$StudentName $FatherName $Mobile";
						if($MaterialType=="Books")
						$QA[]=array($IssueId,$Detail,$ClassName,$Session,$Total,$Paid,$Remarks,$DOI);	
						else
						$QA[]=array($IssueId,$Detail,$Total,$Paid,$Remarks,$DOI);	
					}
					$DATA['aaData']=$QA;
					$fp = fopen('plugins/Data/data1.txt', 'w');
					fwrite($fp, json_encode($DATA));
					fclose($fp);	
				}
				
				?>

                <div class="row-fluid">
                    <div class="span3">
                       <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Dates</span>
                                </h4>
                                <a href="#" class="minimize tip" title="Minimize">Minimize</a>
                            </div>
							<div class="content noPad clearfix"> 
								<form class="form-horizontal" action="ReportAction" name="IssueReport" id="IssueReport" method="Post">
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="normal">From Date</label>
													<input tabindex="1" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="FromDate" type="text" name="FromDate" value="<?php echo $FromDate; ?>" readonly />
												</div>
											</div>
										</div>
										<div class="form-row row-fluid">
											<div class="span12">
												<div class="row-fluid">
													<label class="form-label span4" for="normal">To Date</label>
													<input tabindex="2" class="span8 tip" title="Mandatory : (dd-mm-yyyy)" id="ToDate" type="text" name="ToDate" value="<?php echo $ToDate; ?>" readonly />
												</div>
											</div>
										</div>
										<input type="hidden" readonly name="Action" value="IssueReport">
										<input type="hidden" readonly name="MaterialType" value="<?php echo $MaterialType; ?>">
									   <?php $ButtonContent="Get Report"; ActionButton($ButtonContent,3); ?>
								</form>
							</div>
                        </div>
                    </div>	
					<div class="span9">
					<?php if($Error==0) { ?>
					
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Issue Report <?php echo "of \"$MaterialType\" from $FromDate to $ToDate"; ?></span>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintIssueReportList" readonly>
										<input type="hidden" name="HeadingName" value="PrintIssueReportHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Issue Report"></button>
										</form>
									</div>
								</h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php
								$PrintReportList1="<table id=\"IssueReportTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Issue No</th>
											<th>Name</th>";
											if($MaterialType=="Books")
											$PrintReportList1.="<td>Class</td><Td>Session</td>";
											$PrintReportList1.="<th>Total</th>
											<th>Paid</th>
											<th>Remarks</th>
											<th>Date Issued</th>
										</tr>
									</thead>
									<tbody>";
									echo $PrintReportList1;
									$PrintReportList2="</tbody>
								</table>";
								echo $PrintReportList2;
								$PrintReportList="$PrintReportList1 $ListReport $PrintReportList2";
								$_SESSION['PrintIssueReportList']=$PrintReportList;
								$PrintReportHeading="Issue Report of \"$MaterialType\" from $FromDate to $ToDate";
								$_SESSION['PrintIssueReportHeading']=$PrintReportHeading;
								?>
							</div>
						</div>					
					
					<?php } else 
					echo "$Msg"; ?>
					</div>
				</div>
            </div>
        </div>
		
 <script type="text/javascript">
	$(document).ready(function() {
		$('#IssueReportTable').dataTable({
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
		if($('#FromDate').length) {
		$('#FromDate').datepicker({ dateFormat: 'dd-mm-yy' });
		}	
		if($('#ToDate').length) {
		$('#ToDate').datepicker({ dateFormat: 'dd-mm-yy' });
		}	
		$("input, textarea, select").not('.nostyle').uniform();
		$("#IssueReport").validate({
			rules: {
				FromDate: {
					required: true,
				},
				ToDate: {
					required: true,
				}
			},
			messages: {
				FromDate: {
					required: "Please select this!!",
				},
				ToDate: {
					required: "Please select this!!",
				}
			}   
		});
	});
</script>
<?php
include("Template/Footer.php");
?>