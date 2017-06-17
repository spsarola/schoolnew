<?php
include("Include.php");
include("Grading.php");
?>
<title>Exam Report</title>
<style>
*{font-family:verdana; font-size:10px;}
table.fancy {  font-size:10px; background: whitesmoke;  border-collapse: collapse;  width:98%;  margin:0 auto;  margin-bottom:10px; margin-top:10px;}
//table.fancy tr:hover {   background: lightsteelblue !important;}
table.fancy th, table.fancy td {  border: 1px silver solid;  padding: 0.2em;  padding-left:10px; vertical-align:top}
table.fancy th {  background: gainsboro;  text-align: left;}
table.fancy caption {  margin-left: inherit;  margin-right: inherit;}
table.fancy tr:hover{background-color:#ddd;}
</style>
<?php
$AdmissionId=$_GET['AdmissionId'];
$query10="select registration.RegistrationId,StudentName,FatherName,MotherName,Mobile,ClassName,SectionName,DOB from registration,admission,studentfee,class,section where
	registration.RegistrationId=admission.RegistrationId and
	admission.AdmissionId=studentfee.AdmissionId and
	studentfee.Session='$CURRENTSESSION' and
	admission.AdmissionId='$AdmissionId' and
	studentfee.SectionId=section.SectionId and 
	section.ClassId=class.ClassId ";
$check10=mysqli_query($CONNECTION,$query10);
$count10=mysqli_num_rows($check10);
if($count10!=1)
echo "<p style=\"font-weight:bold;color:Red;\">This is not a valid student!!</p>";
else
{

$row10=mysqli_fetch_array($check10);
$StudentName=$row10['StudentName'];
$MotherName=$row10['MotherName'];
$FatherName=$row10['FatherName'];
$Mobile=$row10['Mobile'];
$ClassName=$row10['ClassName'];
$SectionName=$row10['SectionName'];
$AdmissionId=$row10['RegistrationId'];
$DOB=$row10['DOB'];
if($DOB!="")
$DOB=date("d M Y",$DOB);
echo "<table class=fancy>
	<tr>
		<th>Student Name</th><td>$StudentName</td>
		<th>Father Name</th><td>$FatherName</td>
		<th>Mother Name</th><td>$MotherName</td>
	</tr>
	<tr>
		<th>Class</th><td>$ClassName $SectionName</td>
		<th>Date of Birth</th><td>$DOB</td>
		<th>Mobile</th><td>$Mobile</td>
	</tr>
</table>";



$query0="select ExamId,exam.SectionId,ExamName,Weightage from exam,studentfee,registration,admission where
	exam.SectionId=studentfee.SectionId and
	exam.Session='$CURRENTSESSION' and
	registration.RegistrationId=admission.RegistrationId and
	admission.AdmissionId=studentfee.AdmissionId and
	registration.RegistrationId='$AdmissionId' and
	studentfee.Session='$CURRENTSESSION' and
	ExamStatus='Active'";
$check0=mysqli_query($CONNECTION,$query0);
while($row0=mysqli_fetch_array($check0))
{
	$ExamId=$row0['ExamId'];
	$ExamName=$row0['ExamName'];
	$Weightage=round($row0['Weightage'],2);
	$SectionId=$row0['SectionId'];
		
	$SubHeading="";
	$SubName="";
	if($ExamName=="FA I")
	{
		$XamPriority[]=1;
		$SubHeading="<th>Subject</th>";
		$SubName="<td>$SubjectAbb</td>";
	}
	elseif($ExamName=="FA II")
	$XamPriority[]=2;
	elseif($ExamName=="FA III")
	{
		$XamPriority[]=5;
		$SubHeading="<th>Subject</th>";
		$SubName="<td>$SubjectAbb</td>";
	}
	elseif($ExamName=="FA IV")
	$XamPriority[]=6;
	elseif($ExamName=="SA I")
	$XamPriority[]=3;
	elseif($ExamName=="SA II")
	$XamPriority[]=7;
	
	$qry="select SubjectId,SubjectName,SubjectAbb,Class,SubjectId from subject where SubjectStatus='Active' and Session='$CURRENTSESSION' and FIND_IN_SET($SectionId, Class) > 0";
	$chk=mysqli_query($CONNECTION,$qry);
	$STRTotal="";
	$STRHeading="";
	$STRTableHeading="";
	$STRTableData="";
	$i=0;
	while($rw=mysqli_fetch_array($chk))
	{
		$SubjectId=$rw['SubjectId'];
		$SubjectName=$rw['SubjectName'];
		$SubjectAbb=$rw['SubjectAbb'];
		
		if($ExamName=="FA I" || $ExamName=="FA III")
			$SubName="<td>$SubjectAbb</td>";
		else
			$SubName="";
		
		$TotalMarks=0;
		$TotalMaximumMarks=0;
		$i++;
		$STRTableData="";
		$query="select ExamActivityName,MaximumMarks,Marks from examdetail where ExamDetailStatus='Active' and ExamId='$ExamId' and SubjectId='$SubjectId' order by ExamActivityName";
		$check=mysqli_query($CONNECTION,$query);
		$count=mysqli_num_rows($check);
		$UnsetMarks=0;
		while($row=mysqli_fetch_array($check))
		{
			$ExamActivityName=$row['ExamActivityName'];
			$MaximumMarks=$row['MaximumMarks'];
			$Marks=explode(",",$row['Marks']);
			unset($FinalMarks);
			foreach($Marks as $MarksValue)
			{
				$MarksArrayValue=explode("-",$MarksValue);
				if($MarksArrayValue[0]==$AdmissionId)
				$FinalMarks=$MarksArrayValue[1];
			}
			if(!isset($FinalMarks) || !is_numeric($FinalMarks))
			$FinalMarks="-";
			
			if($FinalMarks=="-")
			$UnsetMarks++;
			
			if($i==1)
			$STRTableHeading.="<th>$ExamActivityName($MaximumMarks)</th>";
			$STRTableData.="<td>$FinalMarks</td>";
			$TotalMarks+=$FinalMarks;
			$TotalMaximumMarks+=$MaximumMarks;
			if($ExamName=="FA I" || $ExamName=="FA II" || $ExamName=="FA III" || $ExamName=="FA IV" )
			$TotalMarksExam=$TotalMarks*.20;
			elseif($ExamName=="SA I" || $ExamName=="SA II")
			$TotalMarksExam=$TotalMarks*.30;			
		}
		if($UnsetMarks==$count)
		{
			$Total="-";
			$WeightageTotal="-";
			$TotalMarks="-";
			$Grading="-";
		}
		else
		{
			$WeightageTotal=round($TotalMarks*($Weightage/100),2);
			$WeightageMaximum=round($TotalMaximumMarks*($Weightage/100),2);
			$Grading=Grade($TotalMaximumMarks,$TotalMarks);
		}
		
		if($count>0)
		{
			$STRHeading.="<tr>$SubName $STRTableData<td>$TotalMarksExam</td><td>$Grading</td></tr>";
			$ExamNameArray[]=$ExamName;
			$SubjectNameArray[]=$SubjectAbb;
			if(is_numeric($TotalMarks))
			$MarksArray[]=$TotalMarks;
			else
			$MarksArray[]="";
		}
	}
		$XamTable[]="<tr><th colspan=21 style=\"text-align:center;\">$ExamName</th></tr><tr>$SubHeading $STRTableHeading<th>Total</th><th>Grade</th></tr>
			$STRHeading";
	
}

$UniqueSubjectNameArray=array_unique($SubjectNameArray);

foreach($UniqueSubjectNameArray as $UniqueSubjectNameArrayValue)
{
	$x=0;
	$T1=0;
	foreach($ExamNameArray as $ExamNameArrayValue)
	{
	if(($ExamNameArrayValue=="FA I" || $ExamNameArrayValue=="FA II") && $SubjectNameArray[$x]==$UniqueSubjectNameArrayValue)
	$T1+=$MarksArray[$x]*.20;
	elseif($ExamNameArrayValue=="SA I" && $SubjectNameArray[$x]==$UniqueSubjectNameArrayValue)
	$T1+=$MarksArray[$x]*.30;
	$x++;
	}
	$Term1Subject[]=$UniqueSubjectNameArrayValue;
	$Term1Marks[]=$T1;
	//$TotalTerms1Marks+=$T1;
	$TotalTerm1Subject++;
}


foreach($UniqueSubjectNameArray as $UniqueSubjectNameArrayValue)
{
	$y=0;
	$T2=0;
	foreach($ExamNameArray as $ExamNameArrayValue)
	{
	if(($ExamNameArrayValue=="FA III" || $ExamNameArrayValue=="FA IV") && $SubjectNameArray[$y]==$UniqueSubjectNameArrayValue)
	$T2+=$MarksArray[$y]*.20;
	elseif($ExamNameArrayValue=="SA II" && $SubjectNameArray[$y]==$UniqueSubjectNameArrayValue)
	$T2+=$MarksArray[$y]*.30;
	$y++;
	}
	$Term2Subject[]=$UniqueSubjectNameArrayValue;
	$Term2Marks[]=$T2;
	//$TotalTerms2Marks+=$T2;
	$TotalTerm2Subject++;
}

$x1=0;
$x2=0;
$XamPriority[]=4;
$T1Exam="<tr><th colspan=2 style=\"text-align:center;\">TERM I</th></tr><tr><th>Grade</th><th>GP</tr>";
foreach($Term1Subject as $Term1SubjectValue)
{
	$T1Marks=$Term1Marks[$x1];
	$T1Grade=Grade(50,$T1Marks);
	$T1GP=CGPA($T1Grade);
	$TotalTerms1Marks+=$T1GP;
	$Term1.="<tr><td>$T1Marks($T1Grade)</td><td>$T1GP</td></tr>";
	$x1++;
}
$T1Exam.="$Term1";
$XamTable[]=$T1Exam;

$XamPriority[]=8;
$T2Exam="<tr><th colspan=2 style=\"text-align:center;\">TERM II</th></tr><tr><th>Grade</th><th>GP</th></tr>";
foreach($Term2Subject as $Term2SubjectValue)
{
	$T2Marks=$Term2Marks[$x2];
	$T2Grade=Grade(50,$T2Marks);
	$T2GP=CGPA($T2Grade);
	$TotalTerms2Marks+=$T2GP;
	$Term2.="<tr><td>$T2Marks($T2Grade)</td><td>$T2GP</td></tr>";
	$x2++;
}
$T2Exam.="$Term2";
$XamTable[]=$T2Exam;

asort($XamPriority);

foreach($XamPriority as $index=>$XamPriorityValue)
{
	$m++;
	if($m==4 || $m==8)
	$width="8%";
	else
	$width="24%";
	echo "<table class=fancy style=\"margin-left:5px; float:left; width:$width;\"> $XamTable[$index] </table>";
	if($m==4)
	echo "<div style=\"clear:left;\"></div>";
}

$Term1Grade=Grade(10,($TotalTerms1Marks/$TotalTerm1Subject));
$Term2Grade=Grade(10,($TotalTerms2Marks/$TotalTerm2Subject));
$OverAllGrade=Grade(10,(($TotalTerms1Marks+$TotalTerms2Marks)/($TotalTerm1Subject+$TotalTerm2Subject)));
$OverallCGPA=CGPA($OverAllGrade);
echo "<table class=fancy>
	<tr><th>Term 1 Grade</th><th>Term 2 Grade</th><th>Overall Grade</th><th>CGPA</th></tr>
	<tr><td>$Term1Grade</td><Td>$Term2Grade</td><Td>$OverAllGrade</td><td>$OverallCGPA</td></tr>
	</table>";
}
?>