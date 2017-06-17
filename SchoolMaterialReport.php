<?php
$PageName="SchoolMaterialReport";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
$MaterialType=isset($_GET['MaterialType']) ? $_GET['MaterialType'] : '';
if($MaterialType=="" || ($MaterialType!="Books" && $MaterialType!="Uniform" && $MaterialType!="Other") )
$MaterialType="Books";
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
                <?php $SchoolMaterialCategory="<a href=SchoolMaterialReport/Books><div class=\"badge badge-important\">Books</div></a>
										<a href=SchoolMaterialReport/Uniform><div class=\"badge badge-info\">Uniform</div></a>
										<a href=SchoolMaterialReport/Other><div class=\"badge badge-success\">Other</div></a>";
				$BreadCumb="School Material $SchoolMaterialCategory"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); 
				$StockDate=isset($_GET['D']) ? $_GET['D'] : '';
				if($StockDate=="")
				$StockDate=$DDMMYYYY;
				$StockDate.=" 23:59";
				$StockDate=strtotime($StockDate);
				if($StockDate=="")
				$StockDateName=date("d-m-Y",$Date);
				else
				$StockDateName=date("d-m-Y",$StockDate);
				$PrintData="";
				$query01="select Name,BranchPrice,SellingPrice,Quantity,ClassName,schoolmaterial.Session,SchoolMaterialId from schoolmaterial,class where 
				SchoolMaterialStatus='Active' and 
				SchoolMaterialType='$MaterialType' and 
				schoolmaterial.ClassId=class.ClassId ";
				$check01=mysqli_query($CONNECTION,$query01);
				$count01=mysqli_num_rows($check01);
				$DATA=array();
				$QA=array();
				while($row01=mysqli_fetch_array($check01))
				{
					$SchoolMaterialIdArray[]=$row01['SchoolMaterialId'];
					$BranchPriceArray[]=$row01['BranchPrice'];
					$NameArray[]=$row01['Name'];
					$SellingPriceArray[]=$row01['SellingPrice'];
					$QuantityArray[]=$row01['Quantity'];
					$ClassNameArray[]=$row01['ClassName'];
					$SessionArray[]=$row01['Session'];
				}
				
				$query02="select Material from issue where MaterialType='$MaterialType' and DOI<='$StockDate' ";
				$check02=mysqli_query($CONNECTION,$query02);
				$count02=mysqli_num_rows($check02);
				$STR=$i="";
				while($row02=mysqli_fetch_array($check02))
				{
					$i++;
					$STR.=$row02['Material'];
					if($i<$count02)
					$STR.=",";
				}
				$IssueArray=explode(",",$STR);
				$query1="select SUM(Quantity) as TotalQuantity,UniqueId from purchaselist,purchase where 
					MaterialType='$MaterialType' and
					purchaselist.Token=purchase.Token and
					purchase.DOP<='$StockDate' 
					group by UniqueId ";
				$check1=mysqli_query($CONNECTION,$query1);
				$count1=mysqli_num_rows($check1);
				$ListTotalQuantity=0;
				while($row1=mysqli_fetch_array($check1))
				{
					$CountIssue=0;
					$ListTotalQuantity=round($row1['TotalQuantity'],2);
					$ListUniqueId=$row1['UniqueId'];
					$ListSearchIndex=array_search($ListUniqueId,$SchoolMaterialIdArray);
					$ListName=$NameArray[$ListSearchIndex];
					if($MaterialType=="Books")
					{
						$ListClassName=$ClassNameArray[$ListSearchIndex];
						$ListSessionName=$SessionArray[$ListSearchIndex];
					}
					
					foreach($IssueArray as $Issue)
					{
						$IssueMaterialQuantityArray=explode("-",$Issue);
						$IssueMaterialId=$IssueMaterialQuantityArray[0];
						if(isset($IssueMaterialQuantityArray[1]))
						$IssueQuantity=$IssueMaterialQuantityArray[1];
						if($IssueMaterialId==$ListUniqueId)
						$CountIssue+=$IssueQuantity;
					}
					
					$ListTotalQuantity-=$CountIssue;
					
					if($MaterialType!="Books")
					$QA[]=array($ListName,$ListTotalQuantity);	
					else
					$QA[]=array($ListName,$ListTotalQuantity,$ListClassName,$ListSessionName);
				}	
				$DATA['aaData']=$QA;
				$fp = fopen('plugins/Data/data1.txt', 'w');
				fwrite($fp, json_encode($DATA));
				fclose($fp);	
				
				?>
                <div class="row-fluid">
                    <div class="span3">				
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="ReportAction" name="SchoolMaterialReportForm" id="SchoolMaterialReportForm" method="post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Stock Date</label>
												<input tabindex="1" class="span8" id="D" type="text" name="D" value="<?php echo $StockDateName; ?>" readonly />
											</div>
										</div>
									</div>
									<?php
									$ButtonContent="Get Detail"; ActionButton($ButtonContent,2); ?>
									<input type="hidden" name="Action" value="SchoolMaterialReport" readonly>
									<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
								</form>
                            </div>
                        </div>
                    </div>
					<div class="span9">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>School Material "<?php echo $MaterialType; ?> List <?php echo "on $StockDateName"; ?>"</span>
									<?php if($count01>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="SessionName" value="PrintSchoolMaterialReportList" readonly>
										<input type="hidden" name="HeadingName" value="PrintSchoolMaterialReportHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print School Material Report List"></button>
										</form>
									</div>
									<?php } ?>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix">
							<?php
							$Print1="<table id=\"SchoolMaterialReportTable\" class=\"responsive dynamicTable display table table-bordered\">
								<thead>
								  <tr>
									<th>Name</th>
									<th>Quantity</th>";
									if($MaterialType=="Books")
									{
									$Print1.="<th>Class</th>
											<th>Session</th>";
									}
								  $Print1.="</tr>
								</thead>
								<tbody>";
								echo $Print1;
								$Print3="</tbody>
							</table>";
							echo $Print3;
								$Print="$Print1 $PrintData $Print3";
								$_SESSION['PrintSchoolMaterialReportList']=$Print;
								$PrintSchoolMaterialReportHeading="School Material $MaterialType List on $StockDateName";
								$_SESSION['PrintSchoolMaterialReportHeading']=$PrintSchoolMaterialReportHeading;
							?>
							</div>
						</div>					
					</div>
				</div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$('#SchoolMaterialReportTable').dataTable({
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
		if($('#D').length) {
		$("#D").datepicker({ yearRange: "-5:+0", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
	});
</script>
	
<?php
include("Template/Footer.php");
?>