<?php
include("Include.php");
$q=$_GET['q'];
$Action=$_GET['Action'];
if($Action=="GetAccountTypeDetail")
{
	$qName=GetCategoryValueOfId($q,'AccountType');
	if($qName=="Bank")
	{
	?>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="AccountName">Bank Account Number</label>
					<input tabindex="2" class="span8" id="AccountName" type="text" name="AccountName" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="BankAccountName">Bank Account Name</label>
					<input tabindex="2" class="span8" id="BankAccountName" type="text" name="BankAccountName" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="BankName">Bank Name</label>
					<input tabindex="2" class="span8" id="BankName" type="text" name="BankName" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="BranchName">Branch Name</label>
					<input tabindex="2" class="span8" id="BranchName" type="text" name="BranchName" />
				</div>
			</div>
		</div>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="IFSCCode">IFSC Code</label>
					<input tabindex="2" class="span8" id="IFSCCode" type="text" name="IFSCCode" />
				</div>
			</div>
		</div>
	<?php
	}
	elseif($qName=="Cash")
	{
	?>
		<div class="form-row row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span4" for="AccountName">Account Name</label>
					<input tabindex="2" class="span8" id="AccountName" type="text" name="AccountName" />
				</div>
			</div>
		</div>	
	<?php
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="GetAttendanceReportForADay")
{
	$CurrentDateTimeStamp=strtotime($q);
	$DateName=date("D, d M Y",$CurrentDateTimeStamp);
	$query1="select StaffName,StaffId,MasterEntryValue from staff,masterentry where 
	staff.StaffPosition=masterentry.MasterEntryId and staff.StaffStatus='Active' order by StaffName ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	while($row1=mysqli_fetch_array($check1))
	{
		$StaffNameArray[]=$row1['StaffName'];
		$StaffIdArray[]=$row1['StaffId'];
		$StaffPositionArray[]=$row1['MasterEntryValue'];
	}	
	
	$Show="<table class=\"responsive table table-bordered\">
		<thead>
		  <tr>
			<th>Staff Name</th>
			<th>P</th>
			<th>A</th>
			<th>H</th>
			<th>HD</th>
			<th>OD</th>
			<th>PL</th>
		  </tr>
		</thead>
		<tbody>";
	
	$query2="Select Attendance from staffattendance where Date='$CurrentDateTimeStamp' ";
	$check2=mysqli_query($CONNECTION,$query2);
	$count2=mysqli_num_rows($check2);
	if($count2>0)
	{
		$row2=mysqli_fetch_array($check2);
		$Attendance=explode(",",$row2['Attendance']);
		foreach($Attendance as $AttendanceValue)
		{
			$AttendanceString=explode("-",$AttendanceValue);
			$MarkedStaffId=$AttendanceString[0];
			$MarkedAtt=$AttendanceString[1];
			
			$SearchIndex=array_search($MarkedStaffId,$StaffIdArray);
			if($SearchIndex===FALSE)
			{}
			else
			{
				$StaffName=$StaffNameArray[$SearchIndex];
				$Show.="<tr>
				<td>$StaffName</td><td>";
				if($MarkedAtt=="P") { $Show.="P"; }
				$Show.="</td><td>";
				if($MarkedAtt=="A") { $Show.="A"; }
				$Show.="</td><td>";
				if($MarkedAtt=="H") { $Show.="H"; }
				$Show.="</td><td>";
				if($MarkedAtt=="HD") { $Show.="HD"; }
				$Show.="</td><td>";
				if($MarkedAtt=="OD") { $Show.="OD"; }
				$Show.="</td><td>";
				if($MarkedAtt=="PL") { $Show.="CL"; }
				$Show.="</td></tr>";
			}
		}
	}
		$Show.="</tbody>
			</table>";
?>
	<div class="box chart gradient">
		<div class="title">
			<h4>
				<span>Attendance Report for <?php echo $DateName; ?></span>
			</h4>
			<a href="#" class="minimize">Minimize</a>
		</div>
		<div class="content" style="padding-bottom:0;">
		<?php if($count2>0) echo "$Show"; else { $Message="Attendance not marked for $DateName !!"; $Type="alert-error"; ShowNotification($Message,$Type); }  ?>
		</div>
	</div>
<?php
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="DeleteStudentRegistration")
{
	$query1="select StudentName,FatherName,Mobile from registration where RegistrationId='$q' and registration.Session='$CURRENTSESSION' ";
	$check1=mysqli_query($CONNECTION,$query1);
	$count1=mysqli_num_rows($check1);
	if($count1>0)
	{
		$row1=mysqli_num_rows($check1);
		$StudentName=$row1['StudentName'];
		$FatherName=$row1['FatherName'];
		$Mobile=$row1['Mobile'];
	}
	
	$query="select AdmissionId from admission where RegistrationId='$q' ";
	$check=mysqli_query($CONNECTION,$query);
	$count=mysqli_num_rows($check);
	
	if($count1==0)
	{
	?>
	<div class="alert alert-error">This is not a valid registration delete URL!!</div>
	<?php
	}
	elseif($count>0)
	{
	?>
	<div class="alert alert-error">This registration cannot be deleted because admission is already confirmed!!</div>
	<?php
	}
	else
	{
	?>
		<div class="box chart gradient">
			<div class="title">
				<h4>
					<span>Delete Registration</span>
				</h4>
				<a href="#" class="minimize">Minimize</a>
			</div>
			<div class="content" style="padding:5px;">
				<div class="alert alert-error">You cannot recover it after deletion!!</div>
				<div class="form-row row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<label class="form-label span4" for="normal">Password</label>
							<input tabindex="101" class="span8" type="password" name="Password" id="Password" placeholder="Password" />
						</div>
					</div>
				</div>
				<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
				<input type="hidden" name="Action" value="DeleteStudentRegistration" readonly />
				<input type="hidden" name="RegistrationId" value="<?php echo $q; ?>" readonly />
				<?php SetDeleteButton(102); ?>
			</div>
		</div>
	<?php
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="GetFixedSalaryHead")
{
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
		
	$query="select FixedSalaryHead from salarystructure where SalaryStructureStatus='Active' and SalaryStructureId='$q' ";
	$check=mysqli_query($CONNECTION,$query);
	$row=mysqli_fetch_array($check);
	$FixedSalaryHeadArray=explode(",",$row['FixedSalaryHead']);
	foreach($FixedSalaryHeadArray as $FixedSalaryHeadArrayValue)
	{
		$FieldName="SalaryHead-$FixedSalaryHeadArrayValue";
		$SearchIndex=array_search($FixedSalaryHeadArrayValue,$SalaryHeadIdArray);
		$SalaryHead=$SalaryHeadArray[$SearchIndex];
		echo "<div class=\"form-row row-fluid\">
					<div class=\"span12\">
						<div class=\"row-fluid\">
							<label class=\"form-label span4\" for=\"normal\">$SalaryHead</label>
							<input tabindex=\"111\" class=\"span8\" type=\"number\" name=\"$FieldName\" id=\"$FieldName\" required />
						</div>
					</div>
				</div>";
	}
	
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="TransferIndividualStock")
{
	$qArray=explode("-",$q);
	$q=$qArray[0];
	$From=$qArray[1];
	if($From=="NotAssigned")
	$query="select StockName,(OpeningStock+CurrentStock) as Quantity from stock where StockId='$q' ";
	elseif($From=="Transfer")
	$query="select StockName,(Quantity-Returning) as Quantity from stockassign,stock where stock.StockId=stockassign.StockId and StockAssignId='$q' ";
	$check=mysqli_query($CONNECTION,$query);
	$row=mysqli_fetch_array($check);
	$StockName=$row['StockName'];
	$Quantity=round($row['Quantity'],2);
?>
	<?php if($From=="NotAssigned") { ?>
	<input type="hidden" name="StockId" value="<?php echo $q; ?>" readonly>
	<?php } elseif($From=="Transfer") { ?>
	<input type="hidden" name="StockAssignId" value="<?php echo $q; ?>" readonly>
	<?php } ?>
	<div class="box chart gradient">
		<div class="title">
			<h4>
				<span>Transfer Stock "<?php echo $StockName; ?>" ?? </span>
			</h4>
		</div>
		<div class="content" style="padding-bottom:0;">
		<?php if($Quantity>0) { ?>
			<div class="form-row row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<label class="form-label span4" for="normal">Transfer To</label>
						<div class="span8 controls sel">
							<?php GetCategoryValue('AssignTo','AssignTo','','1','TransferIndividualStock2','TransferIndividualStock2','',10,''); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-row row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<label class="form-label span4" for="normal">Quantity</label>
						<input class="span8" id="Quantity" type="text" name="Quantity" placeholder="Less than <?php echo $Quantity; ?>" />
					</div>
				</div>
			</div>
			<div id="TransferIndividualStock2"></div>
		<?php } else { ?>
		<div class="alert alert-error"><?php echo $StockName; ?> is not available to transfer!!</div>
		<?php } ?>
		</div>
	</div>
<?php
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="PurchaseDiv")
{
	$query="select Unit from stock where StockId='$q' ";
	$check=mysqli_query($CONNECTION,$query);
	$row=mysqli_fetch_array($check);
	$Unit=$row['Unit'];
	$UnitName=GetCategoryValueOfId($Unit,'Unit');
	?>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="normal">Price
				<?php if($Unit!="0")
				echo "Per $UnitName";
				?>
				</label>
				<input tabindex="3" type="text" class="span8" name="PurchasePrice" id="PurchasePrice">
			</div>
		</div>
	</div>	
	<?php
	if($Unit!="0")
	{
	?>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="normal">Quantity <?php echo " In $UnitName"; ?></label>
				<input tabindex="4" type="text" class="span8" name="Quantity" id="Quantity">
			</div>
		</div>
	</div>		
	<?php
	}
	?>
	<div class="form-row row-fluid">
		<div class="span12">
			<div class="row-fluid">
				<label class="form-label span4" for="normal">Other Info</label>
				<div class="span8 controls-textarea">
				<textarea tabindex="5" class="span12" name="OtherInfo" id="OtherInfo"></textarea>
				</div>
			</div>
		</div>
	</div>	
	<?php
}
//////////////////////////////////////////////////////////////////////////////////////////////////
elseif($Action=="UpdateClassDetail")
{
	$q=explode("-",$q);
	$SectionId=$q[0];
	$Distance=$q[1];
	$query="select Amount,MasterEntryValue,FeeId from fee,masterentry where
		fee.FeeType=masterentry.MasterEntryId and
		fee.SectionId='$SectionId' and
		fee.Session='$CURRENTSESSION' and (Distance='' or Distance='$Distance')";
	$check=mysqli_query($CONNECTION,$query);
	$TabIndex=101;
	while($row=mysqli_fetch_array($check))
	{
		$TabIndex++;
		$FeeId=$row['FeeId'];
		$Amount=$row['Amount'];
		$FeeName=$row['MasterEntryValue'];
		?>
			<div class="form-row row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<label class="form-label span4" for="normal"><?php echo "$FeeName"; ?></label>
						<input tabindex="<?php echo $TabIndex; ?>" type="number" value="<?php echo $Amount; ?>" class="span8" name="<?php echo $FeeId;?>" id="<?php echo $FeeId;?>">
					</div>
				</div>
			</div>			
		<?php
	}	$ButtonContent="Confirm"; ActionButton($ButtonContent,$TabIndex); 
}
/////////////////////////////////////////////////////////////////////////////////////////
?>