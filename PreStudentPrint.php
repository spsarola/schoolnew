<?php
$PageName = "Student Listing";
$TooltipRequired = 1;
$SearchRequired = 1;
$FormRequired = 1;
$TableRequired = 1;
include("Include.php");
IsLoggedIn();

include("Template/HTML.php");
include("Template/Header.php");
include("Template/Sidebar.php");

//SELECT THE DATA OF STUDENT
$query = "SELECT RegistrationId,Session,Status,StudentName,FatherName,FatherMobile,MotherName,DOB,Gender,Category,
        (SELECT CONCAT(c.ClassName,'-',s.SectionName)FROM class AS c,section AS s WHERE c.ClassId=s.ClassId AND c.ClassStatus='Active' AND s.SectionStatus='Active' AND c.Session='$CURRENTSESSION' AND s.SectionId =r.SectionId) AS class,
        (select p.Path FROM photos AS p WHERE p.Document='85' AND p.UniqueId=r.RegistrationId AND p.Detail='StudentDocuments' LIMIT 1 ) AS photo
        FROM `registration` AS r;";
//$students_result = mysqli_query($CONNECTION, $query);
?>

<div id="content" class="clearfix">
    <div class="contentwrapper">
        <?php
        $BreadCumb = "Student Print";
        BreadCumb($BreadCumb);
        if(isset($_POST['UniqueId']) && $_POST['UniqueId']>0){
            $UniqueId=$_POST['UniqueId']
        ?>
            
        <form class="form-horizontal" action="StudentPrint.php" name="StudentPrint" id="AdmissionReport" method="Post" target="_blank">
        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Student Detail</span>
                        </h4>
                        <a href="#" class="minimize" style="display: inline; overflow: hidden; height: 1px; padding: 0px; margin: 0px; width: 1px; opacity: 0;">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">

                            <div class="form-row row-fluid">
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="StudentName" type="checkbox" name="StudentName" value="StudentName" />Student Name</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Gender" type="checkbox" name="Gender" value="Gender" />Gender</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="DOB" type="checkbox" name="DOB" value="DOB" />DOB</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="StudentClass" type="checkbox" name="StudentClass" value="StudentClass" />Student Class</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="FatherName" type="checkbox" name="FatherName" value="FatherName" />Father Name</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="MotherName" type="checkbox" name="MotherName" value="MotherName" />Mother Name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Caste" type="checkbox" name="Caste" value="Caste" />Caste</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Category" type="checkbox" name="Category" value="Category" />Category</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="SSSMID" type="checkbox" name="SSSMID" value="SSSMID" />SSSMID</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Family_SSSMID" type="checkbox" name="Family_SSSMID" value="Family_SSSMID" />Family SSSMID</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Aadhar_No" type="checkbox" name="Aadhar_No" value="Aadhar_No" />Aadhar Number</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Bank_Account_Number" type="checkbox" name="Bank_Account_Number" value="Bank_Account_Number" />Bank Account Number</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="IFSC_Code" type="checkbox" name="IFSC_Code" value="IFSC_Code" />IFSC Code</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="BloodGroup" type="checkbox" name="BloodGroup" value="BloodGroup" />BloodGroup</label>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Contact Detail</span>
                        </h4>
                        <a href="#" class="minimize" style="display: inline; overflow: hidden; height: 1px; padding: 0px; margin: 0px; width: 1px; opacity: 0;">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="PresentAddress" type="checkbox" name="PresentAddress" value="PresentAddress" />Present Address</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="PermanentAddress" type="checkbox" name="PermanentAddress" value="PermanentAddress" />Permanent Address</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Mobile" type="checkbox" name="Mobile" value="Mobile" />Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherMobile" type="checkbox" name="FatherMobile" value="FatherMobile" />Father Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherMobile" type="checkbox" name="MotherMobile" value="MotherMobile" />Mother Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Landline" type="checkbox" name="Landline" value="Landline" />Landline</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="AlternateMobile" type="checkbox" name="AlternateMobile" value="AlternateMobile" />Alternate Mobile</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Qualification</span>
                        </h4>
                        <a href="#" class="minimize" style="display: inline; overflow: hidden; height: 1px; padding: 0px; margin: 0px; width: 1px; opacity: 0;">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherDateOfBirth" type="checkbox" name="FatherDateOfBirth" value="FatherDateOfBirth" />Father DateOfBirth</label>
                                </div>
                            </div>
                            
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherEmail" type="checkbox" name="FatherEmail" value="FatherEmail" />Father Email</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherQualification" type="checkbox" name="FatherQualification" value="FatherQualification" />Father Qualification</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherOccupation" type="checkbox" name="FatherOccupation" value="FatherOccupation" />Father Occupation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherDesignation" type="checkbox" name="FatherDesignation" value="FatherDesignation" />Father Designation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherOrganization" type="checkbox" name="FatherOrganization" value="FatherOrganization" />FatherOrganization</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherDateOfBirth" type="checkbox" name="MotherDateOfBirth" value="MotherDateOfBirth" />Mother DateOfBirth</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherEmail" type="checkbox" name="MotherEmail" value="MotherEmail" />Mother Email</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherQualification" type="checkbox" name="MotherQualification" value="MotherQualification" />Mother Qualification</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherOccupation" type="checkbox" name="MotherOccupation" value="MotherOccupation" />Mother Occupation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherDesignation" type="checkbox" name="MotherDesignation" value="MotherDesignation" />Mother Designation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherOrganization" type="checkbox" name="MotherOrganization" value="MotherOrganization" />Mother Organization</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Parents Detail</span>
                        </h4>
                        <a href="#" class="minimize" style="display: inline; overflow: hidden; height: 1px; padding: 0px; margin: 0px; width: 1px; opacity: 0;">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="BoardUniversity" type="checkbox" name="BoardUniversity" value="BoardUniversity" />BoardUniversity</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Class" type="checkbox" name="Class" value="Class" />Class</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Year" type="checkbox" name="Year" value="Year" />Year</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Marks" type="checkbox" name="Marks" value="Marks" />Marks</label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Termination</span>
                        </h4>
                        <a href="#" class="minimize" style="display: inline; overflow: hidden; height: 1px; padding: 0px; margin: 0px; width: 1px; opacity: 0;">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="DateOfTermination" type="checkbox" name="DateOfTermination" value="DateOfTermination" />Date Of Termination</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="TerminationReason" type="checkbox" name="TerminationReason" value="TerminationReason" />Termination Reason</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="StudentFeeId" type="checkbox" name="StudentFeeId" value="StudentFeeId" />Student FeeId</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Remarks" type="checkbox" name="Remarks" value="Remarks" />Remarks</label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row row-fluid">
            <div class="span8">
                <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
                <input type="hidden" name="Action" value="StudentPrint" readonly>
                <input type="hidden" name="UniqueId" value="<?php echo $UniqueId; ?>" readonly>
                <button type="submit" tabindex="1904" class="btn btn-info">Go for Print</button>
            </div>
        </div>
    </form>
        <?php }else{
            echo "Opps ! Something went wrong !!";
        } ?>
    </div>
</div>
<?php
include("Template/Footer.php");
?>