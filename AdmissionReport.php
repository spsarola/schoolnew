<?php
$PageName="AdmissionReport";
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
			<?php $BreadCumb="Admission Report"; BreadCumb($BreadCumb); ?>
				
				<?php DisplayNotification(); 
				$GETSectionId=isset($_GET['SectionId']) ? $_GET['SectionId'] : '';
				$StudentStatus=isset($_SESSION['StudentStatus']) ? $_SESSION['StudentStatus'] : '';
				$LoginDetail=isset($_SESSION['LoginDetail']) ? $_SESSION['LoginDetail'] : '';
				$StudentStatusChecked=$ListDOB=$LoginDetailChecked="";
				if($StudentStatus=="Yes")
				{
					$StudentStatusChecked="checked=checked";
					$StudentStatus="Terminated";
				}
				else
				$StudentStatus="";
				if($LoginDetail=="Yes")
				$LoginDetailChecked="checked=checked";
				unset($_SESSION['LoginDetail']);
				$SelectedClass=$ValidSectionId=$ListCurrentClass=$SectionQuery=$Print3=$ListClassName=$ListAddress="";	
				$query3="select ClassName,SectionName,SectionId from class,section where 
					class.ClassId=section.ClassId and class.ClassStatus='Active' and
					section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
				$check3=mysqli_query($CONNECTION,$query3);
				while($row3=mysqli_fetch_array($check3))
				{
					$ComboCurrentClassName=$row3['ClassName'];
					$ComboCurrentSectionName=$row3['SectionName'];
					$ComboCurrentSectionId=$row3['SectionId'];
					if($GETSectionId==$ComboCurrentSectionId)
					{
						$SelectedClass="selected";
						$ValidSectionId=1;
					}
					else
					$SelectedClass="";
					$ListCurrentClass.="<option value=\"$ComboCurrentSectionId\" $SelectedClass>$ComboCurrentClassName $ComboCurrentSectionName</option>";
				}
				if($GETSectionId!="" && $ValidSectionId==1)
				$SectionQuery="and studentfee.SectionId='$GETSectionId' ";
				
				$query="select ParentsPassword,StudentsPassword,PresentAddress,studentfee.AdmissionNo,StudentName,FatherName,Mobile,Date,DOB,ClassName,SectionName from registration,admission,studentfee,class,section where
					registration.RegistrationId=admission.RegistrationId and
					admission.AdmissionId=studentfee.AdmissionId and
					studentfee.StudentFeeStatus='$StudentStatus' and class.ClassId=section.ClassId and section.SectionId=studentfee.SectionId and 
					studentfee.Session='$CURRENTSESSION' $SectionQuery";
				$check=mysqli_query($CONNECTION,$query);
				$count=mysqli_num_rows($check);
				$DATA=array();
				$QA=array();
				while($row=mysqli_fetch_array($check))
				{
					$ListStudentName=$row['StudentName'];
					$ListAdmissionNo=$row['AdmissionNo'];
					$ListFatherName=$row['FatherName'];
					$ListClassName=$row['ClassName'];
					$ListSectionName=$row['SectionName'];
					$ListParentsPassword=$row['ParentsPassword'];
					$ListStudentsPassword=$row['StudentsPassword'];
					$ListParentsUsername="$ListAdmissionNo@parents";
					$ListStudentsUsername="$ListAdmissionNo@student";
					//$ListAddress=$row['PresentAddress'];
					$ListMobile=$row['Mobile'];
					$ListDate=date("d M Y",$row['Date']);
					if($row['DOB']!="")
					$ListDOB=date("d M Y",$row['DOB']);
					if($LoginDetail!="Yes")
					{
						$Print3.="<tr class=\"odd gradeX\">
								<td>$ListAdmissionNo</td>
								<td>$ListStudentName</td>
								<td>$ListFatherName</td>
								<td>$ListClassName $ListSectionName</td>
								<td>$ListMobile</td>
								<td>$ListDate</td>
								<td>$ListAddress</td>
								<td>$ListDOB</td>
							</tr>";
						$ListClassName.=" $ListSectionName";
						$QA[]=array($ListAdmissionNo,$ListStudentName,$ListFatherName,$ListClassName,$ListMobile,$ListDate,$ListAddress,$ListDOB);
					}
					else
					{
						$Print3.="<tr class=\"odd gradeX\">
								<td>$ListAdmissionNo</td>
								<td>$ListStudentName</td>
								<td>$ListParentsUsername</td>
								<td>$ListParentsPassword</td>
								<td>$ListStudentsUsername</td>
								<td>$ListStudentsPassword</td>
							</tr>";
						$QA[]=array($ListAdmissionNo,$ListStudentName,$ListParentsUsername,$ListParentsPassword,$ListStudentsUsername,$ListStudentsPassword);	
					}
				}
				$DATA['aaData']=$QA;
				$fp = fopen('plugins/Data/data1.txt', 'w');
				fwrite($fp, json_encode($DATA));
				fclose($fp);
				
				?>	
					
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box chart gradient">
                            <div class="title">
                                <h4>
                                    <span>Select Class</span>
                                </h4>
                                <a href="#" class="minimize">Minimize</a>
                            </div>
                            <div class="content" style="padding:5px;">
								<form class="form-horizontal" action="/ReportAction" name="AdmissionReport" id="AdmissionReport" method="Post">
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4 mandatory" for="SectionId">Class</label>
												<div class="controls sel span8">   
												<select tabindex="1" name="SectionId" id="SectionId" class="nostyle" style="width:100%;" >
												<option></option>
												<?php echo $ListCurrentClass; ?>
												</select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-row row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<label class="form-label span4" for="normal">Status</label>
												<input tabindex="2" class="styled" id="StudentStatus" type="checkbox" name="StudentStatus" <?php echo $StudentStatusChecked; ?> value="Yes" /> Show only Terminated Student
												<input tabindex="2" class="styled" id="LoginDetail" type="checkbox" name="LoginDetail" <?php echo $LoginDetailChecked; ?> value="Yes" /> Show Student's & Parent's Login
											</div>
										</div>
									</div>
									<input type="hidden" name="Action" value="AdmissionReport" readonly>
									<?php $ButtonContent="Get Student List"; ActionButton($ButtonContent,2); ?>
								</form>
                            </div>
                        </div>
                    </div>	
				</div>
                <div class="row-fluid">
					<div class="span12">
						<div class="box gradient">
							<div class="title">
								<h4>
									<span>Admission Report <?php echo "Session : $CURRENTSESSION"; ?> </span>
									<?php if($count>0) { ?>
									<div class="PrintClass">
										<form method=post action=Print target=_blank>
										<input type="hidden" name="Action" value="Print" readonly>
										<input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
										<input type="hidden" name="SessionName" value="PrintAdmissionReportList" readonly>
										<input type="hidden" name="HeadingName" value="PrintAdmissionReportHeading" readonly>
										<button class="icomoon-icon-printer-2 tip" title="Print Admission Report List"></button>
										</form>
									</div>
									<?php } ?>
								</h4>
							<a href="#" class="minimize">Minimize</a>
							</div>
							<div class="content noPad clearfix">
							<?php
								$Print1="<table id=\"AdmissionReportTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
									<thead>
										<tr>
											<th>Adm No</th>
											<th>Student Name</th>";
											if($LoginDetail!="Yes")
											{
											$Print1.="<th>Father Name</th>
											<th>Class</th>
											<th>Mobile</th>
											<th>Date of Admission</th>
											<th>Address</th>
											<th>Date of Birth</th>";
											}
											else
											{
											$Print1.="<th>Parents Username</th>
											<th>Parents Password</th>
											<th>Students Username</th>
											<th>Students Password</th>";											
											}
											echo $Print1;
											$Print2="</tr>
									</thead>
									<tbody>";
									echo $Print2;
									$Print4="</tbody>
								</table>";
								echo $Print4;
								$PrintList="$Print1 $Print2 $Print3 $Print4";
								$_SESSION['PrintAdmissionReportList']=$PrintList;
								$PrintHeading="Showing List of Admission Report";
								$_SESSION['PrintAdmissionReportHeading']=$PrintHeading;
								$_SESSION['PrintCategory']="Admission Report Session : $CURRENTSESSION";
							?>
							</div>
						</div>					
					</div>
                </div>
            </div>
        </div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$('#AdmissionReportTable').dataTable({
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
		
		$('#example').dataTable( {
        "sDom": 'T<"clear">lfrtip',
        "oTableTools": {
            "sSwfPath": "plugins/swf/copy_csv_xls_pdf.swf"
        }
		} );
		
		$("#SectionId").select2();
		$('#SectionId').select2({placeholder: "Select"});
		$("input, textarea, select").not('.nostyle').uniform();
	});
</script>
<?php
include("Template/Footer.php");
?>