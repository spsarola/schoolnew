<?php
include("Include.php");
$Action=$_GET['Action'];
if($Action=="GetNextSessionSection")
{
	$UniqueId=$_GET['UniqueId'];
	$query2="select ClassName,SectionName,SectionId from class,section where 
		class.ClassId=section.ClassId and class.ClassStatus='Active' and
		section.SectionStatus='Active' and class.Session='$UniqueId' order by ClassName";
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	{
		$SelectClassName=$row2['ClassName'];
		$SelectSectionName=$row2['SectionName'];
		$SelectSectionId=$row2['SectionId'];
		echo "<option value=\"$SelectSectionId\">$SelectClassName $SelectSectionName</option>";
	}	
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetCurrentClassStudent")
{
	$UniqueId=$_GET['UniqueId'];
	$query="Select admission.AdmissionId,registration.RegistrationId,StudentName,FatherName,Mobile,ClassName,SectionName,section.SectionId,class.ClassId from registration,class,section,admission,studentfee where
	studentfee.Session='$CURRENTSESSION' and
	class.ClassId=section.ClassId and
	studentfee.SectionId=section.SectionId and
	registration.RegistrationId=admission.RegistrationId and
	admission.AdmissionId=studentfee.AdmissionId and 
	studentfee.SectionId='$UniqueId' and 
	Status='Studying'
	order by StudentName";	
	$check=mysqli_query($CONNECTION,$query);
	while($row=mysqli_fetch_array($check))
	{
		$StudentName=$row['StudentName'];
		$FatherName=$row['FatherName'];
		$Mobile=$row['Mobile'];
		$RegistrationId=$row['RegistrationId'];
		$AdmissionId=$row['AdmissionId'];
		echo "<option value=\"$AdmissionId\">$StudentName $FatherName $Mobile</option>";
	}
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetClassExam")
{
	$UniqueId=$_GET['UniqueId'];
	$query="select ExamName,Weightage,ExamId from exam,section where 
	ExamStatus='Active' and 
	exam.SectionId='$UniqueId' and
	exam.SectionId=section.SectionId and
	section.SectionStatus='Active' ";	
	$check=mysqli_query($CONNECTION,$query);
	while($row=mysqli_fetch_array($check))
	{
		$ExamName=$row['ExamName'];
		$Weightage=round($row['Weightage'],2);
		$ExamId=$row['ExamId'];
		echo "<option value=\"$ExamId\">$ExamName</option>";
	}
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetSCArea")
{
	$UniqueId=explode("-",$_GET['UniqueId']);
	$SectionId=$UniqueId[1];
	
	$query2="select SCAreaId,SCAreaName,MasterEntryValue,SCAreaClass from masterentry,scarea where
		Session='$CURRENTSESSION' and scarea.SCPartId=masterentry.MasterEntryId ";
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	{
		$SCAreaId=$row2['SCAreaId'];
		$SCAreaName=$row2['SCAreaName'];
		$MasterEntryValue=$row2['MasterEntryValue'];
		$SCAreaClass=explode(",",$row2['SCAreaClass']);
		$SearchIndex=array_search($SectionId,$SCAreaClass);
		if($SearchIndex===FALSE){}
		else
		echo "<option value=\"$SCAreaId\">$SCAreaName ($MasterEntryValue)</option>";
	}
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetExamSubject")
{
	$UniqueId=explode("-",$_GET['UniqueId']);
	$SectionId=$UniqueId[1];
	
	$query2="select subject.SubjectId,SubjectName,Class from subject,examdetail where
		Session='$CURRENTSESSION' and SubjectStatus='Active' and subject.SubjectId=examdetail.SubjectId  group by examdetail.SubjectId";
	$check2=mysqli_query($CONNECTION,$query2);
	while($row2=mysqli_fetch_array($check2))
	{
		$SubjectId=$row2['SubjectId'];
		$SubjectName=$row2['SubjectName'];
		$Class=explode(",",$row2['Class']);
		$SearchIndex=array_search($SectionId,$Class);
		if($SearchIndex===FALSE){}
		else
		echo "<option value=\"$SubjectId\">$SubjectName</option>";
	}
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetClassSchoolMaterial")
{
	$ClassId=$_GET['UniqueId'];
	$query="select Name,SchoolMaterialId from schoolmaterial where 
		Session='$CURRENTSESSION' and 
		ClassId='$ClassId' and
		SchoolMaterialStatus='Active' 
		order by Name ";
	$check=mysqli_query($CONNECTION,$query);
	while($row=mysqli_fetch_array($check))
	{
		$Name=$row['Name'];
		$SchoolMaterialId=$row['SchoolMaterialId'];
		echo "<option value=\"$SchoolMaterialId\">$Name</option>";
	}	
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetAssignToDetail")
{
	$UniqueId=$_GET['UniqueId'];
	$AssignToName=GetCategoryValueOfId($UniqueId,'AssignTo');
	if($AssignToName=="Location")
	{
		$query="select LocationName,CalledAs,LocationId from location where LocationStatus='Active' order by LocationName ";
		$check=mysqli_query($CONNECTION,$query);
		while($row=mysqli_fetch_array($check))
		{
			$LocationName=$row['LocationName'];
			$CalledAs=$row['CalledAs'];
			$LocationId=$row['LocationId'];
			$ListOption.="<option value=\"$LocationId\">$LocationName ($CalledAs)</option>";
		}
	}
	elseif($AssignToName=="Staff")
	{
		$query="select StaffName,MasterEntryValue,StaffId from staff,masterentry where
			staff.StaffPosition=masterentry.MasterEntryId and
			StaffStatus='Active' order by StaffPosition,StaffName ";
		$check=mysqli_query($CONNECTION,$query);
		while($row=mysqli_fetch_array($check))
		{
			$StaffName=$row['StaffName'];
			$StaffPosition=$row['MasterEntryValue'];
			$StaffId=$row['StaffId'];
			$ListOption.="<option value=\"$StaffId\">$StaffName ($StaffPosition)</option>";
		}
	}
	elseif($AssignToName=="Student")
	{
		$query="select ClassName,SectionName,StudentName,FatherName,Mobile,admission.AdmissionId from admission,registration,class,section,studentfee where 
				studentfee.AdmissionId=admission.AdmissionId and
				studentfee.Session='$CURRENTSESSION'
				and registration.RegistrationId=admission.RegistrationId
				and class.ClassId=section.ClassId
				and registration.Status='Studying' group by studentfee.AdmissionId
				order by StudentName,registration.SectionId ";
		$check=mysqli_query($CONNECTION,$query);
		while($row=mysqli_fetch_array($check))
		{
			$StudentName=$row['StudentName'];
			$FatherName=$row['FatherName'];
			$ClassName=$row['ClassName'];
			$SectionName=$row['SectionName'];
			$AdmissionId=$row['AdmissionId'];
			$ListOption.="<option value=\"$AdmissionId\">$StudentName - $FatherName ($ClassName $SectionName)</option>";		
		}
	}
	elseif($AssignToName=="Other")
	{
		$query="select MasterEntryId,MasterEntryValue from masterentry where MasterEntryName='OtherAssignTo' and MasterEntryStatus='Active' ";
		$check=mysqli_query($CONNECTION,$query);
		while($row=mysqli_fetch_array($check))
		{
			$MasterEntryId=$row['MasterEntryId'];
			$MasterEntryValue=$row['MasterEntryValue'];
			$ListOption.="<option value=\"$MasterEntryId\">$MasterEntryValue</option>";		
		}	
	}
	echo $ListOption;
}
//////////////////////////////////////////////////////////////////////////
elseif($Action=="GetStockId")
{
	echo "<option value=\"\">Select One</option>";
	$UniqueId=$_GET['UniqueId'];
	$query="select StockId,StockName from stock where StockType='$UniqueId' and StockStatus='Active' order by StockName ";
	$check=mysqli_query($CONNECTION,$query);	
	while($row=mysqli_fetch_array($check))
	{
		$StockName=$row['StockName'];
		$StockId=$row['StockId'];
		echo "<option value=\"$StockId\">$StockName</option>";
	}	
}
?>