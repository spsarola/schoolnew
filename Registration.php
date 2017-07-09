<?php
$PageName = "Registration";
$TooltipRequired = 1;
$SearchRequired = 1;
$FormRequired = 1;
$TableRequired = 1;
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
        <?php
        $BreadCumb = "Student Registration";
        BreadCumb($BreadCumb);
        ?>

        <?php DisplayNotification(); ?>

        <?php
        $GetRegistrationId = isset($_GET['RegistrationId']) ? $_GET['RegistrationId'] : '';
        $query10 = "select StudentName,FatherName,Mobile from registration where 
					registration.RegistrationId='$GetRegistrationId' and
					registration.Status!='Deleted' and
					registration.Session='$CURRENTSESSION' ";
        $check10 = mysqli_query($CONNECTION, $query10);
        $count10 = mysqli_num_rows($check10);
        if ($count10 == 1) {
            $row10 = mysqli_fetch_array($check10);
            $TabName = $row10['StudentName'];
            $TabFatherName = $row10['FatherName'];
            $TabMobile = $row10['Mobile'];
            ?>
            <div class="row-fluid">
                <div class="span12">
                    <div class="box chart gradient">
                        <div class="title">
                            <h4>
                                <span><?php echo "$TabName S/o $TabFatherName Mobile - $TabMobile"; ?></span>
                            </h4>
                            <a href="#" class="minimize">Minimize</a>
                        </div>
                        <div class="content" style="padding-bottom:0;">
                            <div style="margin-bottom: 20px;">
                                <ul id="myTabs" class="nav nav-tabs pattern">
                                    <li class="active"><a href="#StudentProfile" data-toggle="tab">Student Detail</a></li>
                                    <li><a href="#StudentContact" data-toggle="tab">Contact Detail</a></li>
                                    <li><a href="#ParentsContact" data-toggle="tab">Parents Detail</a></li>
                                    <li><a href="#Qualification" data-toggle="tab">Qualification</a></li>
                                    <li><a href="#SiblingInformation" data-toggle="tab">Sibling Information</a></li>
                                    <li><a href="#Photo" data-toggle="tab">Photo</a></li>
                                    <li><a href="#Termination" data-toggle="tab">Termination</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="StudentProfile">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="StudentContact">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="ParentsContact">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="Qualification">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="SiblingInformation">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="Photo">
                                        Loading...
                                    </div>
                                    <div class="tab-pane fade" id="Termination">
                                        Loading...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <?php
        $query2 = "select ClassName,SectionName,SectionId from class,section where 
						class.ClassId=section.ClassId and class.ClassStatus='Active' and
						section.SectionStatus='Active' and class.Session='$CURRENTSESSION' order by ClassName";
        $check2 = mysqli_query($CONNECTION, $query2);
        $ListAllClass = "";
        while ($row2 = mysqli_fetch_array($check2)) {
            $SelectClassName = $row2['ClassName'];
            $SelectSectionName = $row2['SectionName'];
            $SelectSectionId = $row2['SectionId'];
            $SectionIdArray[] = "$SelectSectionId";
            $SectionNameArray[] = "$SelectClassName $SelectSectionName";
            $Selected = "";
            $ListAllClass.="<option value=\"$SelectSectionId\" >$SelectClassName $SelectSectionName</option>";
        }

        $query3 = "select TerminationReason,RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,Status,DOR from registration,section,class where
						registration.SectionId=section.SectionId and
						class.ClassId=section.ClassId and 
						registration.Session='$CURRENTSESSION' and 
						registration.Session=class.Session and
						registration.Status!='Deleted' 
						order by RegistrationId ";

        $DATA = array();
        $QA = array();
        $result3 = mysqli_query($CONNECTION, $query3);
        $count3 = mysqli_num_rows($result3);
        $Print3 = "";
        while ($row3 = mysqli_fetch_array($result3)) {
            $ListStudentName = $row3['StudentName'];
            $ListFatherName = $row3['FatherName'];
            $ListClassName = $row3['ClassName'];
            $ListRegistrationId = $row3['RegistrationId'];
            $ListTerminationReason = $row3['TerminationReason'];
            if ($ListTerminationReason != "") {
                $SearchTerminationIndex = array_search($ListTerminationReason, $MasterEntryIdArray);
                $TerminationReasonValue = $MasterEntryValueArray[$SearchTerminationIndex];
            } else
                $TerminationReasonValue = "";

            $ListMobile = $row3['Mobile'];
            $ListSectionName = $row3['SectionName'];
            $ListStatus = $row3['Status'];
            $ListDOR = GetdateFormat($row3['DOR']);
            if ($ListStatus == "NotAdmitted")
                $ListStatus = "<span class=\"badge badge-important\">Not Admitted<span>";
            elseif ($ListStatus == "Terminated")
                $ListStatus = "<span class=\"date badge badge-important\">$TerminationReasonValue</span>";
            else
                $ListStatus = "<span class=\"badge badge-success\">Studying<span>";
            $ListStudentName = "<a href=Registration/$ListRegistrationId>$ListStudentName</a> $ListStatus";
            $ListClassName.=" $ListSectionName";
            $Delete = "<a href='javascript:void(0);' onclick=\"showdetail('$ListRegistrationId','DeleteStudentRegistration','DeleteStudentRegistration')\"><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></a>";
            $QA[] = array($ListStudentName, $ListFatherName, $ListMobile, $ListClassName, $ListDOR, $Delete);
            $Print3.="<tr class=\"odd gradeX\">
								<td>$ListStudentName</td>
								<td>$ListFatherName</td>
								<td>$ListMobile</td>
								<td>$ListClassName</td>
								<td>$ListDOR</td>
							</tr>";
        }
        $DATA['aaData'] = $QA;
        $fp = fopen('plugins/Data/data1.txt', 'w');
        fwrite($fp, json_encode($DATA));
        fclose($fp);
        ?>	

        <div class="row-fluid">
            <div class="span12">
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Student Import/Export Data</span>
                        </h4>
                        <a href="#" class="minimize">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <div class="row-fluid">
                            <div class="span4 pull-right">
                                <form action="Action" name="ExportStudentData" id="ExportStudentData" method="Post">
                                    <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
                                    <input type="hidden" name="Action" value="ExportStudentData" readonly>
                                    <button type="submit" tabindex="7" class="btn btn-info">Export CSV</button>
                                </form>
                            </div>
                            <div class="span8">
                                <form action="Action" name="ImportStudentData" id="ImportStudentData" method="Post" enctype="multipart/form-data">
                                    <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
                                    <input type="hidden" name="Action" value="ImportStudentData" readonly>
                                    <input type="file" name="csv_file" id="file" size="20" style="opacity: 0;">
                                    <button type="submit" tabindex="7" class="btn btn-info">Import CSV</button>
                                </form>
                            </div>

                        </div>    
                    </div>    
                </div>    
            </div>    
        </div>   
        <div class="row-fluid">
            <div class="span4">
                <form class="form-horizontal" action="ActionDelete" name="DeleteStudentRegistration" id="DeleteStudentRegistration" method="Post">
                    <div id="DeleteStudentRegistration"></div>
                </form>
                <div class="box chart gradient">
                    <div class="title">
                        <h4>
                            <span>Student Registration</span>
                        </h4>
                        <a href="#" class="minimize">Minimize</a>
                    </div>
                    <div class="content" style="padding:5px;">
                        <form class="form-horizontal" action="Action" name="StudentRegistration" id="StudentRegistration" method="Post">
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4">For Session</label>
                                        <label class="form-label span4 label-field"><?php echo $CURRENTSESSION; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="StudentName">Student Name</label>
                                        <input class="span8" tabindex="1" id="StudentName" type="text" name="StudentName" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="FatherName">Father's Name</label>
                                        <input class="span8" tabindex="2" id="FatherName" type="text" name="FatherName" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="MotherName">Mother's Name</label>
                                        <input class="span8" tabindex="3" id="MotherName" type="text" name="MotherName" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="SSSMID">SSSMID</label>
                                        <input tabindex="4" class="span8" id="SSSMID" type="text" name="SSSMID" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="Family_SSSMID">Family SSSMID</label>
                                        <input tabindex="5" class="span8" id="Family_SSSMID" type="text" name="Family_SSSMID" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="Aadhar_No">Aadhar Number</label>
                                        <input tabindex="6" class="span8" id="Aadhar_No" type="text" name="Aadhar_No" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="Bank_Account_Number">Bank A/c No</label>
                                        <input tabindex="7" class="span8" id="Bank_Account_Number" type="text" name="Bank_Account_Number" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="IFSC_Code">IFSC_Code</label>
                                        <input tabindex="8" class="span8" id="IFSC_Code" type="text" name="IFSC_Code" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="Mobile">Mobile Number</label>
                                        <input class="span8" tabindex="9" id="Mobile" type="text" name="Mobile" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="Class">Class</label>
                                        <div class="controls sel span8">   
                                            <select tabindex="10" name="Class" id="Class" class="nostyle" style="width:100%;" >
                                                <option></option>
                                                <?php echo $ListAllClass; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="CallResponse">Gender </label> 
                                        <div class="span8 controls sel">   
                                            <?php
                                            GetCategoryValue('Gender', 'Gender', '', '', '', '', '', 1, '');
                                            ?>
                                        </div> 
                                    </div>
                                </div> 
                            </div>
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="DOR">Date of Registration</label>
                                        <input class="span8" readonly tabindex="11" id="DOR" type="text" name="DOR" value="" />
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
                            <input type="hidden" name="Action" value="StudentRegistration" readonly>
                            <?php
                            $ButtonContent = "Save";
                            ActionButton($ButtonContent, 7);
                            ?>
                        </form>
                    </div>
                </div>
            </div>					
            <div class="span8">
                <div class="box gradient">
                    <div class="title">
                        <h4>
                            <span>Registration List</span>
                            <?php if ($count3 > 0) { ?>
                                <div class="PrintClass">
                                    <form method=post action=Print target=_blank>
                                        <input type="hidden" name="Action" value="Print" readonly>
                                        <input type="hidden" name="PrintCategory" value="PrintCategory" readonly>
                                        <input type="hidden" name="SessionName" value="PrintRegistrationList" readonly>
                                        <input type="hidden" name="HeadingName" value="PrintRegistrationHeading" readonly>
                                        <button class="icomoon-icon-printer-2 tip" title="Print Registration List"></button>
                                    </form>
                                </div>
                            <?php } ?>
                        </h4>
                        <a href="#" class="minimize">Minimize</a>
                    </div>
                    <div class="content clearfix noPad">
                        <?php
                        $Print1 = "<table id=\"RegistrationTable\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"responsive dynamicTable display table table-bordered\" width=\"100%\">
                                                                <thead>
                                                                        <tr>
                                                                                <th>Student Name</th>
                                                                                <th>Father Name</th>
                                                                                <th>Mobile</th>
                                                                                <th>Class Registered</th>
                                                                                <th>Date of Registration</th>";
                        echo $Print1;
                        echo "<th><span class=\"icomoon-icon-cancel tip\" title=\"Delete\"></span></th>";
                        $Print2 = "</tr>
                                                                </thead>
                                                                <tbody>";
                        echo $Print2;
                        $Print4 = "</tbody>
                                                        </table>";
                        echo $Print4;
                        $PrintRegistrationList = "$Print1 $Print2 $Print3 $Print4";
                        $_SESSION['PrintRegistrationList'] = $PrintRegistrationList;
                        $PrintHeading = "Showing List of Registration";
                        $_SESSION['PrintRegistrationHeading'] = $PrintHeading;
                        $_SESSION['PrintCategory'] = "Registration";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#RegistrationTable').dataTable({
            "sPaginationType": "two_button",
            "bJQueryUI": false,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bProcessing": true,
            "bDeferRender": true,
            "sAjaxSource": "plugins/Data/data1.txt",
            "fnInitComplete": function (oSettings, json) {
                $('.dataTables_filter>label>input').attr('id', 'search');
            }
        });

        $("#Class").select2();
        $('#Class').select2({placeholder: "Select"});
        $("#Gender").select2();
        $('#Gender').select2({placeholder: "Select"});
        if ($('#DOR').length) {
            $('#DOR').datetimepicker({yearRange: "-180:+0", dateFormat: 'dd-mm-yy', changeMonth: true, changeYear: true});
        }
        $("input, textarea, select").not('.nostyle').uniform();
        $("#StudentRegistration").validate({
            ignore: 'input[type="hidden"]',
            rules: {
                StudentName: {
                    required: true,
                },
                FatherName: {
                    required: true,
                },
                MotherName: {
                    required: true,
                },
                SSSMID: {
                    required: true,
                },
                Family_SSSMID: {
                    required: true,
                },
                Aadhar_No: {
                    required: true,
                },
                Bank_Account_Number: {
                    required: true,
                },
                IFSC_Code: {
                    required: true,
                },
                Class: {
                    required: true,
                },
                DOR: {
                    required: true,
                },
                Mobile: {
                    required: true,
                    remote: "RemoteValidation?Action=MobileValidation&Id=Mobile"
                }
            },
            messages: {
                StudentName: {
                    required: "Please enter this!!",
                },
                FatherName: {
                    required: "Please enter this!!",
                },
                MotherName: {
                    required: "Please enter this!!",
                },
                SSSMID: {
                    required: "Please enter this!!",
                },
                Family_SSSMID: {
                    required: "Please enter this!!",
                },
                Aadhar_No: {
                    required: "Please enter this!!",
                },
                Bank_Account_Number: {
                    required: "Please enter this!!",
                },
                IFSC_Code: {
                    required: "Please enter this!!",
                },
                Class: {
                    required: "Please select this!!",
                },
                DOR: {
                    required: "Please enter this!!",
                },
                Mobile: {
                    required: "Please enter this!!",
                    remote: jQuery.format("<?php echo $MOBILENUMBERDIGIT; ?> Digit Mobile number!!")
                }
            }
        });
    });
    $("#DeleteStudentRegistration").validate({
        rules: {
            Password: {
                required: true,
            }
        },
        messages: {
            Password: {
                required: "Please enter this!!",
            }
        }
    });

    $(document).ready(function () {
        $(function () {
            var baseURL = 'StudentAjaxTab';
            $('#StudentProfile').load(baseURL + '?Action=StudentProfile&Id=<?php echo $GetRegistrationId; ?>', function () {
                $('#myTabs').tab();
            });
            $('#myTabs').bind('show', function (e) {
                var pattern = /#.+/gi
                var contentID = e.target.toString().match(pattern)[0];
                $(contentID).load(baseURL + contentID.replace('#', '?Id=<?php echo $GetRegistrationId; ?>&Action='), function () {
                    $('#myTabs').tab();
                });
            });
        });

    });
</script>
<?php
include("Template/Footer.php");
?>