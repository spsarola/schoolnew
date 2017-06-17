<?php
include("Include.php");
$RegistrationId=103;

$query="select studentfee.SectionId from studentfee,admission,registration where
	admission.RegistrationId=registration.RegistrationId and
	admission.AdmissionId=studentfee.AdmissionId and
	studentfee.Session='$CURRENTSESSION' and
	registration.RegistrationId='$RegistrationId' ";
$check=mysqli_query($CONNECTION,$query);
$row=mysqli_fetch_array($check);
$SectionId=$row['SectionId'];

$query2="select ExamId,examdetail.SubjectId,SubjectName,ExamActivityName,MaximumMarks,Marks from examdetail,subject where
	ExamDetailStatus='Active' and
	examdetail.SubjectId=subject.SubjectId and
	subject.Session='$CURRENTSESSION' and
	SubjectStatus='Active' order by SubjectId";
$check2=mysqli_query($CONNECTION,$query2);
while($row2=mysqli_fetch_array($check2))
{
	unset($FinalMarks);
	$AllExamIdArray[]=$row2['ExamId'];
	$AllSubjectIdArray[]=$row2['SubjectId'];
	$AllSubjectNameArray[]=$row2['SubjectName'];
	$AllExamActivityNameArray[]=$row2['ExamActivityName'];
	$AllMaximumMarksArray[]=$row2['MaximumMarks'];
	$Marks=explode(",",$row2['Marks']);
	foreach($Marks as $MarksValue)
	{
		$MarksValue=explode("-",$MarksValue);
		if($MarksValue[0]==$RegistrationId)
		$FinalMarks=$MarksValue[1];
	}
	$AllMarksArray[]=$FinalMarks;
}

$query1="select ExamId,ExamName,Weightage from exam where SectionId='$SectionId' and Session='$CURRENTSESSION' and ExamStatus='Active' ";
$check1=mysqli_query($CONNECTION,$query1);
while($row1=mysqli_fetch_array($check1))
{
	$ExamId=$row1['ExamId'];
	$ExamName=$row1['ExamName'];
	$Weightage=round($row1['Weightage'],2);
	
	$i=0;
	foreach($AllExamIdArray as $AllExamIdArrayValue)
	{
		if($ExamId==$AllExamIdArrayValue)
		{
			
		}
	}
}
?>