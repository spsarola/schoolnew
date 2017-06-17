<?php
$PageName="IssueSchoolMaterial";
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
                <?php $SchoolMaterialCategory="<a href=IssueSchoolMaterial/Books><div class=\"badge badge-important\">Books</div></a>
										<a href=IssueSchoolMaterial/Uniform><div class=\"badge badge-info\">Uniform</div></a>
										<a href=IssueSchoolMaterial/Other><div class=\"badge badge-success\">Other</div></a>";
				$BreadCumb="Issue School Material $SchoolMaterialCategory"; BreadCumb($BreadCumb);  ?>
				<?php DisplayNotification(); 
				$GETAdmissionId=isset($_GET['AdmissionId']) ? $_GET['AdmissionId'] : '';
				$ValidationMessages=$ValidationRules="";
				$query="select class.ClassId,studentfee.AdmissionId,ClassName,SectionName,StudentName,Mobile,FatherName from 
				registration,studentfee,admission,class,section where 
				admission.AdmissionId=studentfee.AdmissionId and
				class.ClassId=section.ClassId and
				studentfee.SectionId=section.SectionId and 
				studentfee.Session='$CURRENTSESSION' and
				registration.RegistrationId=admission.RegistrationId and 
				Status='Studying' ";
				$Select="";
				$ListAllStudent=$ValidStudent="";
				$check=mysqli_query($CONNECTION,$query);
				while($row=mysqli_fetch_array($check))
				{
					$StudentName=$row['StudentName'];
					$FatherName=$row['FatherName'];
					$Mobile=$row['Mobile'];
					$ClassName=$row['ClassName'];
					$ClassId=$row['ClassId'];
					$SectionName=$row['SectionName'];
					$AdmissionId=$row['AdmissionId'];
					if($AdmissionId==$GETAdmissionId)
					{
						$Select="selected";
						$SelectedStudentName=$StudentName;
						$SelectedStudentFatherName=$FatherName;
						$ValidStudent=1;
						$SelectedClassId=$ClassId;
					}
					else
					$Select="";
					$ListAllStudent.="<option value=\"$AdmissionId\" $Select>$StudentName F/n $FatherName ($Mobile) In $ClassName $SectionName</option>";
				}
				$IssueMaterialList=isset($_POST['box2View']) ? $_POST['box2View'] : '';
				if($IssueMaterialList!="")
				$box2View=implode(",",$IssueMaterialList);		
				
				if($ValidStudent==1)
				{
					if($IssueMaterialList=="") 
					{	
						if($MaterialType=="Books")
						{
							$query1="select SchoolMaterialId,Name,SellingPrice,Quantity from schoolmaterial where
								ClassId='$ClassId' and
								Session='$CURRENTSESSION' and
								SchoolMaterialType='Books' and 
								Quantity>0 
								order by Name";
						}
						else
						{
							$query1="select SchoolMaterialId,Name,SellingPrice,Quantity from schoolmaterial where
							SchoolMaterialType='$MaterialType' and 
							Quantity>0 
							order by Name";	
						}
						$check1=mysqli_query($CONNECTION,$query1);
						$ListStockMaterial="";
						while($row1=mysqli_fetch_array($check1))
						{
							$SchoolMaterialId=$row1['SchoolMaterialId'];
							$Name=$row1['Name'];
							$SellingPrice=round($row1['SellingPrice']);
							$Quantity=round($row1['Quantity']);
							$ListStockMaterial.="<option value=\"$SchoolMaterialId\">$Name (Price $SellingPrice $CURRENCY)</option>";
						}
					}					
				
					$query2="select DOI,Total,Paid,IssueId,Material from issue where
							AdmissionId='$GETAdmissionId' and
							Session='$CURRENTSESSION' and
							ClassId='$SelectedClassId' and 
							IssueStatus='Active' and
							MaterialType='$MaterialType' ";
					$check2=mysqli_query($CONNECTION,$query2);
					$count2=mysqli_num_rows($check2);
					$ListIssued="";
					$TotalItemCount=0;
					if($count2>0)
					{
						while($row2=mysqli_fetch_array($check2))
						{
							$ListIssueId=$row2['IssueId'];
							$ListMaterial=$row2['Material'];
							$Item=explode(",",$ListMaterial);
							$ItemCount=0;
							foreach($Item as $ItemValue)
							{
								$ItemList=explode("-",$ItemValue);
								$ItemCount+=$ItemList[1];
								$TotalItemCount+=$ItemList[1];
							}
							$DeleteIssued="<a href=# onclick=\"showdetail($ListIssueId,'DeleteIssued','DeleteIssued')\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
							$IssueMakePayment="<a href=# onclick=\"showdetail($ListIssueId,'IssueMakePayment','IssueMakePayment')\"><span class=\"icomoon-icon-coin tip\" title=\"Payment\"></span></a>";
							$ListDOI=date("d M Y,h:ia",$row2['DOI']);
							$ListTotal=round($row2['Total'],2);
							$ListPaid=round($row2['Paid'],2);
							$ListIssued.="<tr>
								<Td>$ListIssueId</td>
								<Td><a href=PopUp/IssueIndividualList/$ListIssueId data-toggle=\"modal\" data-target=\"#myModal\">$ListDOI ($ItemCount item)</a></td>
								<td>$ListTotal $CURRENCY</td>
								<td>$ListPaid $CURRENCY</td>
								<td>$IssueMakePayment</td>
								<td>$DeleteIssued</td>
								</tr>";
						}
					}
									
				}
				?>

                <div class="row-fluid">
                    <div class="span6">				
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span>Select Student</span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content" style="padding-bottom:0;">
								<form class="form-horizontal" action="ReportAction" name="IssueSchoolMaterial" id="IssueSchoolMaterial" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
											<label class="form-label span4" for="normal">Student</label> 
												<div class="span8 controls sel">   
												<select tabindex="1" name="AdmissionId" id="AdmissionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListAllStudent; ?>
												</select>
												</div> 
											</div>
										</div> 
									</div>
									<?php $ButtonContent="Get Detail"; ActionButton($ButtonContent,2); ?>
									<input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="Action" value="IssueSchoolMaterial" readonly>
								</form>
							</div>
						</div>
						
						<?php if($ValidStudent==1) { ?>
						<div class="box chart gradient">
							<div class="title">
								<h4>
									<span><?php echo $MaterialType; ?> Item Issue History of <?php echo "$SelectedStudentName (F/n $SelectedStudentFatherName) "; ?></span>
								</h4>
								<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content" style="padding-bottom:0;">
							<?php
							if($count2>0) {
							?>
							<table class="responsive table table-bordered">
							<thead>
								<tr>
									<th>Issue No</th>
									<th>Date</th>
									<Th>Total</th>
									<th>Paid</th>
									<th><span class="icomoon-icon-coin tip" title="Payment"></span></th>
									<th><span class="icomoon-icon-cancel tip" title="Delete"></span></th>
								</tr>
							</thead>
							<tbody>
								<?php echo $ListIssued; ?>
							</tbody>
							</table>
							<?php } else  {?>
							<div class="alert alert-error">No material issued in selected session!!</div>
							<?php } ?>
							</div>
						</div>						
						<?php } ?>
						
					</div>
					
					<div class="span6">
						<?php if($ValidStudent==1) { ?>
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select <?php echo $MaterialType;?> Items</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<?php if($IssueMaterialList=="") { ?>
								<form class="form-horizontal" action="" method="post" id="Issue" name="Issue">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="leftBox">
												<div class="searchBox"><input type="text" id="box1Filter" class="searchField" placeholder="Search"/><button id="box1Clear" type="button" class="btn"><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
												<select id="box1View" multiple="multiple" class="multiple nostyle" style="height:200px;">
												<?php echo $ListStockMaterial; ?>
												</select>
												<br/>
												<span id="box1Counter" class="count"></span>
												<div class="dn"><select id="box1Storage" name="box1Storage" class="nostyle"></select></div>
											</div>
											<div class="dualBtn">
												<button id="to2" type="button" class="btn" ><span class="icon12 minia-icon-arrow-right-3"></span></button>
												<button id="allTo2" type="button" class="btn" ><span class="icon12 iconic-icon-last"></span></button>
												<button id="to1" type="button" class="btn marginT5"><span class="icon12 minia-icon-arrow-left-3"></span></button>
												<button id="allTo1" type="button"class="btn marginT5" ><span class="icon12 iconic-icon-first"></span></button>
											</div>
											<div class="rightBox">
												<div class="searchBox"><input type="text" id="box2Filter" class="searchField" placeholder="Search" /><button id="box2Clear"  type="button" class="btn" ><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
												<select id="box2View" name="box2View[]" multiple="multiple" class="multiple nostyle" style="height:200px;"></select><br/>
												<span id="box2Counter" class="count"></span>
												<div class="dn"><select id="box2Storage" class="nostyle"></select></div>
											</div>
										</div> 
									</div>
									   <?php $ButtonContent="Proceed"; ActionButton($ButtonContent,3); ?>
									   <input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
									   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									   <input type="hidden" name="AdmissionId" value="<?php echo $GETAdmissionId; ?>" readonly>
								</form>
								<?php } else { ?>
								<form class="form-horizontal" action="Action" method="post" id="IssueConfirm" name="IssueConfirm">
								<?php	
									if($MaterialType=="Books")
									$query11="select SchoolMaterialId,Quantity,Name,SellingPrice from schoolmaterial where 
										ClassId='$ClassId' and Session='$CURRENTSESSION' and SchoolMaterialType='$MaterialType' ";
									else
									$query11="select SchoolMaterialId,Quantity,Name,SellingPrice from schoolmaterial where SchoolMaterialType='$MaterialType' ";
									$check11=mysqli_query($CONNECTION,$query11);
									while($row11=mysqli_fetch_array($check11))
									{
										$SellingPriceIdArray[]=round($row11['SellingPrice'],2);
										$SchoolMaterialIdArray[]=$row11['SchoolMaterialId'];
										$QuantityArray[]=$row11['Quantity'];
										$NameArray[]=$row11['Name'];
									}
									$TabIndex="4";
									foreach($IssueMaterialList as $IssueMaterialListValue)
									{
										$SearchIndex=array_search($IssueMaterialListValue,$SchoolMaterialIdArray);
										$AvailableQuantity=$QuantityArray[$SearchIndex];
										$MaterialSellingPrice=$SellingPriceIdArray[$SearchIndex];
										$MaterialName=$NameArray[$SearchIndex];
										$FieldName="Name_$IssueMaterialListValue";
										?>
											<div class="form-row row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<label class="form-label span4" for="normal"><?php echo $MaterialName; ?><span class="help-block"><?php echo "@ $MaterialSellingPrice $CURRENCY Per Item"; ?></span></label>
														<input <?php echo "tabindex=\"$TabIndex\""; ?> class="span8" id="<?php echo $FieldName; ?>" type="text" name="<?php echo $FieldName; ?>" />
													</div>
												</div>
											</div>								
										<?php
										$ValidationRules.="$FieldName: {
															required: true,max:$AvailableQuantity,
															remote: \"RemoteValidation?Action=IsAmountWithoutZero&Id=$FieldName\"
														},";
										$ValidationMessages.="$FieldName: {
														required: \"Please enter this!!\",max:\"Only $AvailableQuantity available!!\",
														remote: jQuery.format(\"Numeric and Greater than zero!!\")
														},";
										$TabIndex++;
									}
									?>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Date</label>
												<input <?php echo "tabindex=\"$TabIndex\""; $TabIndex++;?> class="span8" id="DOI" type="text" name="DOI" readonly />
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Remarks</label>
												<div class="controls-textarea span8">
												<textarea <?php echo "tabindex=\"$TabIndex\""; $TabIndex++;?> id="Remarks" name="Remarks" class="span12"></textarea>
												</div>
											</div>
										</div>
									</div>
								   <input type="hidden" name="Action" value="Issue" readonly>
								   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
								   <input type="hidden" name="MaterialType" value="<?php echo $MaterialType; ?>" readonly>
								   <input type="hidden" name="AdmissionId" value="<?php echo $GETAdmissionId; ?>" readonly>
								   <input type="hidden" name="ClassId" value="<?php echo $SelectedClassId; ?>" readonly>
								   <input type="hidden" name="box2View" value="<?php echo $box2View; ?>" readonly>
									<?php $ButtonContent="Issue"; ActionButton($ButtonContent,$TabIndex); ?>
								</form>								
								<?php } ?>
							</div>
						</div>							
						<?php } elseif($GETAdmissionId!="" && $ValidStudent!=1) { ?>
						<div class="alert alert-error">Selected student is not valid!!</div>
						<?php } else { ?>
						<div class="alert alert-info">Please select student!!</div>
						<?php } ?>
						
					</div>
					
				</div>				
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		if($('#DOI').length) {
			$("#DOI").datetimepicker({ dateFormat: 'dd-mm-yy' });
		}
		if($('#DOP').length) {
		$('#DOP').datetimepicker({ dateFormat: 'dd-mm-yy' });
		}
		$.configureBoxes();
		$("#AdmissionId").select2(); 
		$('#AdmissionId').select2({placeholder: "Select"}); 
			
		$("#IssueConfirm").validate({
		rules: {<?php echo $ValidationRules; ?>
				DOI: {
					required: true,
				}
				},
		messages: {<?php echo $ValidationMessages; ?>
				DOI: {
					required: "Please select Date!!",
				}}   
		});		
		$("#IssueMakePaymentForm").validate({
			rules: {
				DOP: {
					required: true,
				},
				Amount: {
					required: true,
					remote: "RemoteValidation?Action=IsAmountWithoutZero&Id=Amount"
				},
				Remarks: {
					required: true,
				}
			},
			messages: {
				DOP: {
					required: "Please select Date!!",
				},
				Amount: {
					required: "Please enter fee Amount!!",
					remote: jQuery.format("Amount should be numeric!!")
				},
				Remarks: {
					required: "Please enter Remarks!!",
				}
			}   
		});		
	});
</script>
<?php
include("Template/Footer.php");
?>