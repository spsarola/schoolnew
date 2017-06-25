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
$query="SELECT RegistrationId,Session,Status,StudentName,FatherName,FatherMobile,MotherName,DOB,Gender,Category,
        (SELECT CONCAT(c.ClassName,'-',s.SectionName)FROM class AS c,section AS s WHERE c.ClassId=s.ClassId AND c.ClassStatus='Active' AND s.SectionStatus='Active' AND c.Session='$CURRENTSESSION' AND s.SectionId =r.SectionId) AS class,
        (select p.Path FROM photos AS p WHERE p.Document='85' AND p.UniqueId=r.RegistrationId AND p.Detail='StudentDocuments' LIMIT 1 ) AS photo
        FROM `registration` AS r;";
$students_result=mysqli_query($CONNECTION, $query);

?>

<div id="content" class="clearfix">
    <div class="contentwrapper">
        <?php $BreadCumb = "Student Listing";
        BreadCumb($BreadCumb);
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="box gradient">
                    <div class="title">
                        <h4><span>Student List</span></h4>
                        <a href="javascript:void(0);" class="minimize" style="display: none;">Minimize</a>
                    </div>
                    <div class="content clearfix noPad">
                        <table id="StudentListTable" cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Photo</th>
                                    <th>Enr No</th>
                                    <th>SectionId</th>
                                    <th>Student</th>
                                    
                                    <th>Father</th>
                                    <th>Mother</th>
                                    <th>Father Mobile</th>
                                    <th>Dob</th>
                                    <th>Gender</th>
                                    <th>Catg</th>
                                    <th>Print</th>
                                    
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if(mysqli_num_rows($students_result)>0){
                                    $i=0;
                                    while ($student = mysqli_fetch_assoc($students_result)){
                                        extract($student);
                                        
                                        if(isset($photo) && $photo!=''){
                                            $photo="<img src='".$PHOTOPATH."/thumbnail-".$photo."' alt='student-image' style='height:100px;width:100px;' />";
                                        }else{
                                            $photo='No image';
                                        }
                                        $DOB=(isset($DOB) && $DOB!='')? date('d-m-Y', $DOB):"";
                                ?>
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td><?php echo $photo ?></td>
                                            <td><?php echo isset($RegistrationId)? $RegistrationId:'' ; ?></td>
                                            <td><?php echo $class ?></td>
                                            <td><?php echo $StudentName ?></td>

                                            <td><?php echo $FatherName ?></td>
                                            <td><?php echo $MotherName ?></td>
                                            <td><?php echo $FatherMobile ?></td>
                                            <td><?php echo $DOB ?></td>
                                            <td><?php echo $Gender ?></td>
                                            <td><?php echo $Category ?></td>
                                            <td>
                                                <form class="form-horizontal" action="PreStudentPrint.php" name="StudentPrint" id="StudentPrint" target="_blank" method="Post">
                                                    <input type="hidden" name="RandomNumber" value="<?php echo $TOKEN; ?>" readonly>
                                                    <input type="hidden" name="Action" value="PreStudentPrint" readonly>
                                                    <input type="hidden" name="UniqueId" value="<?php echo $RegistrationId; ?>" readonly>
                                                    <button type="submit"  class="btn btn-info">Print</button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php 
                                    }
                                }else{ ?>
                                <tr>
                                    <td colspan='15'><center>No Data Found !</center></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php DisplayNotification(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#StudentListTable').dataTable({
            "sPaginationType": "two_button",
            "bJQueryUI": false,
            "bAutoWidth": false,
            "bLengthChange": false,
            "bProcessing": true,
            "bDeferRender": true,
//            "sAjaxSource": "plugins/Data/data1.txt",
            "fnInitComplete": function (oSettings, json) {
                $('.dataTables_filter>label>input').attr('id', 'search');
            }
        });
    });
</script>
<?php
include("Template/Footer.php");
?>

