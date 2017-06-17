<?php
$PageName="DashBoard";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$CalendarRequired=1;
$ChartRequired=1;
$TableRequired=1;
include("Include.php");
if($ErrorMessage!="")
{
	$Message=$ErrorMessage;
	$Type=error;
	SetNotification($Message,$Type);
	header("Location:ErrorPage");
	exit();
}
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
                <?php $BreadCumb="DashBoard"; BreadCumb($BreadCumb); ?>
				<?php DisplayNotification(); ?>
				<?php
				if($USERNAME=="masteruser" || $USERNAME=="webmaster") 
				{
					$REPORTDAYS=isset($_SESSION['REPORTDAYS']) ? $_SESSION['REPORTDAYS'] : 15;
					$DateddMMyyyy=date("d-m-Y",strtotime($Date));
					$TodayDate=$DateddMMyyyy;
					$ReportDate=date("d M Y", strtotime(date("Y-m-d", strtotime($TodayDate)) . " -$REPORTDAYS day"));
					
					$TodayDateStart="$TodayDate 00:00";
					$ReportDateEnd="$ReportDate 23:59";
					$TSTS=strtotime($TodayDateStart);
					$RETS=strtotime($ReportDateEnd);				
					
					for($i=$REPORTDAYS;$i>0;$i--)
					{
						$Income[$i]=0;
						$Expense[$i]=0;
					}

					$query="select COUNT(AdmissionId) as TotalStudent,SectionName,ClassName from studentfee,class,section where
						studentfee.Session='$CURRENTSESSION' and
						studentfee.SectionId=section.SectionId and
						section.ClassId=class.ClassId
						group by studentfee.SectionId ";
					$check=mysqli_query($CONNECTION,$query);
					$AdmissionData=null;
					while($row=mysqli_fetch_array($check))
					{
						$TotalStudent=$row['TotalStudent'];
						$SectionName=$row['SectionName'];
						$ClassName=$row['ClassName'];
						$RandomColor=GetRandomColor();
						$AdmissionData.="{ label: \"$ClassName $SectionName\",  data: $TotalStudent, color: \"$RandomColor\"},";
					}

					$query1="Select SUM(TransactionAmount) as TotalIncome,TransactionDate from transaction where
						TransactionStatus='Active' and
						TransactionType='1' and
						TransactionDate>='$RETS' and
						TransactionDate<='$TSTS' 
						group by TransactionDate ";
					$check1=mysqli_query($CONNECTION,$query1);
					while($row1=mysqli_fetch_array($check1))
					{
						$DayStart=strtotime($TodayDate);
						$TotalIncome=round($row1['TotalIncome'],2);
						$IncomeDate=date("d M Y",$row1['TransactionDate']);
						for($i=$REPORTDAYS;$i>0;$i--)
						{	
							$DayStartName=date("d M Y",$DayStart);
							if($DayStartName==$IncomeDate)
							{	
								$Income[$i]=$TotalIncome;
								break;
							}
							$DayStart=date("d M Y", strtotime(date("Y-m-d", strtotime($DayStartName)) . " -1 day"));
							$DayStart=strtotime($DayStart);
						}
					}
					
					$query2="Select SUM(TransactionAmount) as TotalExpense,TransactionDate from transaction where
						TransactionStatus='Active' and
						TransactionType='0' and
						TransactionDate>='$RETS' and
						TransactionDate<='$TSTS' 
						group by TransactionDate ";
					$check2=mysqli_query($CONNECTION,$query2);
					while($row2=mysqli_fetch_array($check2))
					{
						$DayStart=strtotime($TodayDate);
						$TotalExpense=round($row2['TotalExpense'],2);
						$ExpenseDate=date("d M Y",$row2['TransactionDate']);
						for($i=$REPORTDAYS;$i>0;$i--)
						{	
							$DayStartName=date("d M Y",$DayStart);
							if($DayStartName==$ExpenseDate)
							{	
								$Expense[$i]=$TotalExpense;
								break;
							}
							$DayStart=date("d M Y", strtotime(date("Y-m-d", strtotime($DayStartName)) . " -1 day"));
							$DayStart=strtotime($DayStart);
						}
					}

					$DayStart=strtotime($TodayDate);
					$IncomeSTR=$LabelContent=$ExpenseSTR=null;
					for($i=$REPORTDAYS;$i>0;$i--)
					{
						$DayStartName=date("d M Y",$DayStart);
						$IncomeSTR.="[$i,$Income[$i]]";
						if($i>1)
						$IncomeSTR.=",";
						$ExpenseSTR.="[$i,$Expense[$i]]";
						if($i>1)
						$ExpenseSTR.=",";
						$DayStart=date("d M Y", strtotime(date("Y-m-d", strtotime($DayStartName)) . " -1 day"));
						$DayStart=strtotime($DayStart);
						$LabelContent.="[$i,\"$DayStartName\"]";
						if($i>1)
						$LabelContent.=",";
					}				
				}
				?>
                <div class="row-fluid">
					<div class="span8">
						<div class="row-fluid">
							<div class="span12">
								<div class="box gradient">
									<div class="title">
										<h4>
											<span>Circulars</span>
										</h4>
										<a href="#" class="minimize">Minimize</a>
									</div>
									<div class="content clearfix scroll" style="max-height:200px; overflow:auto;">
									<?php
									$query5="select Circular,Title,DateReleased from circular where CircularStatus='Active' order by DateReleased desc ";
									$check5=mysqli_query($CONNECTION,$query5);
									$count5=0;
									$count5=mysqli_num_rows($check5);
									if($count5>0)
									{
									$i=0;
									?>
										<div class="accordion" id="accordion2">
										<?php
										while($row5=mysqli_fetch_array($check5))
										{
											$i++;
											$ListCircular=$row5['Circular'];
											$ListTitle=$row5['Title'];
											$ListDateReleased=date("d M Y",$row5['DateReleased']);
										?>
											<div class="accordion-group">
											  <div class="accordion-heading">
												<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $i; ?>">
												  <?php echo "$ListTitle on $ListDateReleased"; ?>
												</a>
											  </div>
											  <div id="collapse<?php echo $i; ?>" class="accordion-body collapse" style="height: 0px; ">
												<div class="accordion-inner">
												<?php echo $ListCircular; ?>
												</div>
											  </div>
											</div>
										<?php
										}
										?>
										</div>
										<?php
									}
									else
									echo "<div class=\"alert alert-danger\">No circular found!!</div>";
									?>									   
									</div>
								</div>
							</div>
						</div>
						<?php if($USERNAME=="masteruser" || $USERNAME=="webmaster") { ?>
						<div class="row-fluid">
							<div class="span12">
								<div class="box gradient">
									<div class="title">
										<h4>
											<span>Income/Expense Report</span>
										</h4>
										<a href="#" class="minimize">Minimize</a>
									</div>
									<div class="content clearfix">
									   <div class="lines-chart" style="height: 230px;width:100%;"></div>								
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<div class="box gradient">
									<div class="title">
										<h4>
											<span>Student Admission Report</span>
										</h4>
										<a href="#" class="minimize">Minimize</a>
									</div>
									<div class="content clearfix">
									   <div class="StudentAdmissionGraph" style="height: 180px;width:100%;"></div>						
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
					<div class="span4">
						<?php if($USERNAME=="masteruser" || $USERNAME=="webmaster")  { ?>
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo Translate('Graph Reports'); ?></span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad clearfix"> 
								<form class="form-horizontal" action="ReportAction" name="DashBoardReport" id="DashBoardReport" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal"></label>
												<div class="grid-inputs span8 input-append">
												<input placeholder="No of Days" tabindex="1" class="span8 tip" title="Mandatory : Only Numeric, Number of days you want to show the Expense/Income Report" id="REPORTDAYS" type="number" name="REPORTDAYS" value="<?php echo $REPORTDAYS; ?>" />
												<button class="btn tip" title="Click here to get the graph report" type="submit" tabindex="4">Go!</button>
												</div>
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="DashBoardReport" readonly>
								</form>
                            </div>
                        </div>
						<?php } ?>
                        <div class="box calendar gradient">
                            <div class="title">
                                <h4>
                                    <span><?php echo Translate('Calendar'); ?> </span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content noPad"> 
                                <div id="calendar">								
								</div>
                            </div>
                        </div>
					</div>
                </div>
            </div>
        </div>

<script type="text/javascript">
var AdmissionData = [
   <?php echo $AdmissionData; ?>
];	
var chartColours = ['#88bbc8', '#ed7a53', '#9FC569', '#bbdce3', '#9a3b1b', '#5a8022', '#2c7282'];
var d1 = [<?php echo $IncomeSTR; ?>];
var d2 = [<?php echo $ExpenseSTR; ?>];	
var labelcontent= [<?php echo $LabelContent; ?>];
		
$(document).ready(function() {
	$("input, textarea, select").not('.nostyle').uniform();
	$("#DashBoardReport").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			REPORTDAYS: {
				required: true,
			}
		}  
	});	
});
</script>
<?php
include("Template/Footer.php");
?>