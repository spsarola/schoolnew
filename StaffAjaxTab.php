<?php
include("Include.php");
$Action=$_GET['Action'];
$ListAllSalaryStructure =$Document =$Resolution="";
if($Action=="StaffProfile")
{
$StaffId=$_GET['Id'];
$query100="select * from staff,masterentry where staff.StaffPosition=masterentry.MasterEntryId and StaffId='$StaffId'";
$check100=mysqli_query($CONNECTION,$query100);
$row100=mysqli_fetch_array($check100);
$StaffName=$row100['StaffName'];
$StaffMobile=$row100['StaffMobile'];
$StaffEmail=$row100['StaffEmail'];
$StaffAlternateMobile=$row100['StaffAlternateMobile'];
$StaffPosition=$row100['StaffPosition'];
$StaffDOJ=$row100['StaffDOJ'];
if($StaffDOJ!="")
$StaffDOJ=date("d-m-Y",$row100['StaffDOJ']);
$StaffDOB=$row100['StaffDOB'];
if($StaffDOB!="")
$StaffDOB=date("d-m-Y",$row100['StaffDOB']);
$StaffStatus=$row100['StaffStatus'];
if($StaffStatus=="Active")
$StaffStatusChecked="checked=checked";
else
$StaffStatusChecked="";
$StaffPresentAddress=br2nl($row100['StaffPresentAddress']);
$StaffPermanentAddress=br2nl($row100['StaffPermanentAddress']);
$StaffFName=$row100['StaffFName'];
$StaffMName=$row100['StaffMName'];
?>
<form class="form-horizontal" action="Action" name="ManageStaffDetail" id="ManageStaffDetail" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Name</label>
					<input tabindex="101" class="span8" id="StaffNameDetail" type="text" name="StaffNameDetail" value="<?php echo $StaffName; ?>"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
				<label class="form-label span4" for="normal">Position</label> 
					<div class="span8 controls sel">  
						<?php GetCategoryValue('StaffPosition','StaffPositionDetail',$StaffPosition,'','','','',102,''); ?>
					</div> 
				</div>
			</div> 
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Email</label>
					<input tabindex="104" class="span8" id="StaffEmailDetail" type="email" name="StaffEmailDetail" value="<?php echo $StaffEmail; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Status</label>
					<input tabindex="103" class="styled" id="StaffStatus" type="checkbox" name="StaffStatus" value="Yes" <?php echo $StaffStatusChecked; ?> />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Mobile</label>
					<input tabindex="104" class="span8" id="StaffMobileDetail" type="text" name="StaffMobileDetail" value="<?php echo $StaffMobile; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Alternate Mobile</label>
					<input tabindex="105" class="span8" id="StaffAlternateMobileDetail" type="text" name="StaffAlternateMobileDetail" value="<?php echo $StaffAlternateMobile; ?>" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Present Address</label>
					<div class="span8 controls-textarea">
					<textarea tabindex="106" class="span12" name="StaffPresentAddressDetail" id="StaffPresentAddressDetail"><?php echo $StaffPresentAddress; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Joining Date</label>
					<input tabindex="107" class="span8" id="StaffDOJDetail" type="text" name="StaffDOJDetail" value="<?php echo $StaffDOJ; ?>" readonly />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Birth Date</label>
					<input tabindex="108" class="span8" id="StaffDOBDetail" type="text" name="StaffDOBDetail" value="<?php echo $StaffDOB; ?>" readonly />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Permanent Address</label>
					<div class="span8 controls-textarea">
					<textarea tabindex="109" class="span12" name="StaffPermanentAddressDetail" id="StaffPermanentAddressDetail"><?php echo $StaffPermanentAddress; ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageStaffProfile" readonly>
		<input type="hidden" name="StaffId" value="<?php echo $StaffId; ?>" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,110); ?>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function() {

if($('#StaffDOJDetail').length) {
	$("#StaffDOJDetail").datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}
if($('#StaffDOBDetail').length) {
	$("#StaffDOBDetail").datepicker({ yearRange: "-80:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}

	$("input, textarea, select").not('.nostyle').uniform();
	$("#StaffPositionDetail").select2();
	$('#StaffPositionDetail').select2({placeholder: "Select"});
	$("#ManageStaffDetail").validate({
		rules: {
			StaffPositionDetail: {
				required: true,
			},
			StaffNameDetail: {
				required: true,
			},
			StaffMobileDetail: {
				required: true,
				remote: "RemoteValidation?Action=MobileValidation&Id=StaffMobileDetail"
			},
			StaffAlternateMobileDetail: {
				remote: "RemoteValidation?Action=MobileValidation&Id=StaffAlternateMobileDetail"
			},
			StaffDOJDetail: {
				required: true,
			}
		},
		messages: {
			StaffPositionDetail: {
				required: "Please select this!!",
			},
			StaffNameDetail: {
				required: "Please enter this!!",
			},
			StaffMobileDetail: {
				required: "Please enter this!!",
				remote: jQuery.format("Only <?php echo $MOBILENUMBERDIGIT; ?> digit numeric!!"),
			},
			StaffAlternateMobileDetail: {
				remote: jQuery.format("Only <?php echo $MOBILENUMBERDIGIT; ?> digit numeric!!"),
			},
			StaffDOJDetail: {
				required: "Please select this!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="Qualification")
{
	$StaffId=$_GET['Id'];
	$query1="Select StaffId from staff where StaffId='$StaffId' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	
	$check=mysqli_query($CONNECTION,"select * from qualification where UniqueId='$StaffId' and Type='Staff' ");
	$count=mysqli_num_rows($check);
	if($count>0 && $count1==1)
	{
		echo "<table class=\"responsive table table-bordered\">
			<thead>
				 <tr>
					<th>Board/University</th>
					<th>Class</th>
					<th>Year</th>
					<th>Marks</th>
					<th>Remarks</th>
					<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>
				</tr>
			</thead>
			<tbody>";
			$ActionConfirmMessage="Are you sure want to delete?";
			$ActionConfirm=ActionConfirm($ActionConfirmMessage);
			while($row=mysqli_fetch_array($check))
			{
				$BoardUniversity=$row['BoardUniversity'];
				$Class=$row['Class'];
				$Year=$row['Year'];
				$Marks=$row['Marks'];
				$Remarks=$row['Remarks'];
				$QualificationId=$row['QualificationId'];
				$Delete="<a href=ActionGet/DeleteStaffQualification/$QualificationId><span class=\"icomoon-icon-cancel tip\" title=\"Delete\" $ActionConfirm></span></a>";
				echo "<tr>
				<Td>$BoardUniversity</td>
				<Td>$Class</td>
				<Td>$Year</td>
				<Td>$Marks</td>
				<td>$Remarks</td>
				<td>$Delete</td>
				</tr>";
			}
			echo "</tbody>
			</table>";
	}
	else
	echo "<div class=\"alert alert-error\">No Qualification added!!</div>";
?>
<form class="form-horizontal" action="Action" name="ManageQualification" id="ManageQualification" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Board/University</label>
					<input tabindex="151" class="span8" id="BoardUniversity" type="text" name="BoardUniversity"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Class</label>
					<input tabindex="152" class="span8" id="Class" type="text" name="Class"/>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Year</label>
					<input tabindex="153" class="span8" id="Year" type="text" name="Year" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Marks</label>
					<input tabindex="155" class="span8" id="Marks" type="text" name="Marks"  />
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="normal">Remarks</label>
					<div class="controls-textarea span8">
					<textarea tabindex="155" id="Remarks" name="Remarks" class="span12"></textarea>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManageQualification" readonly>
		<input type="hidden" name="UniqueId" value="<?php echo $StaffId; ?>" readonly>
		<input type="hidden" name="QualificationType" value="Staff" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,156); ?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {

	$("input, textarea, select").not('.nostyle').uniform();
	$("#ManageQualification").validate({
		rules: {
			BoardUniversity: {
				required: true,
			},
			Class: {
				required: true,
			},
			Year: {
				required: true,
			},
			Marks: {
				required: true,
			}
		},
		messages: {
			BoardUniversity: {
				required: "Please enter this field!!",
			},
			Class: {
				required: "Please enter this field!!",
			},
			Year: {
				required: "Please enter this field!!",
			},
			Marks: {
				required: "Please enter this field!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="SalarySetup")
{
$StaffId=$_GET['Id'];
$query="Select SalaryStructureName,FixedSalaryHead,SalaryStructureId from salarystructure where SalaryStructureStatus='Active' ";
$check=mysqli_query($CONNECTION,$query);
while($row=mysqli_fetch_array($check))
{
	$ComboSalaryStructureName=$row['SalaryStructureName'];
	$ComboFixedSalaryHead=$row['FixedSalaryHead'];
	$ComboSalaryStructureId=$row['SalaryStructureId'];
	$SalaryStructureNameArray[]=$row['SalaryStructureName'];
	$FixedSalaryHeadArray[]=$row['FixedSalaryHead'];
	$SalaryStructureIdArray[]=$row['SalaryStructureId'];
	$ListAllSalaryStructure.="<option value=\"$ComboSalaryStructureId\">$ComboSalaryStructureName</option>";
}

$query1="select SalaryHeadId,MasterEntryValue,SalaryHead,Code from salaryhead,masterentry where 
	salaryhead.SalaryHeadType=masterentry.MasterEntryId and
	SalaryHeadStatus='Active' ";
$check1=mysqli_query($CONNECTION,$query1);
while($row1=mysqli_fetch_array($check1))
{
	$SalaryHeadIdArray[]=$row1['SalaryHeadId'];
	$SalaryHeadArray[]=$row1['SalaryHead'];
	$CodeArray[]=$row1['Code'];
	$SalaryHeadTypeArray[]=$row1['MasterEntryValue'];
}

$query3="select salarystructuredetail.SalaryHeadId,Expression,SalaryStructureId,Code from salarystructuredetail,salaryhead where salarystructuredetail.SalaryHeadId=salaryhead.SalaryHeadId order by SalaryStructureDetailId ";
$check3=mysqli_query($CONNECTION,$query3);
while($row3=mysqli_fetch_array($check3))
{
	$ArraySalaryHeadId[]=$row3['SalaryHeadId'];
	$ArrayExpression[]=$row3['Expression'];
	$ArrayCode[]=$row3['Code'];
	$ArraySalaryStructureId[]=$row3['SalaryStructureId'];
}

$query2="select salarystructure.SalaryStructureId,SalaryStructureName,FixedSalary,StaffPaidLeave,EffectiveFrom,Remarks,StaffSalaryId from staffsalary,salarystructure where
	staffsalary.SalaryStructureId=salarystructure.SalaryStructureId and
	StaffSalaryStatus='Active' and
	StaffId='$StaffId' order by CAST(EffectiveFrom as SIGNED)";
$check2=mysqli_query($CONNECTION,$query2);
$count2=mysqli_num_rows($check2);
if($count2>0)
{
	while($row2=mysqli_fetch_array($check2))
	{
		unset($DependedSalaryHeadId);
		unset($DependedArrayExpression);
		unset($DependedSalaryCode);
		
		$ListSalaryStructureId=$row2['SalaryStructureId'];
		$ListStaffSalaryId=$row2['StaffSalaryId'];
		
		if($ArraySalaryStructureId!="")
		{
			$kk=0;
			foreach($ArraySalaryStructureId as $ArraySalaryStructureIdValue)
			{
				if($ArraySalaryStructureIdValue==$ListSalaryStructureId)
				{
					$DependedSalaryHeadId[]=$ArraySalaryHeadId[$kk];
					$DependedSalaryCode[]=$ArrayCode[$kk];
					$DependedArrayExpression[]=$ArrayExpression[$kk];
				}
				$kk++;
			}
		}
		
		$ListSalaryStructureName=$row2['SalaryStructureName'];
		$ListFixedSalary=explode(",",$row2['FixedSalary']);
		$ListRemarks=$row2['Remarks'];
		foreach($ListFixedSalary as $ListFixedSalaryValue)
		{
			$SalaryIdWithAmount=explode("-",$ListFixedSalaryValue);
			$SalaryId=$SalaryIdWithAmount[0];
			$SalaryAmount=$SalaryIdWithAmount[1];
			$SearchFixedSalaryIndex=array_search($SalaryId,$SalaryHeadIdArray);
			$SalaryCode=$CodeArray[$SearchFixedSalaryIndex];
			
			$FinalSalaryCodeArray[]=$SalaryCode;
			$FinalSalaryIdArray[]=$SalaryId;
			$FinalSalaryAmountArray[]=$SalaryAmount;
		}
		
		$mmm=0;
		if($DependedArrayExpression!="")
		foreach($DependedArrayExpression as $DependedArrayExpressionValue)
		{
			$mm=0;
			foreach($FinalSalaryCodeArray as $FinalSalaryCodeArrayValue)
			{
				$SalaryInInt=$FinalSalaryAmountArray[$mm];
				$DependedArrayExpressionValue=str_replace($FinalSalaryCodeArrayValue, $SalaryInInt, $DependedArrayExpressionValue);
				$mm++;
			}
			$answer = eval( 'return ' . $DependedArrayExpressionValue . ';' );
			$DependedSalaryId=$DependedSalaryHeadId[$mmm];
			$DependedSalaryCodeC=$DependedSalaryCode[$mmm];
			$FinalSalaryCodeArray[]=$DependedSalaryCodeC;
			$FinalSalaryIdArray[]=$DependedSalaryId;
			$FinalSalaryAmountArray[]=$answer;
			$mmm++;
		}
		
		$p=0;
		$FixedSalaryString="";
		$TotalSalary=0;
		foreach($FinalSalaryIdArray as $FinalSalaryIdArrayValue)
		{
			$Index=array_search($FinalSalaryIdArrayValue,$SalaryHeadIdArray);
			$LastSalaryHead=$SalaryHeadArray[$Index];
			$LastSalaryCode=$CodeArray[$Index];
			$LastSalaryHeadType=$SalaryHeadTypeArray[$Index];
			$LastSalaryAmount=$FinalSalaryAmountArray[$p];
			if($LastSalaryHeadType=="Earning")
			{
				$FontColor="green";
				$TotalSalary+=$LastSalaryAmount;
			}
			else
			{
				$FontColor="red";
				$TotalSalary-=$LastSalaryAmount;
			}
			$p++;
			$FixedSalaryString.="<tr><td><font color=$FontColor>$LastSalaryHead ($LastSalaryCode)</font></td><td>$LastSalaryAmount $CURRENCY</b></td></tr>";
			
			
		}
		$FixedSalaryString="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive display table table-bordered\" width=\"100%\">
							<thead>
								<tr>
									<th>Salary Head</th>
									<th>Salary Amount</th>
								</tr>
							</thead>
							<tbody>$FixedSalaryString</tbody>
							<thead>
								<tr>
									<Th>Total</th>
									<Th>$TotalSalary $CURRENCY</Th>
								</tr>
							</thead>
						</table>";
		
		unset($FinalSalaryCodeArray);
		unset($FinalSalaryIdArray);
		unset($FinalSalaryAmountArray);
		
		$ListStaffPaidLeave=$row2['StaffPaidLeave'];
		$ListEffectiveFrom=date("d M Y",$row2['EffectiveFrom']);
		$Delete="<a href=DeletePopUp/DeleteStaffSalarySetup/$ListStaffSalaryId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
		$ListSalaryStructure.="<tr>
								<td>$ListSalaryStructureName</td>
								<td>$FixedSalaryString</Td>
								<td>$ListStaffPaidLeave</td>
								<td>$ListEffectiveFrom</td>
								<Td>$ListRemarks</td>
								<td>$Delete</td>
							</tr>";
	}
}
?>
<form class="form-horizontal" action="Action" name="StaffSalaryStructure" id="StaffSalaryStructure" method="Post">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="SalaryStructureId">Salary Template</label>
					<div class="controls sel span8">   
					<select tabindex="111" name="SalaryStructureId" id="SalaryStructureId" class="nostyle" style="width:100%;" onchange="showdetail(this.value,'GetFixedSalaryHead','GetFixedSalaryHead')">
					<option></option>
					<?php echo $ListAllSalaryStructure; ?>
					</select>
					</div>
				</div>
			</div>
		</div>
		<div id="GetFixedSalaryHead"></div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="PaidLeave">Paid Leave</label>
					<input tabindex="112" class="span8" id="PaidLeave" type="number" name="PaidLeave" />
				</div>
			</div>
		</div>	
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Remarks">Remarks</label>
					<div class="span8 controls-textarea">
					<textarea tabindex="113" class="span12" name="Remarks" id="Remarks"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="EffectiveFrom">Date Effective From</label>
					<input tabindex="114" class="span8" id="EffectiveFrom" type="text" name="EffectiveFrom" readonly />
				</div>
			</div>
		</div>	
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="StaffSalaryStructure" readonly />
		<input type="hidden" name="StaffId" value="<?php echo $StaffId; ?>" readonly />
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,115); ?>
	</div>
</form>
<div class="clearfix"></div>
<Br>
<?php if($count2>0) { ?>
<table cellpadding="0" cellspacing="0" border="0" class="responsive display table table-bordered" width="100%">
	<thead>
		<tr>
			<th>Salary Template</th>
			<th>Fixed Salary</th>
			<th>Paid Leave</th>
			<th>Effective From</th>
			<th>Remarks</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
	<?php echo $ListSalaryStructure; ?>
	</tbody>
</table>
<?php } else { ?>
<div class="alert alert-error">No salary structure set yet for this staff!!</div>
<?php } ?>

<script type="text/javascript">
$(document).ready(function() {

	$('#myModal').modal({ show: false});
	$('#myModal').on('hidden', function () {
		console.log('modal is closed');
	})
	$("a[data-toggle=modal]").click(function (e) {
	lv_target = $(this).attr('data-target');
	lv_url = $(this).attr('href');
	$(lv_target).load(lv_url);
	});	

if($('#EffectiveFrom').length) {
	$("#EffectiveFrom").datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
}

	$("input, textarea, select").not('.nostyle').uniform();
	$("#SalaryStructureId").select2();
	$('#SalaryStructureId').select2({placeholder: "Select"});
	$("#StaffSalaryStructure").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			SalaryStructureId: {
				required: true,
			},
			PaidLeave: {
				required: true,
				remote: "RemoteValidation?Action=IsAmountWithZero&Id=PaidLeave"
			},
			EffectiveFrom: {
				required: true,
			}
		},
		messages: {
			SalaryStructureId: {
				required: "Please select this!!",
			},
			PaidLeave: {
				required: "Please enter this!!",
				remote: jQuery.format("Only Numeric!!"),
			},
			EffectiveFrom: {
				required: "Please select this!!",
			}
		}   
	});
});
</script>
<?php
}
elseif($Action=="SalaryPayment")
{
	$StaffId=$_GET['Id'];
	$query="select StaffName,StaffMobile from staff where StaffId='$StaffId' and StaffStatus='Active' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count==0)
	{
	?>
	<div class="alert alert-error">Selected staff is either not active or its not a valid Staff Id!!</div>
	<?php
	}
	else
	{
		$query1="select ExpenseId,TransactionAmount,TransactionDate,AccountName,MasterEntryValue,TransactionId,SalaryMonthYear from transaction,expense,masterentry,accounts where
			transaction.TransactionHeadId=expense.ExpenseId and
			TransactionStatus='Active' and
			ExpenseStatus='Active' and
			transaction.TransactionFrom=accounts.AccountId and 
			expense.SalaryPaymentType=masterentry.MasterEntryId and
			expense.StaffId='$StaffId'
			order by SalaryMonthYear desc ";
		$check1=mysqli_query($CONNECTION,$query1);
		$count1=mysqli_num_rows($check1);
		$DATA1=array();
		$QA1=array();
		while($row1=mysqli_fetch_array($check1))
		{
			$ListExpenseId=$row1['ExpenseId'];
			$ListTransactionAmount=$row1['TransactionAmount'];
			$ListTransactionDate=date("d M Y",$row1['TransactionDate']);
			$ListAccountName=$row1['AccountName'];
			$ListSalaryPaymentType=$row1['MasterEntryValue'];
			$ListTransactionId=$row1['TransactionId'];
			$ListSalaryMonthYear=date("M Y",$row1['SalaryMonthYear']);
			$Delete="<a href=DeletePopUp/DeleteStaffSalaryPayment/$ListTransactionId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-cancel\"></span></a>";
			$Note="<a href=Note/StaffSalaryPayment/$ListExpenseId data-toggle=\"modal\" data-target=\"#myModal\"><span class=\"icomoon-icon-clipboard-3\"></span></a>";
			$QA1[]=array($ListTransactionId,$ListTransactionAmount,$ListSalaryPaymentType,$ListSalaryMonthYear,$ListTransactionDate,$ListAccountName,$Delete,$Note);
		}
		$DATA1['aaData']=$QA1;
		$fp = fopen('plugins/Data/data2.txt', 'w');
		fwrite($fp, json_encode($DATA1));
		fclose($fp);
		?>
		
		<form class="form-horizontal" action="Action" name="StaffSalaryPayment" id="StaffSalaryPayment" method="Post">
			<div class="span4">
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="SalaryPaymentType">Payment Type</label>
							<div class="controls sel span8"> 
							<?php GetCategoryValue('SalaryPaymentType','SalaryPaymentType','','','','','',181,''); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="Account">Account</label>
							<div class="span8 controls sel">
							<select tabindex="7" class="nostyle" name="Account" id="Account" style="width:100%;">
							<option></option>
							<?php
							echo $LISTACCOUNT;
							?>
							</select>
							</div>
						</div>
					</div>
				</div>
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="MonthYear">Month Year</label>
							<input tabindex="182" class="span8" id="MonthYear" type="text" name="MonthYear" readonly />
						</div>
					</div>
				</div>	
			</div>
			<div class="span4">
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="Amount">Amount</label>
							<input tabindex="182" class="span8" id="Amount" type="number" name="Amount" />
						</div>
					</div>
				</div>	
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="Remarks">Remarks</label>
							<div class="span8 controls-textarea">
							<textarea tabindex="183" class="span12" name="Remarks" id="Remarks"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="DOP">Payment Date</label>
							<input tabindex="184" class="span8" id="DOP" type="text" name="DOP" readonly />
						</div>
					</div>
				</div>	
				<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
				<input type="hidden" name="Action" value="StaffSalaryPayment" readonly />
				<input type="hidden" name="StaffId" value="<?php echo $StaffId; ?>" readonly />
				<?php $ButtonContent="Save"; ActionButton($ButtonContent,185); ?>
			</div>
		</form>
		<div class="clearfix"></div>
		<Br>
		<div class="box gradient">
			<div class="title">
				<h4>
					<span>Payment List</span>
				</h4>
			<a href="#" class="minimize">Minimize</a>
			</div>
			<div class="content clearfix noPad">
				<table id="SalarySalaryPaymentTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Receipt No</th>
							<th>Amount</th>
							<th>Payment Type</th>
							<th>Month Year</th>
							<th>Date of Payment</th>
							<th>Paid From</th>
							<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
							<th><span class="icomoon-icon-clipboard-3 tip" title="Note"></span></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>	
		
		<script type="text/javascript">
		$(document).ready(function() {
			options = {
			pattern: 'mm-yyyy',
			};
			$('#MonthYear').monthpicker(options);
		if($('#DOP').length) {
			$("#DOP").datepicker({ yearRange: "-10:+10", dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true });
		}
		
		$('#SalarySalaryPaymentTable').dataTable({
			"sPaginationType": "two_button",
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,  
			"bProcessing": true,
			"bDeferRender": true,
			"sAjaxSource": "plugins/Data/data2.txt",
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
			$("#SalaryPaymentType").select2();
			$('#SalaryPaymentType').select2({placeholder: "Select"});
			$("#Account").select2();
			$('#Account').select2({placeholder: "Select"});
			$("#StaffSalaryPayment").validate({
				ignore: 'input[type="hidden"]',
				rules: {
					SalaryPaymentType: {
						required: true,
					},
					Account: {
						required: true,
					},
					Amount: {
						required: true,
						remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Amount"
					},
					DOP: {
						required: true,
					},
					MonthYear: {
						required: true,
					}
				},
				messages: {
					SalaryPaymentType: {
						required: "Please select this!!",
					},
					Account: {
						required: "Please select this!!",
					},
					Amount: {
						required: "Please enter this!!",
						remote: jQuery.format("Only Numeric!!"),
					},
					DOP: {
						required: "Please select this!!",
					},
					MonthYear: {
						required: "Please select this!!",
					}
				}   
			});
		});
		</script>
		
		<?php
	}
}
elseif($Action=="Photo")
{
	$StaffId=$_GET['Id'];
?>
<form class="form-horizontal" action="Action" name="ManagePhotos" id="ManagePhotos" method="Post" enctype="multipart/form-data">
	<div class="span4">
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Title">Title</label>
					<input tabindex="161" class="span8" id="Title" type="text" name="Title"/>
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Resolution">Document</label>
					<div class="span8 controls sel">  
						<?php GetCategoryValue('StaffDocuments','Document',$Document,'','','','',162,''); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="Resolution">Resolution</label>
					<div class="span8 controls sel">  
						<?php GetCategoryValue('Resolution','Resolution',$Resolution,'','','','',163,''); ?>
					</div> 
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="file">Select Image</label>
					<div class="span8 controls sel"> 
					<input type="file" name="file" id="file" tabindex="164" />
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
		<input type="hidden" name="Action" value="ManagePhotos" readonly>
		<input type="hidden" name="UniqueId" value="<?php echo $StaffId; ?>" readonly>
		<input type="hidden" name="Detail" value="StaffDocuments" readonly>
		<?php $ButtonContent="Save"; ActionButton($ButtonContent,166); ?>
	</div>
	<div class="span8">
	<?php
	$query="select PhotoId,Path,Title,MasterEntryValue from photos,masterentry where photos.Document=masterentry.MasterEntryId and UniqueId='$StaffId' and Detail='StaffDocuments' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	if($count>0)
	{
		while($row=mysqli_fetch_array($check))
		{
			$Path=$row['Path'];
			$PhotoId=$row['PhotoId'];
			$Title=$row['Title'];
			$Document=$row['MasterEntryValue'];
			$Path="$PHOTOPATH/thumbnail-$Path";
			if (!file_exists($Path) && $Path!="")
			echo "File Not found";
			else
			echo "<div class=\"span4\"><center><img src=\"$Path\" style=\"border:1px solid #cdcdcd\"> <a href=\"/ActionGet/DeleteDocument/$PhotoId\" $ConfirmProceed><span class=\"icomoon-icon-cancel\"></a></span> <br><b>$Document</b> <br>$Title</center></div>";
		}
	}
	else
	echo "<div class=\"alert alert-error\">No documents uploaded!!</div>";
	?>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$("input, textarea, select").not('.nostyle').uniform();
	$("#Resolution").select2();
	$('#Resolution').select2({placeholder: "Select"});
	$("#Document").select2();
	$('#Document').select2({placeholder: "Select"});
	$("#ManagePhotos").validate({
		ignore: 'input[type="hidden"]',
		rules: {
			Title: {
				required: true,
			},
			Resolution: {
				required: true,
			},
			Document: {
				required: true,
			},
			file: {
				required: true,
			}
		},
		messages: {
			Title: {
				required: "Please enter this field!!",
			},
			Resolution: {
				required: "Please select this field!!",
			},
			Document: {
				required: "Please select this field!!",
			},
			file: {
				required: "Please select this field!!",
			}
		}   
	});
});
</script>
<?php
}
else
{}
?>