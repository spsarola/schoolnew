<?php
$PageName="SMS";
$TooltipRequired=1;
$SearchRequired=1;
$FormRequired=1;
$TableRequired=1;
include("Include.php");
include("SMSFunction.php");
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
                <?php 
				$Connected=InternetConnection();
				if($Connected==true)
				{
					$SMSBalanceCount=CheckBalance($AuthKey,$BaseURL);
					if(!is_numeric($SMSBalanceCount) && $SMSBalanceCount=="code: 201")
						$SMSBalance="<span class=\"badge badge-important\">Invalid Username or Password!!</span>";
					elseif(is_numeric($SMSBalanceCount))
					{	$SMSBalance="<span class=\"badge badge-success\">SMS Balance: $SMSBalanceCount</span>"; }
					else
						$SMSBalance="<span class=\"badge badge-important\">Unknown Error</span>";		
				}	
				else
				{
					$SMSBalance="<span class=\"badge badge-important\">No internet Connection</span>";
				}
				
				$AccountType=isset($_GET['AccountType']) ? $_GET['AccountType'] : '';
				if($AccountType!="Students" && $AccountType!="Staff")
				$AccountType="Students";
				$AccType="<a href=\"SMS/Students\"><span class=\"badge badge-success\">Students</span></a> <a href=\"SMS/Staff\"><span class=\"badge badge-important\">Staff</span></a>";
				$BreadCumb="Send SMS to $AccType $SMSBalance"; BreadCumb($BreadCumb); 
				?>
				<?php DisplayNotification(); 
				$POSTSectionId=isset($_POST['SectionId']) ? $_POST['SectionId'] : '';
				$QueryString=$SelectedClass=$ValidSectionId=$ListClass=$ListData="";
				if($POSTSectionId!="")
				{
					foreach($POSTSectionId as $POSTSectionIdValue)
					{
						if($QueryString=="")
						$QueryString="studentfee.SectionId='$POSTSectionIdValue' ";
						else
						$QueryString.=" or studentfee.SectionId='$POSTSectionIdValue' ";
					}
					$QueryString="( $QueryString )";
				}
				
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					
					if($POSTSectionId!="")
					foreach($POSTSectionId as $POSTSectionIdValue)
					{
						if($POSTSectionIdValue==$ComboCurrentSectionId)
						{
							$SelectedClass="selected";
							$ValidSectionId=1;
							break;
						}
						else
						$SelectedClass="";
					}
					$ListClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				
				if($ValidSectionId==1 && $AccountType=="Students")
				{
					$query1="select admission.AdmissionId,StudentName,FatherName,Mobile from studentfee,registration,admission where 
					studentfee.AdmissionId=admission.AdmissionId and Status='Studying' and
					registration.RegistrationId=admission.RegistrationId and
					studentfee.Session='$CURRENTSESSION' and
					$QueryString
					order by StudentName ";
					$check1=mysqli_query($CONNECTION,$query1);
					while($row1=mysqli_fetch_array($check1))
					{
						$AdmissionId=$row1['AdmissionId'];
						$StudentName=$row1['StudentName'];
						$Mobile=$row1['Mobile'];
						$FatherName=$row1['FatherName'];
						$ListData.="<option value=\"$AdmissionId-$StudentName-$Mobile\">$StudentName ($FatherName)</option>";
					}
				}
				elseif($AccountType=="Staff")
				{
					$query1="select StaffName,StaffId,MasterEntryValue,StaffMobile from staff,masterentry where 
					staff.StaffPosition=masterentry.MasterEntryId and staff.StaffStatus='Active' order by StaffName ";
					$check1=mysqli_query($CONNECTION,$query1);
					while($row1=mysqli_fetch_array($check1))
					{
						$StaffName=$row1['StaffName'];
						$Mobile=$row1['StaffMobile'];
						$StaffId=$row1['StaffId'];
						$StaffPosition=$row1['MasterEntryValue'];
						$ListData.="<option value=\"$StaffId-$StaffName-$Mobile\">$StaffName</option>";
					}
				}
				?>
				
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Send SMS</span>
                                </h4>
                                <a href="#" class="minimize tip" title="Minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding-bottom:0;">
								<?php if($AccountType=="Students") { ?>
								<form class="form-horizontal" action="" name="Filter" id="Filter" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="SectionId" readonly>Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SectionId[]" id="SectionId" class="nostyle" style="width:100%;" multiple="multiple">
												<option></option>
												<?php echo $ListClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
								   <?php $ButtonContent="Get List"; ActionButton($ButtonContent,2); ?>
								   <input type="hidden" name="GetList" value="Yes" readonly>
								   <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
								</form>
								<?php } ?>
								
								<?php if($Connected==false) { ?>
								<div class="alert alert-error">No internet Connection!!</div>
								<?php } elseif($SMSBalanceCount==0 || !is_numeric($SMSBalanceCount)) { ?>
								<div class="alert alert-error">No SMS Balance!!</div>								
								<?php } else { ?>
								<form class="form-horizontal" action="Action" name="SendSMS" id="SendSMS" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="leftBox">
												<div class="searchBox"><input type="text" id="box1Filter" class="searchField" placeholder="Search"/><button id="box1Clear" type="button" class="btn"><span class="icon12  icomoon-icon-cancel-3"></span></button></div>
												<select id="box1View" multiple="multiple" class="multiple nostyle" style="height:200px;">
												<?php echo $ListData; ?>
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
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="textarea">Message Content
                                                    <span class="help-block"></span>
												</label>
												<div class="span8 controls-textarea">   
												<div class="alert alert-info"><b>You can send maximum <?php echo $SMSBalanceCount; ?> SMS!!</b></div>
												<textarea class="span8 limit" id="Content" name="Content" rows="3"></textarea>
												</div>
											</div>
										</div>  
									</div>									
									<input type="hidden" name="Action" value="SendSMS" readonly>
									<input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
									<input type="hidden" name="SMSBalance" value="<?php echo $SMSBalanceCount; ?>" readonly>
									<?php $ButtonContent="Send"; SetButton($ButtonContent,5); ?>
								</form>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>				
				
            </div>
        </div>

<script type="text/javascript">
$(document).ready(function() {
	$.configureBoxes();
	if ($('textarea').hasClass('limit')) {
		$('.limit').inputlimiter({
			limit: 160
		});
	}		
	$("#SectionId").select2();
	$('#SectionId').select2({placeholder: "Select"});
	$("input, textarea, select").not('.nostyle').uniform();
	$("#Filter").validate({
		rules: {
			University: {
				required: true,
			},
			Course: {
				required: true,
			},
			Batch: {
				required: true,
			}
		}  
	});
});
</script>
<?php
include("Template/Footer.php");
?>