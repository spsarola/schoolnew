<?php
$PageName = "Student Listing";
$TooltipRequired = 1;
$SearchRequired = 1;
$FormRequired = 1;
$TableRequired = 1;
include("Include.php");
IsLoggedIn();



//SELECT THE DATA OF STUDENT
$query = "SELECT RegistrationId,Session,Status,StudentName,FatherName,FatherMobile,MotherName,DOB,Gender,Category,
        (SELECT CONCAT(c.ClassName,'-',s.SectionName)FROM class AS c,section AS s WHERE c.ClassId=s.ClassId AND c.ClassStatus='Active' AND s.SectionStatus='Active' AND c.Session='$CURRENTSESSION' AND s.SectionId =r.SectionId) AS class,
        (select p.Path FROM photos AS p WHERE p.Document='85' AND p.UniqueId=r.RegistrationId AND p.Detail='StudentDocuments' LIMIT 1 ) AS photo
        FROM `registration` AS r;";
//$students_result = mysqli_query($CONNECTION, $query);
?>