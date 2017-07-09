<?php

$PageName = "Student Detail Print";
$TooltipRequired = 1;
$SearchRequired = 1;
$FormRequired = 1;
$TableRequired = 1;
include("Include.php");
IsLoggedIn();

//include("Template/HTML.php");
?>
<?php

require('fpdf.php');

class PDF extends FPDF {

// Page header
    function Header() {
        // Logo
        $this->Image('images/school/logo.png', 15, 10, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(10);
        // Title
        $this->Cell(100, 10, 'Nalanda Vidyapith', 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }

// Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function FancyTable($header, $data) {

        $this->SetFont('', 'B');
        // Header
        $w = array(65, 65, 60);
        for ($i = 0; $i < 3; $i++) {
            if(isset($header[$i])){
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
            }else{
                $this->Cell($w[$i], 7, '', 1, 0, 'C');
            }
        }
        $this->Ln();
        $this->SetFont('', '', 11);
        // Data
        for ($i = 0; $i < 3; $i++) {
            if(isset($data[$i])){
                $this->Cell($w[$i], 6, $data[$i], '1', 0, 'L');
            }else{
                $this->Cell($w[$i], 6, '', '1', 0, 'L');
            }
        }
        $this->Ln();
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    function QualificationTable($qualification){
        $this->SetFont('', 'B');
        $this->Cell(80, 7, 'Board University', 1, 0, 'C');
        $this->Cell(30, 7, 'Class', 1, 0, 'C');
        $this->Cell(20, 7, 'Year', 1, 0, 'C');
        $this->Cell(20, 7, 'Marks', 1, 0, 'C');
        $this->Ln();
        $this->SetFont('', '', 11);
        foreach($qualification as $quali){
            $this->Cell(80, 6, $quali['BoardUniversity'], '1', 0, 'L');
            $this->Cell(30, 6, $quali['Class'], '1', 0, 'L');
            $this->Cell(20, 6, $quali['Year'], '1', 0, 'L');
            $this->Cell(20, 6, $quali['Marks'], '1', 0, 'L');
            $this->Ln();
        }
        
    }

}
?>
<?php

if (isset($_POST['UniqueId']) && isset($_POST['Action']) && $_POST['Action'] == 'StudentPrint' && $_POST['UniqueId'] != '' && $_POST['UniqueId'] > 0) {
    $std_check=(isset($_POST['std_check']) && is_array($_POST['std_check']) && count($_POST['std_check']))? $_POST['std_check']:array(); 
    
    
    
    $student_id = $_POST['UniqueId'];
//SELECT THE DATA OF STUDENT
    $query = "SELECT RegistrationId,Session,Status,
    StudentName,FatherName,MotherName,(SELECT CONCAT(c.ClassName,'-',s.SectionName) FROM class AS c,section AS s WHERE c.ClassId=s.ClassId AND c.ClassStatus='Active' AND s.SectionStatus='Active' AND c.Session='$CURRENTSESSION' AND s.SectionId =r.SectionId) AS StudentClass,DOB,Gender,BloodGroup,Mobile,FatherMobile,MotherMobile,
    Category,Caste,PresentAddress,PermanentAddress,Landline,AlternateMobile,SSSMID,Family_SSSMID,Aadhar_No,
    FatherDateOfBirth,FatherEmail,FatherQualification,FatherOccupation,FatherDesignation,FatherOrganization,
    MotherDateOfBirth,MotherEmail,MotherQualification,MotherOccupation,MotherDesignation,MotherOrganization,
    Bank_Account_Number,IFSC_Code,DateOfTermination,TerminationReason,TerminationRemarks,
    (SELECT StudentFeeId FROM admission,studentfee where r.RegistrationId=admission.RegistrationId and admission.AdmissionId=studentfee.AdmissionId ) AS StudentFeeId,
    (select p.Path FROM photos AS p WHERE p.Document='85' AND p.UniqueId=r.RegistrationId AND p.Detail='StudentDocuments' LIMIT 1 ) AS photo
    FROM `registration` AS r WHERE RegistrationId='$student_id' ;";

    $allListingArr = array(
        'Student Name' => 'StudentName', 'Father Name' => 'FatherName', 'Mother Name' => 'MotherName',
        'class' => 'StudentClass', 'DOB' => 'DOB', 'Gender' => 'Gender',
        'Blood Group' => 'BloodGroup', 'Mobile' => 'Mobile', 'Father Mobile' => 'FatherMobile',
        'Mother Mobile' => 'MotherMobile', 'Category' => 'Category', 'Caste' => 'Caste',
        'Present Address' => 'PresentAddress', 'Permanent Address' => 'PermanentAddress', 'Land Line' => 'Landline',
        'Alternate Mobile' => 'AlternateMobile', 'SSSMID' => 'SSSMID', 'Family SSSMID' => 'Family_SSSMID',
        'Aadhar No' => 'Aadhar_No', 'Father DOB' => 'FatherDateOfBirth', 'Father Email' => 'FatherEmail',
        'Father Qualification' => 'FatherQualification', 'Father Occupation' => 'FatherOccupation', 'Father Designation' => 'FatherDesignation',
        'FatherOrganization' => 'Father Organization', 'Mother DOB' => 'MotherDateOfBirth', 'Mother Email' => 'MotherEmail',
        'Mother Qualification' => 'MotherQualification', 'Mother Occupation' => 'MotherOccupation', 'Mother Designation' => 'MotherDesignation',
        'Mother Organization' => 'MotherOrganization', 'Bank Account Number' => 'Bank_Account_Number', 'IFSC Code' => 'IFSC_Code',
        'Termination Date' => 'DateOfTermination', 'Termination Reason' => 'TerminationReason', 'Student FeeId' => 'StudentFeeId',
        'Termination Remarks' => 'TerminationRemarks'
    );
    
//        'Bank_Account_Number', 'IFSC_Code', );
    $students_result = mysqli_query($CONNECTION, $query);

    if (mysqli_num_rows($students_result)) {
        $student = mysqli_fetch_assoc($students_result);
        $listing_arr=array();
        foreach($allListingArr as $key=>$detail){
            if(in_array($detail, $std_check)){
                if($detail=='DOB' || $detail=='MotherDateOfBirth' || $detail=='FatherDateOfBirth' ){
                    if($student[$detail]!=''){
                        $student[$detail]=date('d/m/Y',$student[$detail]);
                    }
                }
            $getCategory=array('Gendre','BloodGroup','Caste','Category');
            if(in_array($detail, $getCategory)){  
                    if($student[$detail]!=''){
                        $student[$detail]=GetCategoryValueOfId($student[$detail],$detail);
                    }
                }
                $listing_arr[$key]=$student[$detail];
            }
            
            
        }
    }
} else {
    
}
?>

<?php

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Times', '', 12);
$pdf->Image($PHOTOPATH . "/thumbnail-" . $student['photo'], 170, 10, 30, 30);
$pdf->Line(5, 45, 205, 45);
$pdf->Ln(20);
$chunk3 = array_chunk($listing_arr, 3, true);

foreach ($chunk3 as $key => $data) {
    $pdf->FancyTable(array_keys($data), array_values($data));
    $pdf->Ln();
}
if(in_array('qualification', $std_check)){
    $qualification_query="SELECT BoardUniversity,Class,Year,Marks FROM qualification WHERE Type='Student' AND UniqueId='$student_id' ;";
    
    $qualification_result=mysqli_query($CONNECTION,$qualification_query);
    $qualification=array();
    while($Qua = mysqli_fetch_assoc($qualification_result)){
        $qualification[]=$Qua;
    }
    if(count($qualification)){
        $pdf->Ln(10);
        $pdf->QualificationTable($qualification);
    }
}


$pdf->Output();
?>
    