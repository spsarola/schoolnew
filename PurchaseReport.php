<?php
$PageName="PurchaseReport";
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
                <?php $BreadCumb="Purchase Report"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); 
				$ListReport="";
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
				elseif(isset($SCHOOLSTARTINGDATE)  && $FDTS<$SCHOOLSTARTINGDATE)
				{
					$Error=1;
					$Msg="<div class=\"alert alert-error\">School was setup on $SCHOOLSTARTINDATENAME, You have to select date after $SCHOOLSTARTINDATENAME!!</div>";
				}
				else
				{
					$Error=0;
					$Msg="<div class=\"alert alert-success\">Showing issue report from $FromDate to $ToDate !!</div>";
				}

				if($Error==0)
				{
					$query2="select SupplierName,purchase.Token,Total,Paid,DOP,Remarks from purchase,purchaselist,supplier where
						PurchaseStatus='Active' and
						purchase.Token=purchaselist.Token and 
						purchase.SupplierId=supplier.SupplierId and
						purchaselist.MaterialType='Stock' and 
						DOP>='$FDTS' and
						DOP<='$TDTS' group by purchaselist.Token order by DOP";
					$check2=mysqli_query($CONNECTION,$query2);
					$DATA=array();
					$QA=array();
					while($row2=mysqli_fetch_array($check2))
					{
						$SupplierName=$row2['SupplierName'];
						$Token=$row2['Token'];
						$Total=round($row2['Total'],2);
						$Paid=round($row2['Paid'],2);
						$Remarks=$row2['Remarks'];
						$DOP=date("d M Y,h:ia",$row2['DOP']);
						$Total="$Total $CURRENCY";
						$Paid="$Paid $CURRENCY";
						$SupplierName="<a href=Purchase/$Token>$SupplierName</a>";
						$QA[]=array($SupplierName,$Total,$Paid,$Remarks,$DOP);	
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
								<form class="form-horizontal" action="ReportAction" name="PurchaseReport" id="PurchaseReport" method="Post">
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
										<input type="hidden" readonly name="Action" value="PurchaseReport">
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
									<span>Purchase Report <?php echo "of \"Stock\" from $FromDate to $ToDate"; ?></span>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintPurchaseReportList" readonly>
										<input type="hidden" name="HeadingName" value="PrintPurchaseReportHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Purchase Report"></button>
										</form>
									</div>
								</h4>
                                <a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
								<?php
								$PrintReportList1="<table id=\"PurchaseReportTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Supplier</th>
											<th>Total</th>
											<th>Paid</th>
											<th>Remarks</th>
											<th>Date Purchased</th>
										</tr>
									</thead>
									<tbody>";
									echo $PrintReportList1;
									$PrintReportList2="</tbody>
								</table>";
								echo $PrintReportList2;
								$PrintReportList="$PrintReportList1 $ListReport $PrintReportList2";
								$_SESSION['PrintPurchaseReportList']=$PrintReportList;
								$PrintReportHeading="Purchase of Stock from $FromDate to $ToDate";
								$_SESSION['PrintPurchaseReportHeading']=$PrintReportHeading;
								?>
							</div>
						</div>					
					<?php } else echo $Msg; ?>
					</div>
				</div>				
            </div>
        </div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#PurchaseReportTable').dataTable({
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
		$("#PurchaseReport").validate({
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