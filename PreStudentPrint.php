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
            
        <form class="form-horizontal" action="StudentPrint" name="StudentPrint" id="printDetailForm" method="Post" target="_blank">
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
                                        <label><input tabindex="2" class="styled" id="StudentName" type="checkbox" name="std_check[]" value="StudentName" checked="checked" disabled="disabled" />Student Name</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Gender" type="checkbox" name="std_check[]" value="Gender" checked="checked" disabled="disabled" />Gender</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="DOB" type="checkbox" name="std_check[]" value="DOB" checked="checked" disabled="disabled" />DOB</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="StudentClass" type="checkbox" name="std_check[]" value="StudentClass" checked="checked" disabled="disabled" />Student Class</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="FatherName" type="checkbox" name="std_check[]" value="FatherName" checked="checked" disabled="disabled" />Father Name</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="MotherName" type="checkbox" name="std_check[]" value="MotherName" checked="checked" disabled="disabled" />Mother Name</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Caste" type="checkbox" name="std_check[]" value="Caste" />Caste</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Category" type="checkbox" name="std_check[]" value="Category" />Category</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="SSSMID" type="checkbox" name="std_check[]" value="SSSMID" />SSSMID</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Family_SSSMID" type="checkbox" name="std_check[]" value="Family_SSSMID" />Family SSSMID</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Aadhar_No" type="checkbox" name="std_check[]" value="Aadhar_No" />Aadhar Number</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="Bank_Account_Number" type="checkbox" name="std_check[]" value="Bank_Account_Number" />Bank Account Number</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="IFSC_Code" type="checkbox" name="std_check[]" value="IFSC_Code" />IFSC Code</label>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="row-fluid">
                                        <label><input tabindex="2" class="styled" id="BloodGroup" type="checkbox" name="std_check[]" value="BloodGroup" checked="checked" disabled="disabled" />BloodGroup</label>
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
                                    <label><input tabindex="2" class="styled" id="PresentAddress" type="checkbox" name="std_check[]" value="PresentAddress" />Present Address</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="PermanentAddress" type="checkbox" name="std_check[]" value="PermanentAddress" />Permanent Address</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Mobile" type="checkbox" name="std_check[]" value="Mobile" checked="checked" disabled="disabled" />Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherMobile" type="checkbox" name="std_check[]" value="FatherMobile" checked="checked" disabled="disabled" />Father Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherMobile" type="checkbox" name="std_check[]" value="MotherMobile" checked="checked" disabled="disabled" />Mother Mobile</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Landline" type="checkbox" name="std_check[]" value="Landline" />Landline</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="AlternateMobile" type="checkbox" name="std_check[]" value="AlternateMobile" />Alternate Mobile</label>
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
                                    <label><input tabindex="2" class="styled" id="FatherDateOfBirth" type="checkbox" name="std_check[]" value="FatherDateOfBirth" />Father DateOfBirth</label>
                                </div>
                            </div>
                            
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherEmail" type="checkbox" name="std_check[]" value="FatherEmail" />Father Email</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherQualification" type="checkbox" name="std_check[]" value="FatherQualification" />Father Qualification</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherOccupation" type="checkbox" name="std_check[]" value="FatherOccupation" />Father Occupation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherDesignation" type="checkbox" name="std_check[]" value="FatherDesignation" />Father Designation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="FatherOrganization" type="checkbox" name="std_check[]" value="FatherOrganization" />FatherOrganization</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row row-fluid">
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherDateOfBirth" type="checkbox" name="std_check[]" value="MotherDateOfBirth" />Mother DateOfBirth</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherEmail" type="checkbox" name="std_check[]" value="MotherEmail" />Mother Email</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherQualification" type="checkbox" name="std_check[]" value="MotherQualification" />Mother Qualification</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherOccupation" type="checkbox" name="std_check[]" value="MotherOccupation" />Mother Occupation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherDesignation" type="checkbox" name="std_check[]" value="MotherDesignation" />Mother Designation</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="MotherOrganization" type="checkbox" name="std_check[]" value="MotherOrganization" />Mother Organization</label>
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
                                    <label><input tabindex="2" class="styled" id="qualification" type="checkbox" name="std_check[]" value="qualification" />Qualification Table</label>
                                </div>
                            </div>
<!--                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="BoardUniversity" type="checkbox" name="std_check[]" value="BoardUniversity" />BoardUniversity</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Class" type="checkbox" name="std_check[]" value="Class" />Class</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Year" type="checkbox" name="std_check[]" value="Year" />Year</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Marks" type="checkbox" name="std_check[]" value="Marks" />Marks</label>
                                </div>
                            </div>-->
                            
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
                                    <label><input tabindex="2" class="styled" id="DateOfTermination" type="checkbox" name="std_check[]" value="DateOfTermination" />Date Of Termination</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="TerminationReason" type="checkbox" name="std_check[]" value="TerminationReason" />Termination Reason</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="StudentFeeId" type="checkbox" name="std_check[]" value="StudentFeeId" />Student FeeId</label>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="row-fluid">
                                    <label><input tabindex="2" class="styled" id="Remarks" type="checkbox" name="std_check[]" value="Remarks" />Remarks</label>
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
<script>
    $("#printDetailForm").submit(function() {
        $("input[type='checkbox']").removeAttr("disabled");
        //$("input[type='checkbox']").prop("checked","checked");
    });
</script>
<?php
include("Template/Footer.php");
?>