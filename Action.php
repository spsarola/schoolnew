<?php

include("Include.php");
IsLoggedIn();
$Action = $_POST['Action'];
$RandomNumber = $_POST['RandomNumber'];
if ($Action == "")
    header("Location:LogIn");
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Delete") {
    $Password = $_POST['Password'];
    if ($USERTYPE != "MasterUser" && $USERTYPE != "Webmaster") {
        $Message = "You don't have priviledge to delete the database!!";
        $Type = "error";
    } elseif (md5($Password) != $PASSWORD) {
        $Message = "Wrong Password!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query45 = "select Password from user where Username='$USERNAME' ";
        $check45 = mysqli_query($CONNECTION, $query45);
        $row45 = mysqli_fetch_array($check45);
        $UserPassword = $row45['Password'];
        $UserUsername = $row45['Username'];

        $res = mysqli_query($CONNECTION, 'SHOW TABLES');
        while ($row = mysqli_fetch_array($res, MYSQLI_NUM)) {
            if ($row[0] == "masterentrycategory" || $row[0] == "pagename" || $row[0] == "tablename" || $row[0] == "masterentry") {
                
            } else {
                $table = $row[0];
                $res2 = mysqli_query($CONNECTION, "TRUNCATE TABLE $table ");
            }
        }
        $query4 = "DELETE  FROM masterentry
		WHERE   MasterEntryName IN (
            SELECT  MasterEntryCategoryValue
            FROM    masterentrycategory
            WHERE   Permission!='Webmaster'
        )";
        mysqli_query($CONNECTION, $query4);

        $MasterUserPassword = md5("webmaster");
        $UserPassword = md5("123456");
        $query3 = "insert into user(Username,Password,UserType) values
	('webmaster','$MasterUserPassword','0'),('masteruser','$UserPassword','0')";
        mysqli_query($CONNECTION, $query3);
        session_destroy();
        $Message = "Database Deleted!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:DatabaseDelete");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "GeneralSetting") {
    /* $SchoolName=$_POST['SchoolName'];
      $SchoolStartDate=$_POST['SchoolStartDate'];
      $SchoolAddress=$_POST['SchoolAddress'];
      $City=$_POST['City'];
      $District=$_POST['District'];
      $PIN=$_POST['PIN'];
      $State=$_POST['State'];
      $Country=$_POST['Country'];
      $Mobile=$_POST['Mobile'];
      $AlternateMobile=$_POST['AlternateMobile'];
      $Email=$_POST['Email'];
      $Landline=$_POST['Landline'];
      $Fax=$_POST['Fax'];
      $DateOfEstablishment=$_POST['DateOfEstablishment'];
      $Board=$_POST['Board'];
      $AffiliatedBy=$_POST['AffiliatedBy'];
      $RegistrationNo=$_POST['RegistrationNo'];
      $AffiliationNo=$_POST['AffiliationNo']; */

    array_walk($_POST, "FilterSqlInjection");

    if ($Mobile != "")
        $CheckMobile = CheckMobile($Mobile);
    if ($AlternateMobile != "")
        $CheckAlternateMobile = CheckMobile($AlternateMobile);
    if ($Email != "")
        $CheckEmail = CheckEmail($Email);
    if ($Landline != "")
        $CheckLandline = CheckLandline($Landline);

    if ($SchoolName == "" || $SchoolStartDate == "") {
        $Message = "School Name,Software starting date & Backup path are mandatory!!";
        $Type = error;
    } elseif ($Mobile != "" && $CheckMobile == 0) {
        $Message = "Mobile number is not valid!!";
        $Type = error;
    } elseif ($AlternateMobile != "" && $CheckAlternateMobile == 0) {
        $Message = "Alternate Mobile number is not valid!!";
        $Type = error;
    } elseif ($Email != "" && $CheckEmail == 0) {
        $Message = "Email is not valid!!";
        $Type = error;
    } elseif ($Landline != "" && $CheckLandline == 0) {
        $Message = "Landline is not valid!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query = "Select Id from generalsetting";
        $check = mysqli_query($CONNECTION, $query);
        $count = mysqli_num_rows($check);
        $DateOfEstablishment = strtotime($DateOfEstablishment);
        $SchoolStartDate = strtotime($SchoolStartDate);
        if ($count == 0) {
            $DOE = strtotime($Date);
            $query1 = "insert into generalsetting(SchoolStartDate,SchoolName,SchoolAddress,City,District,PIN,State,Country,Mobile,AlternateMobile,Email,Landline,Fax,DateOfEstablishment,Board,AffiliatedBy,RegistrationNo,AffiliationNo,DOE)
			values('$SchoolStartDate','$SchoolName','$SchoolAddress','$City','$District','$PIN','$State','$Country','$Mobile','$AlternateMobile','$Email','$Landline','$Fax','$DateOfEstablishment','$Board','$AffiliatedBy','$RegistrationNo','$AffiliationNo','$DOE') ";
        } else {
            $DOL = strtotime($Date);
            $query1 = "update generalsetting set SchoolName='$SchoolName',SchoolAddress='$SchoolAddress',City='$City',
			District='$District',PIN='$PIN',State='$State',Country='$Country',Mobile='$Mobile',AlternateMobile='$AlternateMobile',Email='$Email',
			Landline='$Landline',Fax='$Fax',DateOfEstablishment='$DateOfEstablishment',Board='$Board',AffiliatedBy='$AffiliatedBy',RegistrationNo='$RegistrationNo',AffiliationNo='$AffiliationNo',
			DOL='$DOL' ";
        }
        mysqli_query($CONNECTION, $query1);
        $Message = "Setting saved successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:GeneralSetting");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ChangePassword") {
    $CurrentPassword = $_POST['CurrentPassword'];
    $NewPassword = $_POST['NewPassword'];
    $ConfirmPassword = $_POST['ConfirmPassword'];

    if ($_SESSION['USERTYPEID'] === "Parents") {
        $PasswordField = "ParentsPassword";
        $ParentUsernameArray = explode('@', $USERNAME);
        $AdmissionId = $ParentUsernameArray[0];
        $query = "Select registration.RegistrationId from registration,admission where registration.RegistrationId=admission.RegistrationId and AdmissionId='$AdmissionId' and ParentsPassword='$CurrentPassword' ";
    } elseif ($_SESSION['USERTYPEID'] === "Student") {
        $PasswordField = "StudentsPassword";
        $StudentUsernameArray = explode('@', $USERNAME);
        $AdmissionId = $StudentUsernameArray[0];
        $query = "Select registration.RegistrationId from registration,admission where registration.RegistrationId=admission.RegistrationId and AdmissionId='$AdmissionId' and StudentsPassword='$CurrentPassword' ";
    } else {
        if (isset($CurrentPassword))
            $CurrentPassword = md5($CurrentPassword);
        $query = "Select Password from user where Username='$USERNAME' and Password='$CurrentPassword' ";
    }
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($CurrentPassword == "" || $NewPassword == "" || $ConfirmPassword == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif (strlen($NewPassword) < 6) {
        $Message = "New password length cannot be less than 6 characters!!";
        $Type = "error";
    } elseif ($NewPassword != $ConfirmPassword) {
        $Message = "New password did not match!!";
        $Type = "error";
    } elseif ($count == 0) {
        $Message = "Current password did not match!!";
        $Type = "error";
    } elseif ($NewPassword == $CurrentPassword) {
        $Message = "New Password cannot be same as Current Password!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($_SESSION['USERTYPEID'] == "Student" || $_SESSION['USERTYPEID'] == "Parents") {
            $row = mysqli_fetch_array($check);
            $RegistrationId = $row['RegistrationId'];
            mysqli_query($CONNECTION, "update registration set $PasswordField='$NewPassword' where RegistrationId='$RegistrationId' ");
        } else {
            $NewPassword = md5($NewPassword);
            mysqli_query($CONNECTION, "update user set Password='$NewPassword' where Username='$USERNAME' ");
        }
        $_SESSION['PASSWORD'] = $NewPassword;
        $Message = "Password Updated!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:ChangePassword");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "MasterEntryCategory") {
    array_walk($_POST, "FilterSqlInjection");
    $MasterEntryCategoryValue = $_POST['MasterEntryCategoryValue'];
    $MasterEntryCategoryName = $_POST['MasterEntryCategoryName'];
    $Permission = $_POST['Permission'];

    $check = mysqli_query($CONNECTION, "select MasterEntryCategoryId from masterentrycategory where MasterEntryCategoryValue='$MasterEntryCategoryValue' and MasterEntryCategoryName='$MasterEntryCategoryName' ");
    $count = mysqli_num_rows($check);

    if ($MasterEntryCategoryValue == "" || $MasterEntryCategoryName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This Value is already added to selected Category!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query = "insert into masterentrycategory(MasterEntryCategoryName,MasterEntryCategoryValue,Permission) values('$MasterEntryCategoryName','$MasterEntryCategoryValue','$Permission') ";
        mysqli_query($CONNECTION, $query);
        $Message = "Value added to selected Category!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:MasterEntry");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "MasterEntry") {
    array_walk($_POST, "FilterSqlInjection");
    $MasterEntryValue = $_POST['MasterEntryValue'];
    $MasterEntryName = $_POST['MasterEntryName'];
    $MasterEntryId = $_POST['MasterEntryId'];
    $MasterEntryStatus = $_POST['MasterEntryStatus'];
    if ($MasterEntryStatus != "Active")
        $MasterEntryStatus = "InActive";

    if ($MasterEntryId != "")
        $Update = " and MasterEntryId!='$MasterEntryId' ";
    $query1 = "select * from masterentry where MasterEntryName='$MasterEntryName' and MasterEntryValue='$MasterEntryValue' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldMasterEntryStatus = $row1['MasterEntryStatus'];
            if ($OldMasterEntryStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($MasterEntryId != "") {
        $addupdate = "updated";
        $query2 = "select MasterEntryStatus from masterentry where MasterEntryId='$MasterEntryId' and (MasterEntryStatus='InActive' or MasterEntryStatus='Active') ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentMasterEntryStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($MasterEntryValue == "" || $MasterEntryName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This Value is already added to selected Category!!";
        $Type = "error";
    } elseif ($MasterEntryId != "" && $CurrentMasterEntryStatus == 0) {
        $Message = "This entry is deleted. You cannot update the deleted entry!!";
        $Type = "error";
    } elseif ($MasterEntryName == "UserType" && ($MasterEntryValue == "MasterUser" || $MasterEntryValue == "Webmaster")) {
        $Message = "$MasterEntryValue cannot be set as User Type!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($MasterEntryId == "")
            $query = "insert into masterentry(MasterEntryStatus,MasterEntryName,MasterEntryValue) values('Active','$MasterEntryName','$MasterEntryValue') ";
        else
            $query = "update masterentry set MasterEntryName='$MasterEntryName',MasterEntryValue='$MasterEntryValue',MasterEntryStatus='$MasterEntryStatus' where MasterEntryId='$MasterEntryId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Value $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($MasterEntryId == "")
        header("Location:MasterEntry");
    else
        header("Location:MasterEntry/Update/$MasterEntryId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageAccounts") {
    array_walk($_POST, "FilterSqlInjection");
    $AccountName = $_POST['AccountName'];
    $AccountType = $_POST['AccountType'];
    $AccountTypeName = GetCategoryValueOfId($AccountType, 'AccountType');
    $ManagedBy = $_POST['ManagedBy'];
    $OpeningBalance = $_POST['OpeningBalance'];
    $AccountDate = strtotime($_POST['AccountDate']);

    if ($AccountTypeName == "Bank") {
        $BankAccountName = $_POST['BankAccountName'];
        $BankName = $_POST['BankName'];
        $BranchName = $_POST['BranchName'];
        $IFSCCode = $_POST['IFSCCode'];
    }

    $AccountId = $_POST['AccountId'];
    $AccountStatus = $_POST['AccountStatus'];
    if ($AccountStatus != "Active")
        $AccountStatus = "InActive";

    if ($AccountId != "")
        $Update = " and AccountId!='$AccountId' ";
    $query1 = "select * from accounts where AccountName='$AccountName' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldAccountStatus = $row1['AccountStatus'];
            if ($OldAccountStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($AccountId != "") {
        $addupdate = "updated";
        $query2 = "select AccountStatus from accounts where AccountId='$AccountId' and (AccountStatus='InActive' or AccountStatus='Active') ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentAccountStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($AccountName == "" || $OpeningBalance == "" || $AccountDate == "") {
        $Message = "Account name, Opening Balance, Managed by & Account date are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This account is already added!!";
        $Type = "error";
    } elseif (($BankAccountName == "" || $BankName == "" || $BranchName == "") && $AccountTypeName == "Bank") {
        $Message = "Bank account name, bank name & branch name are mandatory!! $BankAccountName $BankName $BranchName";
        $Type = "error";
    } elseif ($AccountId != "" && $CurrentAccountStatus == 0) {
        $Message = "This account is deleted. You cannot update the deleted account!!";
        $Type = "error";
    } elseif ($SCHOOLSTARTDATE > $AccountDate) {
        $Message = "You cannot start your account before software start date!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($AccountId == "") {
            $DOE = strtotime($Date);
            $query = "insert into accounts(AccountName,BankAccountName,BankName,BranchName,IFSCCode,AccountType,ManagedBy,OpeningBalance,AccountDate,DOE,AccountStatus) 
			values('$AccountName','$BankAccountName','$BankName','$BranchName','$IFSCCode','$AccountType','$ManagedBy','$OpeningBalance','$AccountDate','$DOE','Active') ";
        } else {
            $query = "update accounts set AccountName='$AccountName',BankAccountName='$BankAccountName',BankName='$BankName',
			BranchName='$BranchName',IFSCCode='$IFSCCode',ManagedBy='$ManagedBy',OpeningBalance='$OpeningBalance',
			AccountDate='$AccountDate',AccountStatus='$AccountStatus'
			where AccountId='$AccountId' ";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Account $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($AccountId == "")
        header("Location:ManageAccounts");
    else
        header("Location:ManageAccounts/Update/$AccountId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageClass") {
    array_walk($_POST, "FilterSqlInjection");
    $ClassName = $_POST['ClassName'];
    $ClassId = $_POST['ClassId'];

    if ($ClassId != "")
        $Update = " and ClassId!='$ClassId' ";
    $query1 = "select * from class where ClassName='$ClassName' and Session='$CURRENTSESSION' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldClassStatus = $row1['ClassStatus'];
            if ($OldClassStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($ClassId != "") {
        $addupdate = "updated";
        $query2 = "select ClassStatus from class where ClassId='$ClassId' and ClassStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentClassStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($ClassName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This Value is already added!!";
        $Type = "error";
    } elseif ($ClassId != "" && $CurrentClassStatus == 0) {
        $Message = "This class is deleted. You cannot update the deleted class!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($ClassId == "")
            $query = "insert into class(ClassName,Session,ClassStatus,DOE) values('$ClassName','$CURRENTSESSION','Active','$DOE') ";
        else
            $query = "update class set ClassName='$ClassName',DOL='$DOL' where ClassId='$ClassId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Class $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($ClassId == "")
        header("Location:ManageClass");
    else
        header("Location:ManageClass/UpdateClass/$ClassId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSection") {
    array_walk($_POST, "FilterSqlInjection");
    $SectionName = $_POST['SectionName'];
    $ClassId = $_POST['ClassId'];
    $SectionId = $_POST['SectionId'];

    if ($SectionId != "")
        $Update = " and SectionId!='$SectionId' ";
    $query1 = "select * from section where SectionName='$SectionName' and ClassId='$ClassId' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSectionStatus = $row1['SectionStatus'];
            if ($OldSectionStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SectionId != "") {
        $addupdate = "updated";
        $query2 = "select SectionStatus from section where SectionId='$SectionId' and SectionStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSectionStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($SectionName == "" || $ClassId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This Value is already added!!";
        $Type = "error";
    } elseif ($SectionId != "" && $CurrentSectionStatus == 0) {
        $Message = "This section is deleted. You cannot update the deleted section!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($SectionId == "")
            $query = "insert into section(SectionName,ClassId,SectionStatus,DOE) values('$SectionName','$ClassId','Active','$DOE') ";
        else
            $query = "update section set SectionName='$SectionName',ClassId='$ClassId',DOL='$DOL' where SectionId='$SectionId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Section $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SectionId == "")
        header("Location:ManageClass");
    else
        header("Location:ManageClass/UpdateSection/$SectionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSubject") {
    array_walk($_POST, "FilterSqlInjection");
    $SubjectName = $_POST['SubjectName'];
    $SubjectAbb = $_POST['SubjectAbb'];
    $SubjectId = $_POST['SubjectId'];
    $Class = $_POST['Class'];
    $ClassCount = count($Class);
    foreach ($Class as $k) {
        $i++;
        if ($i < $ClassCount)
            $ClassSTR.="$k,";
        else
            $ClassSTR.=$k;
    }

    if ($SubjectId != "")
        $Update = " and SubjectId!='$SubjectId' ";
    $query1 = "select * from subject where (SubjectName='$SubjectName' or SubjectAbb='$SubjectAbb') and Session='$CURRENTSESSION' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSubjectStatus = $row1['SubjectStatus'];
            if ($OldSubjectStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SubjectId != "") {
        $addupdate = "updated";
        $query2 = "select SubjectStatus from subject where SubjectId='$SubjectId' and SubjectStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSubjectStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($SubjectName == "" || $SubjectAbb == "" || $Class == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This subject is already added!!";
        $Type = "error";
    } elseif ($SubjectId != "" && $CurrentSubjectStatus == 0) {
        $Message = "This subject is deleted. You cannot update the deleted subject!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($SubjectId == "")
            $query = "insert into subject(SubjectName,SubjectAbb,SubjectStatus,Session,DOE,Class) values('$SubjectName','$SubjectAbb','Active','$CURRENTSESSION','$DOE','$ClassSTR') ";
        else
            $query = "update subject set SubjectName='$SubjectName',SubjectAbb='$SubjectAbb',DOL='$DOL',Class='$ClassSTR' where SubjectId='$SubjectId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Subject $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SubjectId == "")
        header("Location:ManageSubject");
    else
        header("Location:ManageSubject/UpdateSubject/$SubjectId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StudentRegistration") {
    /* $StudentName = $_POST['StudentName'];
      $Mobile = $_POST['Mobile'];
      $FatherName = $_POST['FatherName'];
      $MotherName = $_POST['MotherName'];
      $SSSMID = trim($_POST['SSSMID']);
      $Family_SSSMID = trim($_POST['Family_SSSMID']);
      $Aadhar_No = trim($_POST['Aadhar_No']);
      $Bank_Account_Number = trim($_POST['Bank_Account_Number']);
      $IFSC_Code = trim($_POST['IFSC_Code']);
      $Gender = $_POST['Gender'];
      $DOR = $_POST['DOR'];
      $Class = $_POST['Class']; */

    array_walk($_POST, "FilterSqlInjection", $CONNECTION);
    extract($_POST);

    $query = "select RegistrationId from registration where ((StudentName='$StudentName' and Mobile='$Mobile') or (StudentName='$StudentName' and FatherName='$FatherName'))  and Session='$CURRENTSESSION' and Status!='Deleted' ";

    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($StudentName == "" || $Mobile == "" || $FatherName == "" || $MotherName == "" || $DOR == "" || $Class == "" || $Gender == "" || $SSSMID == "" || $Family_SSSMID == "" || $Aadhar_No == "" || $Bank_Account_Number == "" || $IFSC_Code == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This student is already registered for same class!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOR = strtotime($DOR);
        $DOE = strtotime($Date);
        $StudentsPassword = rand(100000, 999999);
        $ParentsPassword = rand(100000, 999999);
        $query1 = "insert into registration(StudentsPassword,ParentsPassword,StudentName,FatherName,MotherName,DOR,Gender,SectionId,Mobile,Status,DOE,Session,Username,SSSMID,Family_SSSMID,Aadhar_No,Bank_Account_Number,IFSC_Code) values
			('$StudentsPassword','$ParentsPassword','$StudentName','$FatherName','$MotherName','$DOR','$Gender','$Class','$Mobile','NotAdmitted','$DOE','$CURRENTSESSION','$USERNAME','$SSSMID','$Family_SSSMID','$Aadhar_No','$Bank_Account_Number','$IFSC_Code') ";

        mysqli_query($CONNECTION, $query1);
        $Message = "Student registered successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Registration");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageFee") {
    array_walk($_POST, "FilterSqlInjection");
    $FeeType = $_POST['FeeType'];
    $Amount = $_POST['Amount'];
    $SectionId = $_POST['SectionId'];
    $Distance = $_POST['Distance'];
    $TransportFee = $_POST['TransportFee'];
    $FeeId = $_POST['FeeId'];

    if ($TransportFee == "Yes")
        $TransportQuery = " and Distance='$Distance' ";
    if ($FeeId != "")
        $Update = " and FeeId!='$FeeId' ";
    $query1 = "select * from fee where FeeType='$FeeType' and SectionId='$SectionId' and Session='$CURRENTSESSION' $Update $TransportQuery";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldFeeStatus = $row1['FeeStatus'];
            if ($OldFeeStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($FeeId != "") {
        $addupdate = "updated";
        $query2 = "select FeeStatus from fee where FeeId='$FeeId' and FeeStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentFeeStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($FeeType == "" || $SectionId == "" || $Amount == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This fee is already added!!";
        $Type = "error";
    } elseif ($count3 > 0) {
        $Message = "Only one transport fee is allowed!!";
        $Type = "error";
    } elseif ($FeeId != "" && $CurrentFeeStatus == 0) {
        $Message = "This fee is deleted. You cannot update the deleted fee!!";
        $Type = "error";
    } elseif ($TransportFee == "Yes" && $Distance == "") {
        $Message = "Distance is required in case of transport fee!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($FeeId == "") {

            foreach ($SectionId as $SectionIdValue) {
                $i++;
                $query01 = "select FeeId from fee where 
					Session='$CURRENTSESSION' and 
					FeeStatus='Active' and 
					SectionId='$SectionIdValue' and 
					FeeType='$FeeType' and (Distance='' or Distance='$Distance') ";
                $check01 = mysqli_query($CONNECTION, $query01);
                $count01 = mysqli_fetch_array($check01);
                if ($count01 == 0) {
                    if ($queryadd != '')
                        $queryadd.=" , ";
                    $queryadd.="('$CURRENTSESSION','$SectionIdValue','$FeeType','Active','$Amount','$Distance','$DOE')";
                }
            }
            $query = "insert into fee(Session,SectionId,FeeType,FeeStatus,Amount,Distance,DOE) values $queryadd ";
        } else
            $query = "update fee set SectionId='$SectionId',FeeType='$FeeType',Amount='$Amount',Distance='$Distance',DOL='$DOL' where FeeId='$FeeId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Fee $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($FeeId == "")
        header("Location:ManageFee");
    else
        header("Location:ManageFee/UpdateFee/$FeeId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "AdmissionConfirm") {
    array_map($_POST, "FilterSqlInjection");
    $RegistrationId = $_POST['RegistrationId'];
    $SectionId = $_POST['SectionId'];
    $DOA = $_POST['DOA'];
    $Distance = $_POST['Distance'];
    $AdmissionNo = $_POST['AdmissionNo'];
    $Remarks = mynl2br($_POST['Remarks']);
    $FeeArray = $_POST['FeeArray'];
    $FeeArray = explode("-", $FeeArray);
    $Count = count($FeeArray);
    for ($i = 0; $i < $Count; $i++) {
        $FeeAmount = $_POST[$FeeArray[$i]];
        if (!CheckNumeric($FeeAmount))
            $ErrorInFee++;
        $FeeString.="$FeeArray[$i]-$FeeAmount";
        if ($i != ($Count - 1))
            $FeeString.=",";
    }

    $check = mysqli_query($CONNECTION, "select RegistrationId from admission where RegistrationId='$RegistrationId'");
    $count = mysqli_num_rows($check);

    $check2 = mysqli_query($CONNECTION, "select AdmissionNo from admission where AdmissionNo='$AdmissionNo' ");
    $count2 = mysqli_num_rows($check2);

    if ($RegistrationId == "" || $DOA == "" || $SectionId == "" || $AdmissionNo == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This student is already admitted!!";
        $Type = "error";
    } elseif ($count2 > 0) {
        $Message = "This admission no already exists!!";
        $Type = "error";
    } elseif ($ErrorInFee > 0) {
        $Message = "$ErrorInFee number of fees are not numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOA = strtotime($DOA);
        $DOE = strtotime($Date);
        $query = "insert into admission(RegistrationId,DOA,Remarks,DOE) values('$RegistrationId','$DOA','$Remarks','$DOE') ";
        mysqli_query($CONNECTION, $query);
        $AdmissionId = mysqli_insert_id($CONNECTION);
        mysqli_query($CONNECTION, "update registration set Status='Studying' where RegistrationId='$RegistrationId' ");
        $query1 = "insert into studentfee(AdmissionId,AdmissionNo,Session,SectionId,Date,DOE,FeeStructure,Distance,Username)
				values('$AdmissionId','$AdmissionNo','$CURRENTSESSION','$SectionId','$DOA','$DOE','$FeeString','$Distance','$USERNAME') ";
        mysqli_query($CONNECTION, $query1);
        $Message = "Admitted successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Admission");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageExam") {
    array_map($_POST, "FilterSqlInjection");
    $ExamId = $_POST['ExamId'];
    $ExamName = $_POST['ExamName'];
    $SectionId = $_POST['SectionId'];
    $Weightage = $_POST['Weightage'];

    if ($ExamId != "")
        $Update = " and ExamId!='$ExamId' ";
    $query1 = "select * from exam where ExamName='$ExamName' and SectionId='$SectionId' and Session='$CURRENTSESSION' $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldExamStatus = $row1['ExamStatus'];
            if ($OldExamStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($ExamId != "") {
        $addupdate = "updated";
        $query2 = "select ExamStatus from exam where ExamId='$ExamId' and ExamStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentExamStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($ExamName == "" || $SectionId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This exam is already added!!";
        $Type = "error";
    } elseif ($ExamId != "" && $CurrentExamStatus == 0) {
        $Message = "This exam is deleted. You cannot update the deleted exam!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($ExamId == "")
            $query = "insert into exam(Session,SectionId,ExamName,ExamStatus,DOE,Weightage) values('$CURRENTSESSION','$SectionId','$ExamName','Active','$DOE','$Weightage') ";
        else
            $query = "update exam set SectionId='$SectionId',ExamName='$ExamName',DOL='$DOL',Weightage='$Weightage' where ExamId='$ExamId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Exam $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($ExamId == "")
        header("Location:ManageExam");
    else
        header("Location:ManageExam/UpdateExam/$ExamId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageVehicle") {
    array_map($_POST, "FilterSqlInjection");
    $VehicleId = $_POST['VehicleId'];
    $VehicleName = $_POST['VehicleName'];
    $VehicleNumber = $_POST['VehicleNumber'];

    if ($VehicleId != "")
        $Update = " and VehicleId!='$VehicleId' ";
    $query1 = "select * from vehicle where (VehicleName='$VehicleName' or VehicleNumber='$VehicleNumber') $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldVehicleStatus = $row1['VehicleStatus'];
            if ($OldVehicletatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($VehicleId != "") {
        $addupdate = "updated";
        $query2 = "select VehicleStatus from vehicle where VehicleId='$VehicleId' and VehicleStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentVehicleStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($VehicleName == "" || $VehicleNumber == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This vehicle is already added!!";
        $Type = "error";
    } elseif ($VehicleId != "" && $CurrentVehicleStatus == 0) {
        $Message = "This vehicle is deleted. You cannot update the deleted vehicle!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($VehicleId == "")
            $query = "insert into vehicle(VehicleName,VehicleNumber,VehicleStatus,DOE) values('$VehicleName','$VehicleNumber','Active','$DOE') ";
        else
            $query = "update vehicle set VehicleName='$VehicleName',VehicleNumber='$VehicleNumber',DOL='$DOL' where VehicleId='$VehicleId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Vehicle $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($VehicleId == "")
        header("Location:Transport");
    else
        header("Location:Transport/UpdateVehicle/$VehicleId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageFuel") {
    array_map($_POST, "FilterSqlInjection");
    $VehicleId = $_POST['FuelVehicleId'];
    $FuelId = $_POST['FuelId'];
    $Quantity = $_POST['Quantity'];
    $Rate = $_POST['Rate'];
    $ReceiptNo = $_POST['ReceiptNo'];
    $DOF = $_POST['DOF'];
    $Remarks = mynl2br($_POST['Remarks']);

    if ($FuelId != "")
        $Update = " and FuelId!='$FuelId' ";
    $query1 = "select * from vehiclefuel where ReceiptNo='$ReceiptNo' and ReceiptNo!='' $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldFuelStatus = $row1['FuelStatus'];
            if ($OldFueltatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($FuelId != "") {
        $addupdate = "updated";
        $query2 = "select FuelStatus from vehiclefuel where FuelId='$FuelId' and FuelStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentFuelStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($VehicleId == "" || $Quantity == "" || $Rate == "" || $DOF == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This receipt no is already added!!";
        $Type = "error";
    } elseif ($FuelId != "" && $CurrentFuelStatus == 0) {
        $Message = "This fuel id is deleted. You cannot update the deleted fuel id!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        $DOF = strtotime($DOF);
        if ($FuelId == "")
            $query = "insert into vehiclefuel(VehicleId,Quantity,Rate,ReceiptNo,DOF,Remarks,FuelStatus,DOE) values('$VehicleId','$Quantity','$Rate','$ReceiptNo','$DOF','$Remarks','Active','$DOE') ";
        else
            $query = "update vehiclefuel set VehicleId='$VehicleId',Quantity='$Quantity',Rate='$Rate',ReceiptNo='$ReceiptNo',DOF='$DOF',Remarks='$Remarks',DOL='$DOL' where FuelId='$FuelId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Fuel id $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($FuelId == "")
        header("Location:Transport");
    else
        header("Location:Transport/UpdateFuel/$FuelId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageVehicleReading") {
    array_map($_POST, "FilterSqlInjection");
    $VehicleId = $_POST['ReadingVehicleId'];
    $VehicleReadingId = $_POST['VehicleReadingId'];
    $Reading = $_POST['Reading'];
    $DOR = $_POST['DOR'];
    $Remarks = mynl2br($_POST['ReadingRemarks']);

    $DOROnlyDate = date("d-m-Y", $DOR);
    $DOROnlyDateTimeStamp = strtotime($DOROnlyDate);
    $DOROnlyDateNextDate = date('d-m-Y', strtotime('+1 day', strtotime($DOROnlyDate)));
    $DOROnlyDateNextDateTimeStamp = strtotime($DOROnlyDateNextDate);

    if ($VehicleReadingId != "")
        $Update = " and VehicleReadingId!='$VehicleReadingId' ";

    if ($VehicleReadingId == "") {
        $query2 = "select MAX(Reading) as High from vehiclereading where VehicleId='$VehicleId' $Update";
        $check2 = mysqli_query($CONNECTION, $query2);
        $count2 = mysqli_num_rows($check2);
        if ($count2 > 0) {
            $row2 = mysqli_fetch_array($check2);
            $High = $row2['High'];
        }
    } else {
        $query2 = "select Reading from vehiclereading where (Reading>'$Reading' and DOR<'$DOR' $Update) or (Reading<'$Reading' and DOR>'$DOR' $Update) ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $count2 = mysqli_num_rows($check2);
    }

    $query1 = "select Reading from vehiclereading where DOR>='$DOROnlyDateTimeStamp' and DOR<'$DOROnlyDateNextDateTimeStamp' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($VehicleReadingId != "")
        $Update = " and VehicleReadingId!='$VehicleReadingId' ";
    $query1 = "select * from vehiclereading where ReceiptNo='$ReceiptNo' $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldFuelStatus = $row1['FuelStatus'];
            if ($OldFueltatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($VehicleReadingId != "")
        $addupdate = "updated";
    else
        $addupdate = "added";

    if ($VehicleId == "" || $Reading == "" || $DOR == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($High > $Reading && $VehicleReadingId == "") {
        $Message = "Reading cannot less than previous reading!!";
        $Type = "error";
    } elseif ($VehicleReadingId != "" && $count2 > 0) {
        $Message = "Reading ($Reading km) cannot be updated because it is either less than previous reading or greater than next reading!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "Reading already note for this vehicle on $DOROnlyDate !!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        $DOR = strtotime($DOR);
        if ($VehicleReadingId == "")
            $query = "insert into vehiclereading(VehicleId,Reading,DOR,Remarks,VehicleReadingStatus,DOE) values('$VehicleId','$Reading','$DOR','$Remarks','Active','$DOE') ";
        else
            $query = "update vehiclereading set VehicleId='$VehicleId',Reading='$Reading',DOR='$DOR',Remarks='$Remarks',DOL='$DOL' where VehicleReadingId='$VehicleReadingId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Vehicle Reading id $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($VehicleReadingId == "")
        header("Location:Transport");
    else
        header("Location:Transport/UpdateReading/$VehicleReadingId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageStaff") {
    array_map($_POST, "FilterSqlInjection");
    $StaffPosition = $_POST['StaffPosition'];
    $StaffName = $_POST['StaffName'];
    $StaffMobile = $_POST['StaffMobile'];
    $StaffDOJ = $_POST['StaffDOJ'];
    if ($StaffDOJ != "")
        $StaffDOJTimeStamp = strtotime($StaffDOJ);
    $CheckStaffMobile = CheckMobile($StaffMobile);

    $check = mysqli_query($CONNECTION, "select StaffName from staff where StaffName='$StaffName' and StaffMobile='$StaffMobile' ");
    $count = mysqli_num_rows($check);

    if ($StaffPosition == "" || $StaffName == "" || $StaffMobile == "" || $StaffDOJ == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif (!$CheckStaffMobile) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!! $StaffMobile";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This staff is already added in the list!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $StaffDOJ = strtotime($StaffDOJ);
        $Date = strtotime($Date);
        $query = "insert into staff(StaffName,StaffMobile,StaffPosition,StaffDOJ,StaffStatus,DOE) values('$StaffName','$StaffMobile','$StaffPosition','$StaffDOJ','Active','$Date') ";
        mysqli_query($CONNECTION, $query);
        $Message = "Staff added successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:ManageStaff");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageVehicleRoute") {
    array_map($_POST, "FilterSqlInjection");
    $VehicleId = $_POST['VehicleRouteVehicleId'];
    $Route = $_POST['Route'];
    $VehicleRouteName = $_POST['VehicleRouteName'];
    $VehicleRouteId = $_POST['VehicleRouteId'];
    $RouteTo = $_POST['RouteTo'];
    $Remarks = mynl2br($_POST['Remarks']);

    if ($VehicleRouteId != "")
        $Update = " and VehicleRouteId!='$VehicleRouteId' ";
    $query1 = "select VehicleRouteStatus from vehicleroute where VehicleRouteName='$VehicleRouteName' and Session='$CURRENTSESSION' $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldVehicleRouteStatus = $row1['VehicleRouteStatus'];
            if ($OldVehicleRouteStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($VehicleRouteId != "") {
        $addupdate = "updated";
        $query2 = "select VehicleRouteStatus,Route from vehicleroute where VehicleRouteId='$VehicleRouteId' and VehicleRouteStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentVehicleRouteStatus = mysqli_num_rows($check2);
        $row2 = mysqli_fetch_array($check2);
        $OldRoute = explode(",", $row2['Route']);
    } else
        $addupdate = "added";

    $DiffRoute = array_diff($OldRoute, $Route);
    if ($DiffRoute != "") {
        foreach ($DiffRoute as $DiffRouteValue) {
            $RouteStoppageName = "";
            $RouteStudents = "";
            $query3 = "select MasterEntryValue,Students from vehicleroutedetail,masterentry where 
				VehicleRouteId='$VehicleRouteId' and 
				RouteStoppageId='$DiffRouteValue' and 
				Students!='' and
				vehicleroutedetail.RouteStoppageId=masterentry.MasterEntryId ";
            echo $query3;
            $check3 = mysqli_query($CONNECTION, $query3);
            $count3 = mysqli_num_rows($check3);
            if ($count3 > 0) {
                $row3 = mysqli_fetch_array($check3);
                $RouteStoppageName = $row3['MasterEntryValue'];
                $RouteStudents = count(explode(",", $row3['Students']));
                $StudentFoundInRouteError = 1;
                break;
            }
        }
    }

    if ($VehicleId == "" || $Route == "" || $VehicleRouteName == "" || $RouteTo == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($StudentFoundInRouteError == 1) {
        $Message = "$RouteStoppageName has $RouteStudents Students in it. To remove this stopagge, please delete that students first!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This route is already added!!";
        $Type = "error";
    } elseif ($VehicleRouteId != "" && $CurrentVehicleRouteStatus == 0) {
        $Message = "This route is deleted. You cannot update the deleted route!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Route = implode(",", $Route);
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($VehicleRouteId == "")
            $query = "insert into vehicleroute(RouteTo,VehicleId,Session,Route,VehicleRouteName,VehicleRouteRemarks,VehicleRouteStatus,DOE) values('$RouteTo','$VehicleId','$CURRENTSESSION','$Route','$VehicleRouteName','$Remarks','Active','$DOE') ";
        else
            $query = "update vehicleroute set RouteTo='$RouteTo',VehicleRouteName='$VehicleRouteName',VehicleId='$VehicleId',Route='$Route',VehicleRouteRemarks='$Remarks',DOL='$DOL' where VehicleRouteId='$VehicleRouteId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Route $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($VehicleRouteId == "")
        header("Location:TransportRoute");
    else
        header("Location:TransportRoute/UpdateRoute/$VehicleRouteId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageVehicleRouteDetail") {
    array_map($_POST, "FilterSqlInjection");
    $RouteStoppageId = isset($_POST['RouteStoppageId']) ? $_POST['RouteStoppageId'] : '';
    $AdmissionId = isset($_POST['AdmissionId']) ? $_POST['AdmissionId'] : '';
    $VehicleRouteId = isset($_POST['VehicleRouteId']) ? $_POST['VehicleRouteId'] : '';
    $VehicleRouteDetailId = isset($_POST['VehicleRouteDetailId']) ? $_POST['VehicleRouteDetailId'] : '';
    $Update = "";
    if ($VehicleRouteDetailId != "")
        $Update = " and VehicleRouteDetailId!='$VehicleRouteDetailId' ";

    $query = "select Route,RouteTo from vehicleroute where VehicleRouteId='$VehicleRouteId'";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    $row = mysqli_fetch_array($check);
    $Route = $row['Route'];
    $RouteTo = $row['RouteTo'];
    $Route = explode(",", $Route);
    $ArrayIndex = array_search($RouteStoppageId, $Route);

    $query2 = "Select VehicleRouteDetailStatus from vehicleroutedetail where VehicleRouteId='$VehicleRouteId' and RouteStoppageId='$RouteStoppageId' $Update ";
    $check2 = mysqli_query($CONNECTION, $query2);
    $count2 = mysqli_num_rows($check2);
    if ($count2 > 0) {
        $row2 = mysqli_fetch_array($check2);
        $OldVehicleRouteDetailStatus = $row2['VehicleRouteDetailStatus'];
        if ($OldVehicleRouteDetailStatus == "Deleted" && $count2 > 0)
            $count2 = 0;
        else
            $count2++;
    }

    if ($VehicleRouteDetailId != "") {
        $query3 = "select VehicleRouteDetailStatus from vehicleroutedetail where VehicleRouteDetailId='$VehicleRouteDetailId' and VehicleRouteDetailStatus='Active' ";
        $check3 = mysqli_query($CONNECTION, $query3);
        $CurrentVehicleRouteDetailStatus = mysqli_num_rows($check3);
    }

    $query41 = "select admission.AdmissionId,StudentName,FatherName,ClassName,SectionName,Mobile from registration,class,section,admission,studentfee where
			registration.RegistrationId=admission.RegistrationId and
			admission.AdmissionId=studentfee.AdmissionId and
			studentfee.Session='$CURRENTSESSION' and
			studentfee.SectionId=section.SectionId and
			class.ClassId=section.ClassId and
			studentfee.Distance!='' 
			order by StudentName,FatherName";
    $check41 = mysqli_query($CONNECTION, $query41);
    while ($row41 = mysqli_fetch_array($check41)) {
        $ListAdmissionId = $row41['AdmissionId'];
        $ListStudentName = $row41['StudentName'];
        $StudentNameArray[] = $row41['StudentName'];
        $ListFatherName = $row41['FatherName'];
        $ListClassName = $row41['ClassName'];
        $ListMobile = $row41['Mobile'];
        $MobileArray[] = $row41['Mobile'];
        $AdmissionIdArray[] = $ListAdmissionId;
        $StudentOptionArray[] = "$ListStudentName $ListFatherName $ListClassName $ListMobile";
    }

    if ($VehicleRouteDetailId == "") {
        $query4 = "select Students from vehicleroutedetail,vehicleroute where
		vehicleroutedetail.VehicleRouteId=vehicleroute.VehicleRouteId and
		vehicleroute.Session='$CURRENTSESSION' and
		RouteTo='$RouteTo' and Students!='' ";
    } else {
        $query4 = "select Students from vehicleroutedetail,vehicleroute where
		vehicleroutedetail.VehicleRouteId=vehicleroute.VehicleRouteId and
		vehicleroutedetail.VehicleRouteDetailId!='$VehicleRouteDetailId' and
		vehicleroute.Session='$CURRENTSESSION' and
		RouteTo='$RouteTo' and Students!='' ";
    }
    $check4 = mysqli_query($CONNECTION, $query4);
    while ($row4 = mysqli_fetch_array($check4)) {
        $OtherStudents = $row4['Students'];
        $OtherStudents = explode(",", $OtherStudents);
        foreach ($OtherStudents as $OtherStudentsValue)
            $StudentsInTransportRoute[] = $OtherStudentsValue;
    }


    if ($AdmissionId != "")
        foreach ($AdmissionId as $AdmissionIdValue) {
            if ($StudentsInTransportRoute != "") {
                $SearchForSavedStudent = array_search($AdmissionIdValue, $StudentsInTransportRoute);
                if ($SearchForSavedStudent === FALSE) {
                    
                } else {
                    $StudentsAlreadySavedError++;
                    $SearchForName = array_search($AdmissionIdValue, $AdmissionIdArray);
                    if ($Student == "")
                        $Student.=$StudentNameArray[$SearchForName] . " " . $MobileArray[$SearchForName];
                    else
                        $Student.=" , " . $StudentNameArray[$SearchForName] . " " . $MobileArray[$SearchForName];
                }
            }
        }

    if ($RouteStoppageId == "" || $VehicleRouteId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($AdmissionId == "" && $VehicleRouteDetailId == "") {
        $Message = "Please select students!!";
        $Type = "error";
    } elseif ($count == 0) {
        $Message = "Route doesn't exist!!";
        $Type = "error";
    } elseif ($ArrayIndex === FALSE) {
        $Message = "This stoppage doesn't belong to selected route!!";
        $Type = "error";
    } elseif ($StudentsAlreadySavedError > 0) {
        $Message = "$StudentsAlreadySavedError student(s) are already saved in other route. List are $Student!!";
        $Type = "error";
    } elseif ($count2 > 0) {
        $Message = "This route is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } elseif ($VehicleRouteDetailId != "" && $CurrentVehicleRouteDetailStatus == 0) {
        $Message = "This route is deleted. You cannot update the deleted route!!";
        $Type = "error";
    } else {
        $DOE = strtotime($Date);
        $DOL = strtotime($DOL);
        $AdmissionId = implode(",", $AdmissionId);
        if ($VehicleRouteDetailId == "") {
            $query1 = "insert into vehicleroutedetail(VehicleRouteId,RouteStoppageId,Students,DOE,VehicleRouteDetailStatus)	
			values ('$VehicleRouteId','$RouteStoppageId','$AdmissionId','$DOE','Active') ";
        } else {
            if ($AdmissionId == "") {
                $query1 = "delete from vehicleroutedetail where VehicleRouteDetailId='$VehicleRouteDetailId' ";
                $DeleteRouteStoppage = 1;
            } else {
                $query1 = "update vehicleroutedetail set RouteStoppageId='$RouteStoppageId',Students='$AdmissionId',DOL='$DOL' where 
				VehicleRouteDetailId='$VehicleRouteDetailId' ";
            }
        }
        mysqli_query($CONNECTION, $query1);
        $Message = "Saved successfully!!";
        $Type = "success";
    }

    echo $Message;
    SetNotification($Message, $Type);
    if ($VehicleRouteDetailId == "" || $DeleteRouteStoppage == 1)
        header("Location:TransportRoute/ViewRoute/$VehicleRouteId");
    else
        header("Location:TransportRoute/ViewRoute/$VehicleRouteId/UpdateRouteDetail/$VehicleRouteDetailId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ExamSetup") {
    array_map($_POST, "FilterSqlInjection");
    $ExamId = $_POST['ExamId'];
    $ExamActivityName = $_POST['ExamActivityName'];
    $ExamActivityType = $_POST['ExamActivityType'];
    $ExamDetailId = $_POST['ExamDetailId'];
    $MaximumMarks = $_POST['MaximumMarks'];
    $SubjectId = $_POST['SubjectId'];

    if ($ExamDetailId != "")
        $Update = " and ExamDetailId!='$ExamDetailId' ";
    $query1 = "select * from examdetail where ExamId='$ExamId' and ExamActivityName='$ExamActivityName' and SubjectId='$SubjectId' $Update";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldExamDetailStatus = $row1['ExamDetailStatus'];
            if ($OldExamDetailStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($ExamDetailId != "") {
        $query2 = "select ExamDetailStatus from examdetail where ExamDetailId='$ExamDetailId' and ExamDetailStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentExamDetailStatus = mysqli_num_rows($check2);
    }

    if ($ExamId == "" || $ExamActivityName == "" || $ExamActivityType == "" || $MaximumMarks == "" || $SubjectId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This activity name is already added!!";
        $Type = "error";
    } elseif ($ExamDetailId != "" && $CurrentExamDetailStatus == 0) {
        $Message = "This activity is deleted. You cannot update the deleted activity!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($ExamDetailId == "")
            $query = "insert into examdetail(ExamId,ExamActivityName,ExamActivityType,ExamDetailStatus,DOE,MaximumMarks,SubjectId) values('$ExamId','$ExamActivityName','$ExamActivityType','Active','$DOE','$MaximumMarks','$SubjectId') ";
        else
            $query = "update examdetail set ExamActivityName='$ExamActivityName',ExamActivityType='$ExamActivityType',DOL='$DOL',MaximumMarks='$MaximumMarks',SubjectId='$SubjectId' where ExamDetailId='$ExamDetailId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Exam setup saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($ExamDetailId == "")
        header("Location:ExamSetup/$ExamId");
    else
        header("Location:ExamSetup/$ExamId/UpdateExamSetup/$ExamDetailId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "MarksSetup") {
    array_map($_POST, "FilterSqlInjection");
    $ExamId = $_POST['ExamId'];
    $SubjectId = $_POST['SubjectId'];

    $query = "select ExamDetailId,ExamActivityName,MaximumMarks,SubjectName,Marks from examdetail,exam,subject where
	examdetail.ExamId=exam.ExamId and
	examdetail.ExamId='$ExamId' and
	examdetail.SubjectId='$SubjectId' and
	examdetail.SubjectId=subject.SubjectId and
	exam.Session='$CURRENTSESSION' 
	order by ExamActivityName";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($count == 0) {
        $Message = "This is not a valid link!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query1 = "select section.SectionId from exam,class,section where 
		exam.ExamId='$ExamId' and
		exam.SectionId=section.SectionId and
		class.ClassId=section.ClassId and
		exam.Session='$CURRENTSESSION' ";
        $check1 = mysqli_query($CONNECTION, $query1);
        $row1 = mysqli_fetch_array($check1);
        $SectionId = $row1['SectionId'];

        $query2 = "select registration.RegistrationId,StudentName,FatherName from registration,admission,studentfee where
		registration.RegistrationId=admission.RegistrationId and
		admission.AdmissionId=studentfee.AdmissionId and
		studentfee.Session='$CURRENTSESSION' and
		studentfee.SectionId='$SectionId' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        while ($row2 = mysqli_fetch_array($check2)) {
            $RegistrationId = $row2['RegistrationId'];
            $RegistrationIdArray[] = $RegistrationId;
        }

        while ($row = mysqli_fetch_array($check)) {
            $FinalMarks = "";
            $MaximumMarks = $row['MaximumMarks'];
            $ExamDetailId = $row['ExamDetailId'];
            foreach ($RegistrationIdArray as $RegistrationIdArrayValues) {
                $FieldName = "Field_" . $RegistrationIdArrayValues . "_" . $ExamDetailId;
                $MO = $_POST[$FieldName];
                if ($MO <= $MaximumMarks)
                    $FinalMarks.="$RegistrationIdArrayValues-$MO,";
            }
            $Length = strlen($FinalMarks);
            $FinalMarks = substr($FinalMarks, 0, ($Length - 1));
            $query3 = "update examdetail set Marks='$FinalMarks' where ExamDetailId='$ExamDetailId' ";
            mysqli_query($CONNECTION, $query3);
        }
        $Message = "Marks saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:MarksSetup/$ExamId/$SubjectId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageCall") {
    array_map($_POST, "FilterSqlInjection");
    $Name = $_POST['Name'];
    $Landline = $_POST['Landline'];
    $CallResponse = $_POST['CallResponse'];
    $Address = mynl2br($_POST['Address']);
    $ResponseDetail = mynl2br($_POST['ResponseDetail']);
    $Mobile = $_POST['Mobile'];
    $DOC = $_POST['DOC'];
    $FollowUpDate = $_POST['FollowUpDate'];
    $CallId = $_POST['CallId'];
    $NoOfChild = $_POST['NoOfChild'];

    if ($CallId != "") {
        $Already = "and CallId!='$CallId' and CallStatus='Active'";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select CallId from call where Name='$Name' and Mobile='$Mobile' $Already ");
    $count = mysqli_num_rows($check);

    if ($Name == "" || $ResponseDetail == "" || $CallResponse == "" || $DOC == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This call is already added!!";
        $Type = "error";
    } elseif ($Mobile != "" && (!is_numeric($Mobile) || strlen($Mobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($Landline != "" && (!is_numeric($Landline) || strlen($Landline) != $LANDLINENUMBERDIGIT)) {
        $Message = "Landline should be $LANDLINENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($Landline == "" && $Mobile == "") {
        $Message = "Please enter either Mobile or Landline Number!!";
        $Type = "error";
    } elseif (!is_numeric($NoOfChild)) {
        $Message = "No Of Child can only be numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $FollowUpDate = strtotime($FollowUpDate);
        $DOC = strtotime($DOC);
        if ($CallId == "") {
            $query = "insert into calling(FollowUpDate,Landline,Name,Address,CallResponse,ResponseDetail,Mobile,DOC,DOE,NoOfChild,CallStatus) values
		('$FollowUpDate','$Landline','$Name','$Address','$CallResponse','$ResponseDetail','$Mobile','$DOC','$Date','$NoOfChild','Active') ";
        } else {
            $query = "update calling set FollowUpDate='$FollowUpDate',Landline='$Landline',Name='$Name',Address='$Address',CallResponse='$CallResponse',
				ResponseDetail='$ResponseDetail',Mobile='$Mobile',DOC='$DOC',DLU='$Date',NoOfChild='$NoOfChild'
				 where CallId='$CallId' and CallStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Call $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($CallId == "")
        header("Location:Call");
    else
        header("Location:Call/Update/$CallId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageOCall") {
    array_map($_POST, "FilterSqlInjection");
    $Name = $_POST['Name'];
    $CallDuration = $_POST['CallDuration'];
    $Landline = $_POST['Landline'];
    $Remarks = mynl2br($_POST['Remarks']);
    $Mobile = $_POST['Mobile'];
    $DOC = $_POST['DOC'];
    $FollowUpDate = $_POST['FollowUpDate'];
    $CallId = $_POST['CallId'];

    if ($CallId != "") {
        $Already = "and OCallId!='$CallId' and CallStatus='Active'";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select OCallId from ocall where Name='$Name' and Mobile='$Mobile' $Already ");
    $count = mysqli_num_rows($check);

    if ($Name == "" || $DOC == "" || $CallDuration == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This call is already added!!";
        $Type = "error";
    } elseif ($Mobile != "" && (!is_numeric($Mobile) || strlen($Mobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($Landline != "" && (!is_numeric($Landline) || strlen($Landline) != $LANDLINENUMBERDIGIT)) {
        $Message = "Landline should be $LANDLINENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($Landline == "" && $Mobile == "") {
        $Message = "Please enter either Mobile or Landline Number!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $FollowUpDate = strtotime($FollowUpDate);
        $DOC = strtotime($DOC);
        if ($CallId == "") {
            $query = "insert into ocalling(FollowUpDate,Landline,Name,Remarks,Mobile,DOC,DOE,CallStatus,CallDuration) values
		('$FollowUpDate','$Landline','$Name','$Remarks','$Mobile','$DOC','$Date','Active','$CallDuration') ";
        } else {
            $query = "update ocalling set CallDuration='$CallDuration',FollowUpDate='$FollowUpDate',Landline='$Landline',Name='$Name',Remarks='$Remarks',Mobile='$Mobile',DOC='$DOC',DLU='$Date'
				 where OCallId='$CallId' and CallStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Call $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($CallId == "")
        header("Location:OCall");
    else
        header("Location:OCall/Update/$CallId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageFollowUp") {
    array_map($_POST, "FilterSqlInjection");
    $ResponseDetail = mynl2br($_POST['ResponseDetail']);
    $Remarks = mynl2br($_POST['Remarks']);
    $DOF = $_POST['DOF'];
    $NextFollowUpDate = $_POST['NextFollowUpDate'];
    $FollowUpId = $_POST['FollowUpId'];
    $FollowUpType = $_POST['FollowUpType'];
    $FollowUpUniqueId = $_POST['FollowUpUniqueId'];
    if ($NextFollowUpDate != "")
        $NextFollowUpDate = strtotime($NextFollowUpDate);
    if ($DOF != "")
        $DOF = strtotime($DOF);

    if ($FollowUpType == "Call") {
        $table = "calling";
        $field = "CallId";
    } elseif ($FollowUpType == "Enquiry") {
        $table = "enquiry";
        $field = "EnquiryId";
    }

    $query = "select $field from $table where $field='$FollowUpUniqueId' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($ResponseDetail == "" || $DOF == "" || $FollowUpType == "" || $FollowUpUniqueId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count == 0) {
        $Message = "This is not a valid URL!!";
        $Type = "error";
    } elseif ($NextFollowUpDate != "" && $DOF > $NextFollowUpDate) {
        $Message = "Follow Up date should be greater than Date of Calling!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($FollowUpId == "") {
            $MessageButton = "added";
            $query = "insert into followup(FollowUpStatus,FollowUpType,FollowUpUniqueId,ResponseDetail,Remarks,NextFollowUpDate,DOF) values
		('Active','$FollowUpType','$FollowUpUniqueId','$ResponseDetail','$Remarks','$NextFollowUpDate','$DOF') ";
        } else {
            $MessageButton = "updated";
            $query = "update followup set ResponseDetail='$ResponseDetail',Remarks='$Remarks',NextFollowUpDate='$NextFollowUpDate',DOF='$DOF'
			 where FollowUpId='$FollowUpId' and FollowUpStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Follow $MessageButton successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($FollowUpId == "")
        header("Location:FollowUp/$FollowUpType/$FollowUpUniqueId");
    else
        header("Location:FollowUp/$FollowUpType/$FollowUpUniqueId/Update/$FollowUpId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageCalendar") {
    array_map($_POST, "FilterSqlInjection");
    $StartTime = $_POST['StartTime'];
    $EndTime = $_POST['EndTime'];
    $Color = $_POST['Color'];
    $Title = $_POST['Title'];
    $StartTimeStamp = strtotime($StartTime);
    $EndTimeStamp = strtotime($EndTime);
    $CalendarId = $_POST['CalendarId'];

    if ($CalendarId != "")
        $Update = " and CalendarId!='$CalendarId' ";
    $query1 = "select * from calendar where Title='$Title' and StartTime='$StartTimeStamp' and EndTime='$EndTimeStamp' and Username='$USERNAME' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $CalendarUsername = $row1['Username'];
            $OldCalendarStatus = $row1['CalendarStatus'];
            if ($OldCalendarStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($CalendarId != "") {
        $addupdate = "updated";
        $query2 = "select CalendarStatus from calendar where CalendarId='$CalendarId' and CalendarStatus='Active' and Username='$USERNAME' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentCalendarStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($StartTime == "" || $EndTime == "" || $Color == "" || $Title == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($CalendarId != "" && $CurrentCalendarStatus == 0) {
        $Message = "This calendar is deleted. You cannot update the deleted calendar!!";
        $Type = "error";
    } elseif ($StartTimeStam > $EndTimeStamp) {
        $Message = "Start Time cannot be less that End Time!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This calendar is already added!!";
        $Type = "error";
    } elseif ($count1 > 0 && $CalendarUsername != '$USERNAME') {
        $Message = "You cannot edit other's calendar!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DateTimeStamp = strtotime($Date);
        if ($CalendarId == "")
            $query = "insert into calendar(Title,Username,StartTime,EndTime,Color,Date,CalendarStatus) values('$Title','$USERNAME','$StartTimeStamp','$EndTimeStamp','$Color','$DateTimeStamp','Active') ";
        else
            $query = "update calendar set Title='$Title',StartTime='$StartTimeStamp',EndTime='$EndTimeStamp',Color='$Color',DLU='$DateTimeStamp' where CalendarId='$CalendarId' and Username='$USERNAME' and CalendarStatus='Active' ";

        mysqli_query($CONNECTION, $query);
        $Message = "Calendar $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($CalendarId == "")
        header("Location:Calendar");
    else
        header("Location:Calendar/Update/$CalendarId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageCircular") {
    array_map($_POST, "FilterSqlInjection");
    $Title = $_POST['Title'];
    $Circular = $_POST['Circular'];
    $CircularId = $_POST['CircularId'];

    if ($CircularId != "")
        $Update = " and CircularId!='$CircularId' ";
    if ($USERNAME != "masteruser" && $USERNAME != 'webmaster')
        $UsernameQuery = " and Username='$USERNAME' ";
    $query1 = "select * from circular where Title='$Title' $UsernameQuery $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $CircularUsername = $row1['Username'];
            $OldCircularStatus = $row1['CircularStatus'];
            if ($OldCircularStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($CircularId != "") {
        $addupdate = "updated";
        $query2 = "select CircularStatus from circular where CircularId='$CircularId' and CircularStatus='Active' $UsernameQuery ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentCircularStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($Circular == "" || $Title == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($CircularId != "" && $CurrentCircularStatus == 0) {
        $Message = "This circular is deleted. You cannot update the deleted circular!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This circular is already added!!";
        $Type = "error";
    } elseif ($count1 > 0 && $CircularUsername != '$USERNAME') {
        $Message = "You cannot edit other's circular!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if (!isset($NAME))
            $NAME = "Admin";
        $DateReleased = strtotime($Date);
        if ($CircularId == "")
            $query = "insert into circular(Title,Username,DateReleased,Circular,CircularStatus) values('$Title','$USERNAME','$DateReleased','$Circular','Active') ";
        else
            $query = "update circular set Title='$Title',Circular='$Circular' where CircularId='$CircularId' and CircularStatus='Active' $UsernameQuery ";

        mysqli_query($CONNECTION, $query);
        $Message = "Circular $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($CircularId == "")
        header("Location:Circular");
    else
        header("Location:Circular/Update/$CircularId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageEnquiry") {
    array_map($_POST, "FilterSqlInjection");
    $EnquiryType = $_POST['EnquiryType'];
    $Reference = $_POST['Reference'];
    $Name = $_POST['Name'];
    $EnquiryResponse = $_POST['EnquiryResponse'];
    $Address = mynl2br($_POST['Address']);
    $ResponseDetail = mynl2br($_POST['ResponseDetail']);
    $Mobile = $_POST['Mobile'];
    $AlternateMobile = $_POST['AlternateMobile'];
    $EnquiryDate = $_POST['EnquiryDate'];
    $EnquiryId = $_POST['EnquiryId'];
    $NoOfChild = $_POST['NoOfChild'];

    if ($EnquiryId != "") {
        $Already = "and EnquiryId!='$EnquiryId' ";
        $MessageButton = "updated";
    } else
        $MessageButton = "added";
    $check = mysqli_query($CONNECTION, "select EnquiryId from enquiry where Name='$Name' and Mobile='$Mobile' $Already ");
    $count = mysqli_num_rows($check);

    if ($EnquiryType == "" || $Reference == "" || $Name == "" || $Address == "" || $ResponseDetail == "" || $Mobile == "" || $EnquiryResponse == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This enquiry is already added!!";
        $Type = "error";
    } elseif (!is_numeric($Mobile) || strlen($Mobile) != $MOBILENUMBERDIGIT) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($AlternateMobile != "" && (!is_numeric($AlternateMobile) || strlen($AlternateMobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Alternate Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif (!is_numeric($NoOfChild)) {
        $Message = "No Of Child can only be numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $EnquiryDate = strtotime($EnquiryDate);
        if ($EnquiryId == "") {
            $query = "insert into enquiry(EnquiryStatus,EnquiryType,Reference,Name,Address,EnquiryResponse,ResponseDetail,Mobile,AlternateMobile,EnquiryDate,DOE,NoOfChild) values
		('Active','$EnquiryType','$Reference','$Name','$Address','$EnquiryResponse','$ResponseDetail','$Mobile','$AlternateMobile','$Date','$Date','$NoOfChild') ";
        } else {
            $query = "update enquiry set EnquiryType='$EnquiryType',Reference='$Reference',Name='$Name',Address='$Address',EnquiryResponse='$EnquiryResponse',
				ResponseDetail='$ResponseDetail',Mobile='$Mobile',AlternateMobile='$AlternateMobile',EnquiryDate='$EnquiryDate',DLU='$Date',NoOfChild='$NoOfChild'
				 where EnquiryId='$EnquiryId' ";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Enquiry $MessageButton successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($EnquiryId == "")
        header("Location:Enquiry");
    else
        header("Location:Enquiry/Update/$EnquiryId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageStudentProfile") {
    array_walk($_POST, "FilterSqlInjection");

    $StudentName = $_POST['UpdateStudentName'];
    $FatherName = $_POST['UpdateFatherName'];
    $MotherName = $_POST['UpdateMotherName'];
    $SSSMID = trim($_POST['SSSMID']);
    $Family_SSSMID = trim($_POST['Family_SSSMID']);
    $Aadhar_No = trim($_POST['Aadhar_No']);
    $Bank_Account_Number = trim($_POST['Bank_Account_Number']);
    $IFSC_Code = trim($_POST['IFSC_Code']);
    $RegistrationId = $_POST['RegistrationId'];
    $DOR = $_POST['UpdateDOR'];
    $DOB = $_POST['UpdateDOB'];
    $BloodGroup = $_POST['UpdateBloodGroup'];
    $Caste = $_POST['UpdateCaste'];
    $Category = $_POST['UpdateCategory'];
    $Gender = $_POST['UpdateGender'];
    $SectionId = $_POST['StudentSectionId'];



    $check = mysqli_query($CONNECTION, "select StudentName,Status from registration where StudentName='$StudentName' and FatherName='$FatherName' and Class='$Class' and Session='$Session' and RegistrationId!='$RegistrationId' ");
    $count = mysqli_num_rows($check);

    $check1 = mysqli_query($CONNECTION, "select Status from registration where RegistrationId='$RegistrationId' and registration.Session='$CURRENTSESSION' ");
    $row1 = mysqli_fetch_array($check1);
    $Status = $row1['Status'];

    $check2 = mysqli_query($CONNECTION, "select RegistrationId from admission where RegistrationId='$RegistrationId' ");
    $count2 = mysqli_num_rows($check2);

    if ($StudentName == "" || $DOR == "" || $FatherName == "" || $MotherName == "" || $SSSMID == "" || $Family_SSSMID == "" || $Aadhar_No == "" || $Bank_Account_Number == "" || $IFSC_Code == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This student is already registered in same class!!";
        $Type = "error";
    } elseif ($Status == "Deleted") {
        $Message = "This student is deleted, you cannot update his/her detail!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOR = strtotime($DOR);
        $DOB = strtotime($DOB);
        $DOE = strtotime($Date);
        $query1 = "update registration set StudentName='$StudentName',BloodGroup='$BloodGroup',Caste='$Caste',Category='$Category',DOB='$DOB',DOR='$DOR',FatherName='$FatherName',MotherName='$MotherName',SSSMID='$SSSMID',Family_SSSMID='$Family_SSSMID',Aadhar_No='$Aadhar_No',Bank_Account_Number='$Bank_Account_Number',IFSC_Code='$IFSC_Code',Gender='$Gender' where RegistrationId='$RegistrationId' ";
        mysqli_query($CONNECTION, $query1);
        if ($count2 == 0)
            mysqli_query($CONNECTION, "update registration set SectionId='$SectionId' where RegistrationId='$RegistrationId' ");
        $Message = "Updated successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Registration/$RegistrationId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageStudentContact") {
    array_map($_POST, "FilterSqlInjection");
    $Mobile = $_POST['Mobile'];
    $Landline = $_POST['Landline'];
    $AlternateMobile = $_POST['AlternateMobile'];
    $FatherMobile = $_POST['FatherMobile'];
    $MotherMobile = $_POST['MotherMobile'];
    $RegistrationId = $_POST['RegistrationId'];
    $PresentAddress = mynl2br($_POST['PresentAddress']);
    $PermanentAddress = mynl2br($_POST['PermanentAddress']);

    if ($Mobile == "" || !is_numeric($Mobile) || strlen($Mobile) != $MOBILENUMBERDIGIT) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($Landline != "" && (!is_numeric($Landline) || strlen($Landline) != $LANDLINENUMBERDIGIT)) {
        $Message = "Landline should be $LANDLINENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($AlternateMobile != "" && (!is_numeric($AlternateMobile) || strlen($AlternateMobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Alternate Mobile should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($FatherMobile != "" && (!is_numeric($FatherMobile) || strlen($FatherMobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Father Mobile should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($MotherMobile != "" && (!is_numeric($MotherMobile) || strlen($MotherMobile) != $MOBILENUMBERDIGIT)) {
        $Message = "Mother Mobile should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query1 = "update registration set Mobile='$Mobile',Landline='$Landline',AlternateMobile='$AlternateMobile',
		PresentAddress='$PresentAddress',PermanentAddress='$PermanentAddress',FatherMobile='$FatherMobile',MotherMobile='$MotherMobile' where RegistrationId='$RegistrationId' ";
        mysqli_query($CONNECTION, $query1);
        $Message = "Updated successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Registration/$RegistrationId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageParentsContact") {
    array_map($_POST, "FilterSqlInjection");
    $RegistrationId = $_POST['RegistrationId'];
    $FatherDateOfBirth = $_POST['FatherDateOfBirth'];
    $FatherEmail = $_POST['FatherEmail'];
    $FatherQualification = $_POST['FatherQualification'];
    $FatherOccupation = $_POST['FatherOccupation'];
    $FatherDesignation = $_POST['FatherDesignation'];
    $FatherOrganization = $_POST['FatherOrganization'];
    $MotherDateOfBirth = $_POST['MotherDateOfBirth'];
    $MotherEmail = $_POST['MotherEmail'];
    $MotherQualification = $_POST['MotherQualification'];
    $MotherOccupation = $_POST['MotherOccupation'];
    $MotherDesignation = $_POST['MotherDesignation'];
    $MotherOrganization = $_POST['MotherOrganization'];

    $query1 = "update registration set 
	FatherDateOfBirth='$FatherDateOfBirth',
	FatherEmail='$FatherEmail',
	FatherQualification='$FatherQualification',
	FatherOccupation='$FatherOccupation',
	FatherDesignation='$FatherDesignation',
	FatherOrganization='$FatherOrganization',
	MotherDateOfBirth='$MotherDateOfBirth',
	MotherEmail='$MotherEmail',
	MotherQualification='$MotherQualification',
	MotherOrganization='$MotherOrganization',
	MotherDesignation='$MotherDesignation',
	MotherOccupation='$MotherOccupation' 
	where RegistrationId='$RegistrationId' ";
    mysqli_query($CONNECTION, $query1);
    $Message = "Updated successfully!!";
    $Type = "success";

    SetNotification($Message, $Type);
    header("Location:Registration/$RegistrationId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageQualification") {
    array_map($_POST, "FilterSqlInjection");
    $QualificationType = $_POST['QualificationType'];
    $UniqueId = $_POST['UniqueId'];
    $BoardUniversity = $_POST['BoardUniversity'];
    $Class = $_POST['Class'];
    $Year = $_POST['Year'];
    $Marks = $_POST['Marks'];
    $Remarks = mynl2br($_POST['Remarks']);

    if ($QualificationType == "Student")
        $query = "Select RegistrationId from registration where RegistrationId='$UniqueId' ";
    else
        $query = "select StaffId from staff where StaffId='$UniqueId' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($QualificationType == "" || $UniqueId == "" || $BoardUniversity == "" || $Class == "" || $Year == "" || $Marks == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query1 = "insert into qualification(Type,UniqueId,BoardUniversity,Class,Year,Marks,Remarks) values
			('$QualificationType','$UniqueId','$BoardUniversity','$Class','$Year','$Marks','$Remarks') ";
        mysqli_query($CONNECTION, $query1);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($QualificationType == "Student")
        header("Location:Registration/$UniqueId");
    else
        header("Location:ManageStaff/$UniqueId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSiblingInformation") {
    array_map($_POST, "FilterSqlInjection");
    $SName = $_POST['SName'];
    $SDOB = $_POST['SDOB'];
    $SClass = $_POST['SClass'];
    $SSchool = $_POST['SSchool'];
    $RegistrationId = $_POST['RegistrationId'];
    $SRemarks = mynl2br($_POST['SRemarks']);

    $query = "Select RegistrationId from registration where RegistrationId='$RegistrationId' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($SName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $SDOB = strtotime($SDOB);
        $query1 = "insert into sibling(SName,SDOB,SClass,SSchool,RegistrationId,SRemarks) values
			('$SName','$SDOB','$SClass','$SSchool','$RegistrationId','$SRemarks') ";
        mysqli_query($CONNECTION, $query1);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Registration/$RegistrationId");
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManagePhotos") {
    array_map($_POST, "FilterSqlInjection");
    $Title = $_POST['Title'];
    $Detail = $_POST['Detail'];
    $Resolution = $_POST['Resolution'];
    $Document = $_POST['Document'];
    $UniqueId = $_POST['UniqueId'];
    $filename = $_FILES['file']['name'];

    if ($Detail == "StudentDocuments") {
        $tablename = "registration";
        $fieldname = "RegistrationId";
    } else {
        $tablename = "staff";
        $fieldname = "StaffId";
    }

    $check = mysqli_query($CONNECTION, "select * from $tablename where $fieldname='$UniqueId' ");
    $count = mysqli_num_rows($check);

    $check1 = mysqli_query($CONNECTION, "select Title from photos where Title='$Title' and Detail='$Detail' and UniqueId='$UniqueId' ");
    $count1 = mysqli_num_rows($check1);

    if ($Detail == "" || $UniqueId == "" || $filename == "" || $Title == "" || $Resolution == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "Title already exists!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $SearchIndex = array_search($Resolution, $MasterEntryIdArray);
        $ResolutionArray = $MasterEntryValueArray[$SearchIndex];
        $ResolutionArray = explode("x", $ResolutionArray);
        $Height = $ResolutionArray[1];
        $Width = $ResolutionArray[0];
        $allowed_ext = "jpg,png,bmp,gif,JPG";
        $max_size = "10000000";
        $extension = pathinfo($_FILES['file']['name']);
        $extension = $extension[extension];
        $allowed_paths = explode(",", $allowed_ext);
        for ($i = 0; $i < count($allowed_paths); $i++) {
            if ($allowed_paths[$i] == "$extension") {
                if ($_FILES['file']['size'] < $max_size)
                    $ok = "1";
                else
                    $ok = "greater";
            }
        }

        if ($ok == 1) {
            $TitleSEO = $Title . "-" . $Detail . "-" . $UniqueId;
            $TitleSEO = preg_replace("/[^A-Za-z0-9\- ]/", "", $TitleSEO);
            $TitleSEO = preg_replace("/ /", "-", $TitleSEO);
            $fileName = $_FILES['file']['name'];
            $newname = "$TitleSEO.$extension";
            $thumbnail = "thumbnail-$TitleSEO.$extension";
            if (file_exists("$PHOTOPATH/$newname"))
                unlink("$PHOTOPATH/$newname");
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], $PHOTOPATH . '/' . $_FILES['file']['name']);
                $rename = rename("$PHOTOPATH/$fileName", "$PHOTOPATH/$newname");
                include('ImageResize.php');
                list($width, $height, $type, $attr) = getimagesize("$PHOTOPATH/$newname");
                $image = new SimpleImage();
                $image->load("$PHOTOPATH/$newname");
                if ($width > $height)
                    $image->resizeToWidth($Width);
                else
                    $image->resizeToHeight($Height);
                $image->save("$PHOTOPATH/$newname");

                $image->load("$PHOTOPATH/$newname");
                $image->resizeToWidth(150);
                $image->save("$PHOTOPATH/$thumbnail");
                $DOE = strtotime($Date);
                $Path = $newname;
                $query3 = "insert into photos(Title,Document,Path,Detail,UniqueId,DOE) values('$Title','$Document','$Path','$Detail','$UniqueId','$DOE') ";
                mysqli_query($CONNECTION, $query3);
                $Message = "Photo Uploaded!!!";
                $Type = "success";
            }
        }
        else {
            $Message = "Wrong Image Format or Image size is greater than 10MB!!!";
            $Type = "error";
        }
    }
    SetNotification($Message, $Type);
    if ($Detail == "StudentDocuments")
        header("Location:Registration/$UniqueId");
    else
        header("Location:ManageStaff/$UniqueId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageDRRegister") {
    array_map($_POST, "FilterSqlInjection");
    $Reference = $_POST['Reference'];
    $Title = $_POST['Title'];
    $D = $_POST['D'];
    $DRType = $_POST['DRType'];
    $Address = mynl2br($_POST['Address']);
    $Remarks = mynl2br($_POST['Remarks']);
    $Id = $_POST['Id'];

    if ($Id != "")
        $Update = " and Id!='$Id' ";
    $query1 = "select * from drregister where Title='$Title' and Reference='$Reference' and DRType='$DRType' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldDRStatus = $row1['DRStatus'];
            if ($OldDRStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($Id != "") {
        $addupdate = "updated";
        $query2 = "select DRStatus from drregister where Id='$Id' and DRStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentDRStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($Title == "" || $Reference == "" || $D == "" || $DRType == "" || $Address == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This call is already added!!";
        $Type = "error";
    } elseif ($Id != "" && $CurrentDRStatus == 0) {
        $Message = "This list is deleted. You cannot update the deleted list!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOE = strtotime($Date);
        $DOL = strtotime($Date);
        $Date = strtotime($D);
        if ($DRType == "Dispatch")
            $AddressTo = $Address;
        else
            $AddressFrom = $Address;
        if ($Id == "") {
            $query = "insert into drregister(Reference,Title,AddressTo,AddressFrom,Date,Remarks,DRType,DOE,DRStatus) values
		('$Reference','$Title','$AddressTo','$AddressFrom','$Date','$Remarks','$DRType','$DOE','Active') ";
        } else {
            $query = "update drregister set Reference='$Reference',Title='$Title',AddressTo='$AddressTo',AddressFrom='$AddressFrom',Remarks='$Remarks',
				Date='$Date',DOL='$Date'
				 where Id='$Id' and DRStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($Id == "")
        header("Location:DR/$DRType");
    else
        header("Location:DR/$DRType/Update/$Id");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageComplaint") {
    array_map($_POST, "FilterSqlInjection");
    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $DOC = $_POST['DOC'];
    $Description = $_POST['Description'];
    $Action = $_POST['Act'];
    $ComplaintId = $_POST['ComplaintId'];
    $ComplaintType = $_POST['ComplaintType'];
    $Resolved = $_POST['Resolved'];

    if ($ComplaintId != "")
        $Update = " and ComplaintId!='$ComplaintId' ";
    $query1 = "select * from complaint where Name='$Name' and Mobile='$Mobile' and Description='$Description' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldComplaintStatus = $row1['ComplaintStatus'];
            if ($OldComplaintStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($ComplaintId != "") {
        $addupdate = "updated";
        $query2 = "select ComplaintStatus from complaint where ComplaintId='$ComplaintId' and ComplaintStatus!='Deleted' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentComplaintStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($Name == "" || $Mobile == "" || $DOC == "" || $Description == "" || $ComplaintType == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($ComplaintId != "" && $CurrentComplaintStatus == 0) {
        $Message = "This complaint is deleted. You cannot update the deleted complaint!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This complaint is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($Resolved == "Yes")
            $ComplaintStatus = "Resolved";
        else
            $ComplaintStatus = "Fresh";

        $DateTimeStamp = strtotime($Date);
        $DOC = strtotime($DOC);
        $DOE = strtotime($Date);
        if ($ComplaintId == "")
            $query = "insert into complaint(ComplaintType,Name,Mobile,Description,Action,DOC,DOE,DOEUsername,ComplaintStatus) values('$ComplaintType','$Name','$Mobile','$Description','$Action','$DOC','$DOE','$USERNAME','Fresh') ";
        else
            $query = "update complaint set ComplaintStatus='$ComplaintStatus',ComplaintType='$ComplaintType',Name='$Name',Mobile='$Mobile',Description='$Description',Action='$Action',DOL='$DateTimeStamp',DOLUsername='$USERNAME',DOC='$DOC' where ComplaintId='$ComplaintId' ";

        mysqli_query($CONNECTION, $query);
        $Message = "Complaint $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($ComplaintId == "")
        header("Location:Complaint");
    else
        header("Location:Complaint/Update/$ComplaintId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageVisitorBook") {
    array_map($_POST, "FilterSqlInjection");
    $Name = $_POST['Name'];
    $Mobile = $_POST['Mobile'];
    $InDateTime = $_POST['InDateTime'];
    $Description = $_POST['Description'];
    $NoOfPeople = $_POST['NoOfPeople'];
    $OutDateTime = $_POST['OutDateTime'];
    $VisitorBookId = $_POST['VisitorBookId'];
    $Purpose = $_POST['Purpose'];

    if ($VisitorBookId != "")
        $Update = " and VisitorBookId!='$VisitorBookId' ";
    $query1 = "select * from visitorbook where Name='$Name' and Mobile='$Mobile' and InDateTime='$InDateTime' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldVisitorBookStatus = $row1['VisitorBookStatus'];
            if ($OldVisitorBookStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($VisitorBookId != "") {
        $addupdate = "updated";
        $query2 = "select VisitorBookStatus from visitorbook where VisitorBookId='$VisitorBookId' and VisitorBookStatus!='Deleted' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentVisitorBookStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($Name == "" || $Mobile == "" || $InDateTime == "" || $Purpose == "" || $NoOfPeople == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($VisitorBookId != "" && $CurrentVisitorBookStatus == 0) {
        $Message = "This record is deleted. You cannot update the deleted record!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This record is already added!!";
        $Type = "error";
    } elseif ($NoOfPeople < 1) {
        $Message = "No of people should be greater than 0!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {

        $DateTimeStamp = strtotime($Date);
        $InDateTime = strtotime($InDateTime);
        $OutDateTime = strtotime($OutDateTime);
        $DOE = strtotime($Date);
        if ($VisitorBookId == "")
            $query = "insert into visitorbook(NoOfPeople,Purpose,Name,Mobile,Description,InDateTime,OutDateTime,DOE,DOEUsername,VisitorBookStatus) values('$NoOfPeople','$Purpose','$Name','$Mobile','$Description','$InDateTime','$OutDateTime','$DOE','$USERNAME','Active') ";
        else
            $query = "update visitorbook set NoOfPeople='$NoOfPeople',Purpose='$Purpose',InDateTime='$InDateTime',Name='$Name',Mobile='$Mobile',Description='$Description',OutDateTime='$OutDateTime',DOL='$DateTimeStamp',DOLUsername='$USERNAME' where VisitorBookId='$VisitorBookId' ";

        mysqli_query($CONNECTION, $query);
        $Message = "Record $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($VisitorBookId == "")
        header("Location:VisitorBook");
    else
        header("Location:VisitorBook/Update/$VisitorBookId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageStaffProfile") {
    array_map($_POST, "FilterSqlInjection");
    $StaffPosition = $_POST['StaffPositionDetail'];
    $StaffName = $_POST['StaffNameDetail'];
    $StaffMobile = $_POST['StaffMobileDetail'];
    $CheckStaffMobile = CheckMobile($StaffMobile);
    $StaffDOJ = $_POST['StaffDOJDetail'];
    $StaffDOJTimeStamp = strtotime($StaffDOJ);
    $StaffDOB = $_POST['StaffDOBDetail'];
    $StaffStatus = $_POST['StaffStatus'];
    $StaffEmail = $_POST['StaffEmailDetail'];
    $StaffAlternateMobile = $_POST['StaffAlternateMobileDetail'];
    $CheckStaffAlternateMobile = CheckMobile($StaffAlternateMobile);
    $StaffPresentAddress = mynl2br($_POST['StaffPresentAddressDetail']);
    $StaffPermanentAddress = mynl2br($_POST['StaffPermanentAddressDetail']);
    $StaffId = $_POST['StaffId'];
    if ($StaffStatus == "Yes")
        $StaffStatus = "Active";
    else
        $StaffStatus = "";

    $check = mysqli_query($CONNECTION, "select StaffName from staff where StaffName='$StaffName' and StaffMobile='$StaffMobile' and StaffId!='$StaffId' ");
    $count = mysqli_num_rows($check);

    if ($StaffPosition == "" || $StaffName == "" || $StaffMobile == "" || $StaffDOJ == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif (!$CheckStaffMobile) {
        $Message = "Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif (!$CheckStaffAlternateMobile && $StaffAlternateMobile != "") {
        $Message = "Alternate Mobile number should be $MOBILENUMBERDIGIT digit numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This staff is already added in the list!!";
        $Type = "error";
    } else {
        $StaffDOJ = strtotime($StaffDOJ);
        $StaffDOB = strtotime($StaffDOB);
        $Date = strtotime($Date);
        $query = "update staff set StaffEmail='$StaffEmail',StaffName='$StaffName',StaffMobile='$StaffMobile',StaffAlternateMobile='$StaffAlternateMobile',StaffPosition='$StaffPosition',
				StaffStatus='$StaffStatus',StaffDOJ='$StaffDOJ',StaffDOB='$StaffDOB',StaffAlternateMobile='$StaffAlternateMobile',StaffPresentAddress='$StaffPresentAddress',
				StaffPermanentAddress='$StaffPermanentAddress',DLU='$Date' where StaffId='$StaffId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Staff profile updated successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:ManageStaff/$StaffId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSalaryHead") {
    array_map($_POST, "FilterSqlInjection");
    $SalaryHead = $_POST['SalaryHead'];
    $Code = $_POST['Code'];
    $SalaryHeadId = $_POST['SalaryHeadId'];
    $SalaryHeadType = $_POST['SalaryHeadType'];
    $SalaryHeadStatus = $_POST['SalaryHeadStatus'];
    $DailyBasis = $_POST['DailyBasis'];
    if ($DailyBasis != 1)
        $DailyBasis = 0;

    if ($SalaryHeadId != "")
        $Update = " and SalaryHeadId!='$SalaryHeadId' ";
    $query1 = "select * from salaryhead where (SalaryHead='$SalaryHead' or Code='$Code') $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSalaryHeadStatus = $row1['SalaryHeadStatus'];
            if ($OldSalaryHeadStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SalaryHeadId != "") {
        $addupdate = "updated";
        $query2 = "select SalaryHeadStatus from salaryhead where SalaryHeadId='$SalaryHeadId' and (SalaryHeadStatus='Active' or SalaryHeadStatus='InActive') ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSalaryHeadStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    $query3 = "select Code from salaryhead where SalaryHeadStatus='Active' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    while ($row3 = mysqli_fetch_array($check3))
        $SalaryCodeArray[] = $row3['Code'];

    foreach ($SalaryCodeArray as $SalaryCodeArrayValue) {
        if ($Code != $SalaryCodeArrayValue) {
            $CheckforDuplicateCode = strpos($SalaryCodeArrayValue, $Code);
            if ($CheckforDuplicateCode === FALSE) {
                
            } else
                $CodeAlreadyExists = 1;
        }
    }

    if ($SalaryHead == "" || $Code == "" || $SalaryHeadType == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($CodeAlreadyExists == 1) {
        $Message = "This code is already included in some other salary head!! Use different code for this salary head!!";
        $Type = "error";
    } elseif ($SalaryHeadId != "" && $CurrentSalaryHeadStatus == 0) {
        $Message = "This salary head is deleted. You cannot update the deleted salary head!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This salary head is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DateTimeStamp = strtotime($Date);
        if ($SalaryHeadId == "")
            $query = "insert into salaryhead(SalaryHead,Code,SalaryHeadStatus,DailyBasis,SalaryHeadType) values('$SalaryHead','$Code','Active','$DailyBasis','$SalaryHeadType') ";
        else
            $query = "update salaryhead set SalaryHead='$SalaryHead',Code='$Code',SalaryHeadStatus='$SalaryHeadStatus',DailyBasis='$DailyBasis',SalaryHeadType='$SalaryHeadType' where SalaryHeadId='$SalaryHeadId' ";

        mysqli_query($CONNECTION, $query);
        $Message = "Salary Head $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SalaryHeadId == "")
        header("Location:SalaryHead");
    else
        header("Location:SalaryHead/Update/$SalaryHeadId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSalaryStructureTemplate") {
    array_map($_POST, "FilterSqlInjection");
    $FixedSalaryHead = $_POST['FixedSalaryHead'];
    $SalaryStructureName = $_POST['SalaryStructureName'];
    $SalaryStructureId = $_POST['SalaryStructureId'];
    $SalaryStructureStatus = $_POST['SalaryStructureStatus'];

    if ($SalaryStructureId != "")
        $Update = " and SalaryStructureId!='$SalaryStructureId' ";
    $query1 = "select * from salarystructure where SalaryStructureName='$SalaryStructureName' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSalaryStructureStatus = $row1['SalaryStructureStatus'];
            if ($OldSalaryStructureStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SalaryStructureId != "") {

        $query101 = "select StaffSalaryId from staffsalary where SalaryStructureId='$SalaryStructureId' ";
        $check101 = mysqli_query($CONNECTION, $query101);
        $count101 = mysqli_num_rows($check101);

        $addupdate = "updated";
        $query2 = "select SalaryStructureStatus,FixedSalaryHead from salarystructure where SalaryStructureId='$SalaryStructureId' and (SalaryStructureStatus='Active' or SalaryStructureStatus='') ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSalaryStructureStatus = mysqli_num_rows($check2);
        $row2 = mysqli_fetch_array($check2);
        $FixedSalaryHeadArray = $row2['FixedSalaryHead'];
        $FixedSalaryHeadArray = explode(",", $FixedSalaryHeadArray);
        $ArrayDiff = array_diff($FixedSalaryHeadArray, $FixedSalaryHead);
        if ($ArrayDiff != "") {
            foreach ($ArrayDiff as $ArrayDiffValue) {
                $query4 = "select SalaryStructureDetailId from salarystructuredetail where Expression like '%$ArrayDiffValue%' ";
                $check4 = mysqli_query($CONNECTION, $query4);
                $count4 = mysqli_num_rows($check4);
                if ($count4 > 0) {
                    $DependecyFound = 1;
                    break;
                }
            }
        }

        $query3 = "select SalaryHeadId from salarystructuredetail where SalaryStructureId='$SalaryStructureId' ";
        $check3 = mysqli_query($CONNECTION, $query3);
        while ($row3 = mysqli_fetch_array($check3))
            $SaveSalaryHeadId[] = $row3['SalaryHeadId'];

        if ($SaveSalaryHeadId != "") {
            foreach ($FixedSalaryHead as $FixedSalaryHeadArrayValue) {
                $SavedSearchIndex = array_search($FixedSalaryHeadArrayValue, $SaveSalaryHeadId);
                if ($SavedSearchIndex === FALSE) {
                    
                } else
                    $FoundError = 1;
            }
        }
    } else
        $addupdate = "added";

    if ($FixedSalaryHead == "" || $SalaryStructureName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($SalaryStructureId != "" && $CurrentSalaryStructureStatus == 0) {
        $Message = "This salary structure is deleted. You cannot update the deleted salary structure!!";
        $Type = "error";
    } elseif ($DependecyFound == 1) {
        $Message = "Can't updated because one of expression uses this value!!";
        $Type = "error";
    } elseif ($FoundError == 1) {
        $Message = "One of the salary head is already added as expression!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This salary structure is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DateTimeStamp = strtotime($Date);
        $FixedSalaryHead = implode(",", $FixedSalaryHead);
        if ($SalaryStructureId == "")
            $query = "insert into salarystructure(FixedSalaryHead,SalaryStructureName,SalaryStructureStatus) values('$FixedSalaryHead','$SalaryStructureName','Active') ";
        else {
            if ($count101 == 0)
                $query = "update salarystructure set FixedSalaryHead='$FixedSalaryHead',SalaryStructureName='$SalaryStructureName',SalaryStructureStatus='$SalaryStructureStatus' where SalaryStructureId='$SalaryStructureId' ";
            else
                $query = "update salarystructure set SalaryStructureName='$SalaryStructureName' where SalaryStructureId='$SalaryStructureId' ";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Salary Structure $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SalaryStructureId == "")
        header("Location:SalaryStructureTemplate");
    else
        header("Location:SalaryStructureTemplate/Update/$SalaryStructureId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSalaryStructureTemplate2") {
    include("ExpressionValidator.php");
    array_map($_POST, "FilterSqlInjection");
    $SalaryHead = $_POST['SalaryHead'];
    $SalaryStructureId = $_POST['SalaryStructureId'];
    $Expression = $_POST['Expression'];
    $ExpressionCheck = $_POST['Expression'];

    $query1 = "select FixedSalaryHead from salarystructure where SalaryStructureId='$SalaryStructureId' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    $row1 = mysqli_fetch_array($check1);
    $FixedSalaryHead = $row1['FixedSalaryHead'];
    $FixedSalaryHeadArray = explode(",", $FixedSalaryHead);
    foreach ($FixedSalaryHeadArray as $FixedSalaryHeadArrayValue) {
        if ($FixedSalaryHeadArrayValue == $SalaryHead)
            $FixedFound = 1;
    }

    $query2 = "select SalaryHeadId,SalaryHead,Code,MasterEntryValue from salaryhead,masterentry where salaryhead.SalaryHeadType=masterentry.MasterEntryId order by SalaryHead ";
    $check2 = mysqli_query($CONNECTION, $query2);
    while ($row2 = mysqli_fetch_array($check2)) {
        $SelectSalaryHead = $row2['SalaryHead'];
        $SelectCode = $row2['Code'];
        $SelectSalaryHeadType = $row2['MasterEntryValue'];
        $SelectSalaryHeadId = $row2['SalaryHeadId'];
        $SalaryHeadIdArray[] = $SelectSalaryHeadId;
        $SalaryHeadArray[] = $SelectSalaryHead;
        $SalaryHeadCodeArray[] = $SelectCode;
    }

    $SalaryHeadSearchIndex = array_search($SalaryHead, $SalaryHeadIdArray);
    $SalaryHeadCode = $SalaryHeadCodeArray[$SalaryHeadSearchIndex];
    $SearchForSameCode = strpos($Expression, $SalaryHeadCode);
    if ($SearchForSameCode === FALSE) {
        
    } else
        $SelfDependency = 1;

    $query3 = "select SalaryHeadId,Expression from salarystructuredetail where SalaryStructureId='$SalaryStructureId' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    $count3 = mysqli_num_rows($check3);
    if ($count3 > 0) {
        while ($row3 = mysqli_fetch_array($check3)) {
            $SavedSalaryHeadId = $row3['SalaryHeadId'];
            if ($SalaryHead == $SavedSalaryHeadId)
                $Duplicate = 1;
            $SavedExpression = $row3['Expression'];
            $SavedSalaryHeadIdArray[] = $SavedSalaryHeadId;
            $SavedExpressionArray[] = $SavedExpression;
        }
    }

    foreach ($SalaryHeadCodeArray as $SalaryHeadCodeArrayValue) {     // Searching for every salary code
        $SearchForCode = strpos($Expression, $SalaryHeadCodeArrayValue);   // Checking whether a code is in expression or not
        if ($SearchForCode === FALSE) {
            
        }           // if it is not in expression then don't worry
        else {
            $FoundCodeIndex = array_search($SalaryHeadCodeArrayValue, $SalaryHeadCodeArray); // if it is then check for salary head id
            $FoundCodeId = $SalaryHeadIdArray[$FoundCodeIndex];
            if ($SavedSalaryHeadIdArray != "")
                $FoundId = array_search($FoundCodeId, $SavedSalaryHeadIdArray);

            if ($FixedSalaryHeadArray != "" && $FoundId === FALSE)
                $FoundId = array_search($FoundCodeId, $FixedSalaryHeadArray);

            if ($FoundId === FALSE) {
                $Undefined = 1;
                $CodeNotFound = $SalaryHeadCodeArrayValue;
                break;
            }
        }
        $ExpressionCheck = str_replace($SalaryHeadCodeArrayValue, 1, $ExpressionCheck);
    }

    if (0 !== preg_match($regex, $ExpressionCheck)) {
        $ValidationExpression = 1;
        //$answer = eval( 'return ' . $var . ';' );
    } else
        $ValidationExpression = 0;

    if ($SalaryHead == "" || $SalaryStructureId == "" || $Expression == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($FixedFound == 1) {
        $Message = "Fixed salary can not be set in expression!!";
        $Type = error;
    } elseif ($count1 == 0) {
        $Message = "This is not a valid URL!!";
        $Type = error;
    } elseif ($SelfDependency == 1) {
        $Message = "This salary head cannot has self dependency!!";
        $Type = error;
    } elseif ($Duplicate == 1) {
        $Message = "This salary head is already added to same structure!!";
        $Type = error;
    } elseif ($Undefined == 1) {
        $Message = "$CodeNotFound is not defined yet!!";
        $Type = error;
    } elseif ($ValidationExpression == 0) {
        $Message = "This is not a valid expression!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        mysqli_query($CONNECTION, "insert into salarystructuredetail(Expression,SalaryStructureId,SalaryHeadId) values('$Expression','$SalaryStructureId','$SalaryHead') ");
        $Message = "Saved successfully!!";
        $Type = success;
    }

    echo "$Message";

    if ($Type == error) {
        $_SESSION['SalaryHead'] = $SalaryHead;
        $_SESSION['Expression'] = $Expression;
    }
    SetNotification($Message, $Type);
    header("Location:SalaryStructureTemplate/View/$SalaryStructureId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageUser") {
    array_map($_POST, "FilterSqlInjection");
    $UserType = $_POST['UserType'];
    $Username = $_POST['Username'];
    $StaffId = $_POST['StaffId'];
    $Password = $_POST['Password'];
    $UserId = $_POST['UserId'];
    $ResetPassword = $_POST['ResetPassword'];
    $ResetPasswordValue = $_POST['ResetPasswordValue'];

    if ($UserId != "")
        $Update = " and UserId!='$UserId' ";
    $query1 = "select * from user where (Username='$Username' or StaffId='$StaffId') $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($UserType == "" || $Username == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif (!(ctype_alnum($Username))) {
        $Message = "Only alphabets and numbers can be used!!";
        $Type = "error";
    } elseif ($UserId == "" && ($Password == "" || strlen($Password) < 6)) {
        $Message = "Please enter password!!";
        $Type = "error";
    } elseif ($ResetPassword == "Yes" && $ResetPasswordValue == "") {
        $Message = "Please enter password to reset!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This Username is not available or Staff Id is already associated with one user!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $ResetPasswordValue = md5($ResetPasswordValue);
        $Password = md5($Password);
        if ($ResetPassword == "Yes")
            $PasswordQuery = "Password='$ResetPasswordValue',";
        $Date = strtotime($Date);
        if ($UserId == "")
            $query = "insert into user(UserType,StaffId,Username,Password,DOE) values('$UserType','$StaffId','$Username','$Password','$Date') ";
        else
            $query = "update user set $PasswordQuery StaffId='$StaffId',UserType='$UserType',DOL='$Date',DOL='$USERNAME' where UserId='$UserId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Success!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($UserId == "")
        header("Location:ManageUser");
    else
        header("Location:ManageUser/Update/$UserId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "SetPermission") {
    array_map($_POST, "FilterSqlInjection");
    $UserType = $_POST['UserType'];
    $PermissionSTR = $_POST['PermissionSTR'];
    $PermissionSTR = implode(",", $PermissionSTR);

    $query = "select * from permission where UserType='$UserType' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    if ($UserType == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($count == 0) {
            $query = "insert into permission(UserType,PermissionString) values
		('$UserType','$PermissionSTR') ";
        } else {
            $query = "update permission set PermissionString='$PermissionSTR'
				 where UserType='$UserType'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Permission");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManagePage") {
    array_map($_POST, "FilterSqlInjection");
    $Page = $_POST['Page'];
    $PageNameId = $_POST['PageNameId'];

    if ($PageNameId != "") {
        $Already = "and PageNameId!='$PageNameId'";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select PageNameId from pagename where PageName='$Page' $Already ");
    $count = mysqli_num_rows($check);

    if ($Page == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This page is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($PageNameId == "") {
            $query = "insert into pagename(PageName) values
		('$Page') ";
        } else {
            $query = "update pagename set PageName='$Page'
				 where PageNameId='$PageNameId'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Pagename $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($PageNameId == "")
        header("Location:Permission");
    else
        header("Location:Permission/UpdatePage/$PageNameId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageTable") {
    $Table = Escape(trim($_POST['Table']));

    if ($Table != "") {
        $Already = "and TableName!='$Table'";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select TableName from tablename where TableName='$Table' $Already ");
    $count = mysqli_num_rows($check);

    if ($Table == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This table is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($TableName == "") {
            $query = "insert into tablename(TableName) values
		('$Table') ";
        } else {
            $query = "update tablename set TableName='$Table'
				 where TableName='$TableName'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Table $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($TableName == "")
        header("Location:Permission");
    else
        header("Location:Permission/UpdateTable/$TableName");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageLocation") {
    array_walk($_POST, "FilterSqlInjection");
    $LocationName = $_POST['LocationName'];
    $CalledAs = $_POST['CalledAs'];
    $LocationId = $_POST['LocationId'];

    if ($LocationId != "")
        $Update = " and LocationId!='$LocationId' ";
    $query1 = "select * from location where LocationName='$LocationName' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldLocationStatus = $row1['LocationStatus'];
            if ($OldLocationStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($LocationId != "") {
        $addupdate = "updated";
        $query2 = "select LocationStatus from location where LocationId='$LocationId' and LocationStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentLocationStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($LocationName == "" || $CalledAs == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This location is already added!!";
        $Type = "error";
    } elseif ($LocationId != "" && $CurrentLocationStatus == 0) {
        $Message = "This location is deleted. You cannot update the deleted location!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($LocationId == "") {
            $query = "insert into location(LocationName,CalledAs,LocationStatus) values
		('$LocationName','$CalledAs','Active') ";
        } else {
            $query = "update location set LocationName='$LocationName',CalledAs='$CalledAs'
			where LocationId='$LocationId' and LocationStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Location $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($LocationId == "")
        header("Location:ManageLocation");
    else
        header("Location:ManageLocation/Update/$LocationId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageHeader") {
    array_walk($_POST, "FilterSqlInjection");
    $HeaderTitle = $_POST['HeaderTitle'];
    $HRType = $_POST['HRType'];
    $HeaderContent = $_POST['HeaderContent'];
    $HeaderId = $_POST['HeaderId'];

    if ($HeaderId != "") {
        $Already = "and HeaderId!='$HeaderId' ";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select HeaderId from header where HeaderTitle='$HeaderTitle' $Already");
    $count = mysqli_num_rows($check);

    $check1 = mysqli_query($CONNECTION, "select * from header where HRType='$HRType' ");
    $count1 = mysqli_num_rows($check1);
    if ($count1 == 0)
        $HeaderDefault = 'Yes';

    if ($HeaderTitle == "" || $HeaderContent == "" || $HRType == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This header is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($HeaderId == "") {
            $query = "insert into header(HRType,HeaderTitle,HeaderContent,HeaderDefault) values
		('$HRType','$HeaderTitle','$HeaderContent','$HeaderDefault') ";
        } else {
            $query = "update header set HeaderTitle='$HeaderTitle',HeaderContent='$HeaderContent' where HeaderId='$HeaderId' ";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Header $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($HeaderId == "")
        header("Location:ManageHeaderAndFooter");
    else
        header("Location:ManageHeaderAndFooter/Update/$HeaderId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Income") {
    array_walk($_POST, "FilterSqlInjection");
    $IncomeAccountTypeId = $_POST['IncomeAccount'];
    $Amount = $_POST['Amount'];
    $Account = $_POST['Account'];
    $DOI = $_POST['DOI'];
    $Remarks = mynl2br($_POST['Remarks']);
    $TransactionId = $_POST['TransactionId'];
    $CheckAmount = CheckAmountWithoutZero($Amount);
    $Date = strtotime($Date);
    $DOI = strtotime($DOI);

    if ($Amount == "" || $Account == "" || $DOI == "" || $Remarks == "" || $IncomeAccountTypeId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($CheckAmount == 0) {
        $Message = "Amount should be numeric and greater than zero!!";
        $Type = "error";
    } elseif ($SCHOOLSTARTDATE > $DOI) {
        $Message = "Income date cannot be less than Software start date!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query = "insert into transaction(Username,TransactionAmount,TransactionType,TransactionFrom,TransactionHead,TransactionHeadId,TransactionRemarks,TransactionDate,TransactionDOE,TransactionIP,TransactionStatus) values
		('$USERNAME','$Amount','1','$Account','Income','$IncomeAccountTypeId','$Remarks','$DOI','$Date','$IP','Active') ";
        mysqli_query($CONNECTION, $query);
        mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance+$Amount where AccountId='$Account' ");
        $Message = "Income added successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Income");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Supplier") {
    array_walk($_POST, "FilterSqlInjection");
    $SupplierName = $_POST['SupplierName'];
    $SupplierMobile = $_POST['SupplierMobile'];
    $SupplierRemarks = $_POST['SupplierRemarks'];
    $SupplierAddress = mynl2br($_POST['SupplierAddress']);
    $SupplierId = $_POST['SupplierId'];

    if ($SupplierId != "")
        $Update = " and SupplierId!='$SupplierId' ";
    $query1 = "select * from supplier where SupplierName='$SupplierName' and SupplierMobile='$SupplierMobile' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSupplierStatus = $row1['SupplierStatus'];
            if ($OldSupplierStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SupplierId != "") {
        $MessageContent = "updated";
        $query2 = "select SupplierStatus from supplier where SupplierId='$SupplierId' and SupplierStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSupplierStatus = mysqli_num_rows($check2);
    } else
        $MessageContent = "added";

    if ($SupplierName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($SupplierId != "" && $CurrentSupplierStatus == 0) {
        $Message = "This supplier is deleted. You cannot update the deleted supplier!!";
        $Type = "alert-error";
    } elseif ($count1 > 0) {
        $Message = "This supplier is already added!!";
        $Type = "alert-error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($SupplierId == "") {
            $query = "insert into supplier(SupplierName,SupplierMobile,SupplierRemarks,SupplierAddress,Date,SupplierStatus) values
		('$SupplierName','$SupplierMobile','$SupplierRemarks','$SupplierAddress','$Date','Active') ";
        } else {
            $query = "update supplier set SupplierName='$SupplierName',SupplierMobile='$SupplierMobile',SupplierRemarks='$SupplierRemarks',SupplierAddress='$SupplierAddress',DLU='$Date'
			where SupplierId='$SupplierId' and SupplierStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Supplier $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SupplierId == "")
        header("Location:Supplier");
    else
        header("Location:Supplier/Update/$SupplierId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Expense") {
    array_walk($_POST, "FilterSqlInjection");
    $ExpenseAccountType = $_POST['ExpenseAccountType'];
    $ExpenseAccountTypeId = $_POST[$ExpenseAccountType];
    $SupplierId = $_POST['SupplierId'];
    $Amount = $_POST['Amount'];
    $ExpenseDate = $_POST['ExpenseDate'];
    $Payment = $_POST['Payment'];
    $AmountPaid = $_POST['AmountPaid'];
    $Account = $_POST['Account'];
    $DOP = $_POST['DOP'];
    $PaymentRemarks = mynl2br($_POST['PaymentRemarks']);
    $ExpenseRemarks = mynl2br($_POST['ExpenseRemarks']);
    $ExpenseId = $_POST['ExpenseId'];
    $Date = strtotime($Date);
    $ExpenseDate = strtotime($ExpenseDate);
    $DOP = strtotime($DOP);

    if ($Payment == "Yes") {
        $query = "Select (OpeningBalance+AccountBalance) as TotalAccountBalance,AccountName from accounts where AccountId='$Account' ";
        $check = mysqli_query($CONNECTION, $query);
        $row = mysqli_fetch_array($check);
        $TotalAccountBalance = round($row['TotalAccountBalance'], 2);
        $AccountName = $row['AccountName'];
    }

    if ($ExpenseAccountType == "" || $SupplierId == "" || $Amount == "" || $ExpenseDate == "" || $ExpenseAccountTypeId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($Payment == "Yes" && ($AmountPaid == "" || $AmountPaid <= 0 || $DOP == "" || $Account == "")) {
        $Message = "Please enter payment details in case of payment!!";
        $Type = "error";
    } elseif ($Payment == "Yes" && $TotalAccountBalance < $AmountPaid) {
        $Message = "$AccountName has only $TotalAccountBalance $CURRENCY!!";
        $Type = "error";
    } elseif ($Payment == "Yes" && $AmountPaid > $Amount) {
        $Message = "Amount paid cannot be greater than expense amount!!";
        $Type = "error";
    } elseif ($SCHOOLSTARTDATE > $ExpenseDate) {
        $Message = "Expense date cannot be less than Software start date!!";
        $Type = "error";
    } elseif ($SCHOOLSTARTDATE > $DOP && $Payment == "Yes") {
        $Message = "Payment date cannot be less than Software start date!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($Payment != "Yes") {
            $AmountPaid = "";
            $Account = "";
            $DOP = "";
        }
        $query2 = "insert into expense(Username,ExpenseStatus,ExpenseAccountType,SupplierId,ExpenseAmount,AmountPaid,ExpenseDate,DOE,ExpenseRemarks) values 
		('$USERNAME','Active','$ExpenseAccountTypeId','$SupplierId','$Amount','$AmountPaid','$ExpenseDate','$Date','$ExpenseRemarks') ";
        mysqli_query($CONNECTION, $query2);
        $ExpenseId = mysqli_insert_id($CONNECTION);
        $query1 = "insert into transaction(Username,TransactionAmount,TransactionType,TransactionFrom,TransactionHead,TransactionHeadId,TransactionRemarks,TransactionDate,TransactionDOE,TransactionIP,TransactionStatus) values
		('$USERNAME','$AmountPaid','0','$Account','Expense','$ExpenseId','$PaymentRemarks','$DOP','$Date','$IP','Active') ";
        if ($Payment == "Yes") {
            mysqli_query($CONNECTION, $query1);
            mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance-$AmountPaid where AccountId='$Account' ");
        }
        $Message = "Expense added successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Expense");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ExpenseMakePayment") {
    array_walk($_POST, "FilterSqlInjection");
    $AmountPaid = $_POST['RemainingAmountPaid'];
    $Account = $_POST['RemainingAccount'];
    $DOP = $_POST['RemainingDOP'];
    $Remarks = mynl2br($_POST['RemainingRemarks']);
    $ExpenseId = $_POST['ExpenseId'];

    $query = "Select (OpeningBalance+AccountBalance) as TotalAccountBalance,AccountName from accounts where AccountId='$Account' ";
    $check = mysqli_query($CONNECTION, $query);
    $row = mysqli_fetch_array($check);
    $TotalAccountBalance = round($row['TotalAccountBalance'], 2);
    $AccountName = $row['AccountName'];

    $query3 = "select AmountPaid,ExpenseAmount,SupplierId from expense where ExpenseId='$ExpenseId' and ExpenseStatus='Active' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    $count3 = mysqli_num_rows($check3);
    $row3 = mysqli_fetch_array($check3);
    $Paid = $row3['AmountPaid'];
    $TotalAmount = $row3['ExpenseAmount'];
    $SupplierId = $row3['SupplierId'];
    $Balance = $TotalAmount - $Paid;

    if ($AmountPaid == "" || $AmountPaid <= 0 || $DOP == "" || $Account == "" || $Remarks == "" || $ExpenseId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TotalAccountBalance < $AmountPaid) {
        $Message = "$AccountName has only $TotalAccountBalance $CURRENCY!!";
        $Type = "error";
    } elseif ($count3 == 0) {
        $Message = "Wrong URL!!";
        $Type = "error";
    } elseif ($AmountPaid > $Balance) {
        $Message = "Only $Balance $CURRENCY is remaining to pay!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $DOP = strtotime($DOP);
        $query1 = "insert into transaction(Username,TransactionAmount,TransactionType,TransactionFrom,TransactionHead,TransactionHeadId,TransactionRemarks,TransactionDate,TransactionDOE,TransactionIP,TransactionStatus) values
		('$USERNAME','$AmountPaid','0','$Account','Expense','$ExpenseId','$Remarks','$DOP','$Date','$IP','Active') ";
        mysqli_query($CONNECTION, $query1);
        mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance-$AmountPaid where AccountId='$Account' ");
        mysqli_query($CONNECTION, "update expense set AmountPaid=AmountPaid+$AmountPaid where ExpenseId='$ExpenseId' ");
        $Message = "Expense added successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Expense/Payment/$ExpenseId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "PromotionConfirm") {
    array_walk($_POST, "FilterSqlInjection");
    $SectionId = $_POST['SectionId'];
    $NextSession = $_POST['NextSession'];
    $NextSectionId = $_POST['NextSectionId'];
    $AdmissionId = $_POST['AdmissionId'];
    $AdmissionId = explode(",", $AdmissionId);
    $DOP = $_POST['DOP'];
    $Distance = $_POST['Distance'];
    $Remarks = mynl2br($_POST['Remarks']);
    $FeeArray = $_POST['FeeArray'];
    $FeeArray = explode("-", $FeeArray);
    $Count = count($FeeArray);
    for ($i = 0; $i < $Count; $i++) {
        $FeeAmount = $_POST[$FeeArray[$i]];
        if (!CheckNumeric($FeeAmount))
            $ErrorInFee++;
        $FeeString.="$FeeArray[$i]-$FeeAmount";
        if ($i != ($Count - 1))
            $FeeString.=",";
    }

    if ($AdmissionId == "" || $DOP == "" || $SectionId == "" || $NextSession == "" || $NextSectionId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($ErrorInFee > 0) {
        $Message = "$ErrorInFee number of fees are not numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOP = strtotime($DOP);
        $DOE = strtotime($Date);

        foreach ($AdmissionId as $AdmissionIdValue) {
            $query22 = "select StudentFeeId from studentfee where AdmissionId='$AdmissionIdValue' and Session='$NextSession' ";
            $check22 = mysqli_query($CONNECTION, $query22);
            $count22 = mysqli_num_rows($check22);
            if ($count22 == 0) {
                $query1 = "insert into studentfee(AdmissionId,Date,DOE,SectionId,Session,FeeStructure,Distance)
				values('$AdmissionIdValue','$DOP','$DOE','$NextSectionId','$NextSession','$FeeString','$Distance') ";
                mysqli_query($CONNECTION, $query1);
            }
        }

        $Message = "Promoted successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Promotion");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "PaymentConfirm") {
    array_walk($_POST, "FilterSqlInjection");
    $Token = $_POST['Token'];
    $Account = $_POST['Account'];
    $AdmissionId = $_POST['AdmissionId'];
    $SectionId = $_POST['SectionId'];
    $Remarks = mynl2br($_POST['Remarks']);
    $DOP = $_POST['DOP'];

    $query2 = "select SUM(Amount) as PaidFeeType,FeeType from feepayment,transaction where
	feepayment.Token=transaction.Token and
	transaction.TransactionHead='Fee' and
	transaction.TransactionHeadId='$AdmissionId' and
	transaction.TransactionSession='$CURRENTSESSION' and 
	transaction.TransactionStatus='Active' 
	group by FeeType ";
    $check2 = mysqli_query($CONNECTION, $query2);
    $count2 = mysqli_num_rows($check2);
    while ($row2 = mysqli_fetch_array($check2)) {
        $PaidFeeTypeArray[] = $row2['FeeType'];
        $PaidFeeAmountArray[] = $row2['PaidFeeType'];
    }

    $query0 = "Select FeeStructure from studentfee where AdmissionId='$AdmissionId' and SectionId='$SectionId' and Session='$CURRENTSESSION' ";
    $check0 = mysqli_query($CONNECTION, $query0);
    $count0 = mysqli_num_rows($check0);
    if ($count0 > 0) {
        $row0 = mysqli_fetch_array($check0);
        $FeeStructure = explode(",", $row0['FeeStructure']);
        foreach ($FeeStructure as $FeeStructureValue) {
            $FeeStructureSubArray = explode("-", $FeeStructureValue);
            $FeeTypeArray[] = $FeeStructureSubArray[0];
            if ($PaidFeeTypeArray != "") {
                $PaidFeeSearchIndex = array_search($FeeStructureSubArray[0], $PaidFeeTypeArray);
                if ($PaidFeeSearchIndex === FALSE)
                    $FeeAmountArray[] = $FeeStructureSubArray[1];
                else
                    $FeeAmountArray[] = $FeeStructureSubArray[1] - $PaidFeeAmountArray[$PaidFeeSearchIndex];
            } else
                $FeeAmountArray[] = $FeeStructureSubArray[1];
        }
    }
    $query1 = "select feepayment.Amount,feepayment.FeeType,MasterEntryValue from feepayment,fee,masterentry where 
		fee.FeeId=feepayment.FeeType and 
		Token='$Token' and 
		fee.FeeType=masterentry.MasterEntryId and
		FeePaymentStatus='Pending' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    while ($row1 = mysqli_fetch_array($check1)) {
        $ToBePaidAmountArray[] = $row1['Amount'];
        $Amount+=$row1['Amount'];
        $ToBePaidFeeTypeArray[] = $row1['FeeType'];
        $FeeName = $row1['MasterEntryValue'];
        $FeeSearchIndex = array_search($row1['FeeType'], $FeeTypeArray);
        $BalanceAmount = $FeeAmountArray[$FeeSearchIndex];
        if ($row1['Amount'] > $BalanceAmount) {
            $OverFeeError = 1;
            $OverFeeErrorMessage.="<br> $FeeName is only $BalanceAmount $CURRENCY due.";
        }
    }


    if ($Token == "" || $Account == "" || $AdmissionId == "" || $DOP == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($count1 == 0) {
        $Message = "No fee added in the list!!";
        $Type = error;
    } elseif ($count0 == 0) {
        $Message = "This is not a valid student id!!";
        $Type = error;
    } elseif ($OverFeeError == 1) {
        $Message = "Following fee amount is greater than balance amount : $OverFeeErrorMessage";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOP = strtotime($DOP);
        $Date = strtotime($Date);
        $queryInsert = "insert into transaction(Username,Token,TransactionSession,TransactionAmount,TransactionType,TransactionFrom,TransactionHead,TransactionSubHead,TransactionHeadId,TransactionRemarks,TransactionDate,TransactionDOE,TransactionStatus)
			values('$USERNAME','$Token','$CURRENTSESSION','$Amount','1','$Account','Fee','','$AdmissionId','$Remarks','$DOP','$Date','Active') ";
        mysqli_query($CONNECTION, $queryInsert);
        mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance+$Amount where AccountId='$Account' ");
        mysqli_query($CONNECTION, "update feepayment set FeePaymentStatus='Active' where Token='$Token' ");
        $Message = "Fee Paid successfully!!";
        $Type = success;
    }
    if ($Type == error)
        $_SESSION['PaymentToken'] = $Token;
    SetNotification($Message, $Type);
    header("Location:Payment/$AdmissionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "UpdateFeeConfirm") {
    array_walk($_POST, "FilterSqlInjection");
    $AdmissionId = $_POST['AdmissionId'];
    $AdmissionNo = $_POST['AdmissionNo'];
    $SectionId = $_POST['SectionId'];
    $DOAP = $_POST['DOAP'];
    $Remarks = mynl2br($_POST['Remarks']);
    $FeeArray = $_POST['FeeArray'];
    $FeeArray = explode("-", $FeeArray);
    $Count = count($FeeArray);
    for ($i = 0; $i < $Count; $i++) {
        $FeeAmount = $_POST[$FeeArray[$i]];
        $Total+=$FeeAmount;
        if (!CheckNumeric($FeeAmount))
            $ErrorInFee++;
        $FeeString.="$FeeArray[$i]-$FeeAmount";
        if ($i != ($Count - 1))
            $FeeString.=",";
        $FeeIdArray[] = $FeeArray[$i];
        $FeeAmountArray[] = $_POST[$FeeArray[$i]];
    }

    $query11 = "select Sum(feepayment.Amount) as PaidFee,feepayment.FeeType,MasterEntryValue from transaction,fee,feepayment,masterentry where
		transaction.Token=feepayment.Token and
		fee.FeeType=masterentry.MasterEntryId and
		feepayment.FeeType=fee.FeeId and 
		TransactionStatus='Active' and
		FeePaymentStatus='Active' and
		transaction.TransactionHead='Fee' and 
		TransactionHeadId='$AdmissionId' and
		TransactionSession='$CURRENTSESSION' group by feepayment.FeeType";
    $check11 = mysqli_query($CONNECTION, $query11);
    $count11 = mysqli_num_rows($check11);
    if ($count11 > 0) {
        while ($row11 = mysqli_fetch_array($check11)) {
            $PaidFeeAmountArray[] = $row11['PaidFee'];
            $PaidFeeIdArray[] = $row11['FeeType'];
            $PaidFeeNameArray[] = $row11['MasterEntryValue'];
        }
    }

    $k = 0;
    foreach ($FeeIdArray as $FeeIdArrayValue) {
        $FeeAmountValue = $FeeAmountArray[$k];
        $SearchIndex = array_search($FeeIdArrayValue, $PaidFeeIdArray);
        if ($SearchIndex === FALSE) {
            
        } else {
            $PaidFeeAmountValue = $PaidFeeAmountArray[$SearchIndex];
            $PaidFeeName = $PaidFeeNameArray[$SearchIndex];
            if ($FeeAmountValue < $PaidFeeAmountValue) {
                $ErrorMsg = "$PaidFeeName is already paid $PaidFeeAmountValue $CURRENCY, You cannot set it $FeeAmountValue $CURRENCY!!";
                break;
            }
        }
        $k++;
    }

    $query11 = "select StudentName,Mobile from studentfee,registration,admission where 
		studentfee.AdmissionNo='$AdmissionNo' and studentfee.AdmissionId!='$AdmissionId' and
		registration.RegistrationId=admission.AdmissionId and
		admission.AdmissionId=studentfee.AdmissionId ";
    $check11 = mysqli_query($CONNECTION, $query11);
    $count11 = mysqli_num_rows($check11);

    if ($AdmissionId == "" || $DOAP == "" || $SectionId == "" || $AdmissionNo == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count11 > 0) {
        $row11 = mysqli_fetch_array($check11);
        $AdmissionName = $row11['StudentName'];
        $AdmissionMobile = $row11['Mobile'];
        $Message = "This Admission No is already assigned to $AdmissionName $AdmissionMobile!!";
        $Type = "error";
    } elseif ($PaidFee > $Total) {
        $Message = "$PaidFee $CURRENCY amount has already been paid, less than $PaidFee $CURRENCY can not be set!!";
        $Type = "error";
    } elseif ($ErrorMsg != "") {
        $Message = $ErrorMsg;
        $Type = "error";
    } elseif ($ErrorInFee > 0) {
        $Message = "$ErrorInFee number of fees are not numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOAP = strtotime($DOAP);
        $DOE = strtotime($Date);
        $query111 = "update studentfee set AdmissionNo='$AdmissionNo',FeeStructure='$FeeString',Date='$DOAP',Remarks='$Remarks' where AdmissionId='$AdmissionId' and SectionId='$SectionId' and Session='$CURRENTSESSION'";
        mysqli_query($CONNECTION, $query111);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:UpdateFee/$SectionId/$AdmissionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageBooks") {
    array_walk($_POST, "FilterSqlInjection");
    $BookName = $_POST['BookName'];
    $AuthorName = $_POST['AuthorName'];
    $SubjectId = $_POST['SubjectId'];
    $Purpose = $_POST['Purpose'];
    $Publisher = $_POST['Publisher'];
    $Price = $_POST['Price'];
    $BookId = $_POST['BookId'];

    if ($BookId != "")
        $Update = " and BookId!='$BookId' ";
    $query1 = "select * from book where BookName='$BookName' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldBookStatus = $row1['BookStatus'];
            if ($OldBookStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($BookId != "") {
        $addupdate = "updated";
        $query2 = "select BookStatus from book where BookId='$BookId' and BookStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentBookStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";


    if ($BookName == "" || $Purpose == "" || $Price == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($BookId != "" && $CurrentBookStatus == 0) {
        $Message = "This book is deleted. You cannot update the deleted book!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This book is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($BookId == "") {
            $query = "insert into book(BookStatus,BookName,AuthorName,Publisher,SubjectId,Purpose,Price,DOE,DOEUsername) values
		('Active','$BookName','$AuthorName','$Publisher','$SubjectId','$Purpose','$Price','$Date','$USERNAME') ";
        } else {
            $query = "update book set BookName='$BookName',AuthorName='$AuthorName',Publisher='$Publisher',SubjectId='$SubjectId',Purpose='$Purpose',
				Price='$Price',DOL='$Date',DOLUsername='$USERNAME'
				 where BookId='$BookId' and BookStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Book $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($BookId == "")
        header("Location:ManageBooks");
    else
        header("Location:ManageBooks/Update/$BookId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ListBookConfirm") {
    array_walk($_POST, "FilterSqlInjection");
    $Token = $_POST['Token'];
    $DOA = $_POST['DOA'];
    $Remarks = mynl2br($_POST['Remarks']);

    $query = "select AccessionNo from listbook where ListBookStatus='Active' ";
    $check = mysqli_query($CONNECTION, $query);
    while ($row = mysqli_fetch_array($check))
        $AccessionNoArray[] = $row['AccessionNo'];

    $query1 = "select AccessionNo from listbook where Token='$Token' and ListBookStatus='Pending' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    while ($row1 = mysqli_fetch_array($check1)) {
        $AccessionNo = $row1['AccessionNo'];
        $SearchIndex = array_search($AccessionNo, $AccessionNoArray);
        if ($SearchIndex === FALSE) {
            
        } else {
            $Found++;
            $AccessionNoFound[] = $AccessionNo;
        }
    }

    if ($Token == "" || $DOA == "" || $Remarks == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($count1 == 0) {
        $Message = "No book list found!!";
        $Type = error;
    } elseif ($Found > 0) {
        $AccessionNoFound = implode(",", $AccessionNoFound);
        $Message = "Acession No \"$AccessionNoFound\" are already added!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOA = strtotime($DOA);
        $Date = strtotime($Date);
        $queryInsert = "insert into listbookconfirm(DOEUsername,Token,DOE,DOA,ListBookConfirmStatus)
			values('$USERNAME','$Token','$Date','$DOA','Active') ";
        mysqli_query($CONNECTION, $queryInsert);
        mysqli_query($CONNECTION, "update listbook set ListBookStatus='Active' where Token='$Token' ");
        $Message = "New books are added into the list successfully!!";
        $Type = success;
    }
    if ($Type == error)
        $_SESSION['ListBookToken'] = $Token;
    SetNotification($Message, $Type);
    header("Location:ManageBooks");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "IssueBook") {
    array_walk($_POST, "FilterSqlInjection");
    $Books = $_POST['Books'];
    $IRToDetail = $_POST['IRToDetail'];
    $IRTo = $_POST['IRTo'];
    $DOI = $_POST['DOI'];
    $Remarks = mynl2br($_POST['Remarks']);

    $CountBooks = count($Books);
    foreach ($Books as $BookId) {
        $i++;
        $QuerySearch.=" ListBookId='$BookId' ";
        if ($i < $CountBooks)
            $QuerySearch.=" or ";
    }
    $QuerySearch = "( $QuerySearch )";

    $query = "select AccessionNo,BookName,AuthorName,IRStatus from listbook,book where
		book.BookId=listbook.BookId and $QuerySearch and ListBookStatus='Active' and BookStatus='Active' ";
    $check = mysqli_query($CONNECTION, $query);
    while ($row = mysqli_fetch_array($check)) {
        $BookName = $row['BookName'];
        $AuthorName = $row['AuthorName'];
        $IRStatus = $row['IRStatus'];
        $AccessionNo = $row['AccessionNo'];
        if ($IRStatus == "Issued") {
            $IssueBooks++;
            $AccessionNoList[] = $AccessionNo;
        }
    }

    if ($IRTo == "Student") {
        $query1 = "Select admission.AdmissionId from registration,admission,studentfee where
		registration.RegistrationId=admission.RegistrationId and
		admission.AdmissionId=studentfee.AdmissionId and
		studentfee.Session='$CURRENTSESSION' and 
		studentfee.AdmissionId='$IRToDetail' and
		registration.Status='Studying' ";
    } elseif ($IRTo == "Staff") {
        $query1 = "select StaffId from staff where 
		StaffId='$IRToDetail' and 
		StaffStatus='Active' ";
    }
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($Books == "" || $IRToDetail == "" || $IRTo == "" || $DOI == "") {
        $Message = "All the field are mandatory!!";
        $Type = error;
    } elseif ($IssueBooks > 0) {
        $AccessionNo = implode(",", $AccessionNoList);
        $Message = "Book $AccessionNo already issued!!";
        $Type = error;
    } elseif ($count1 != 1) {
        $Message = "$IRTo is not valid!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Books = implode(",", $Books);
        $DOE = strtotime($Date);
        $DOITimeStamp = strtotime($DOI);
        $query2 = "insert into bookissue(IRTo,IRToDetail,Books,DOI,BookIssueStatus,Remarks,DOE,DOEUsername) values
			('$IRTo','$IRToDetail','$Books','$DOITimeStamp','Active','$Remarks','$DOE','$USERNAME') ";
        mysqli_query($CONNECTION, $query2);
        mysqli_query($CONNECTION, "update listbook set IRStatus='Issued' where $QuerySearch ");
        $Message = "Book issued successfully!!";
        $Type = success;
    }
    if ($Type == error) {
        $Books = implode(",", $Books);
        $_SESSION['Books'] = $Books;
        $_SESSION['DOI'] = $DOI;
        $_SESSION['IRToDetail'] = $IRToDetail;
        $_SESSION['Remarks'] = $Remarks;
        $_SESSION['Error'] = "Error";
    }
    SetNotification($Message, $Type);
    header("Location:IssueAndReturn/$IRTo");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ReturnBook") {
    array_walk($_POST, "FilterSqlInjection");
    $IRTo = $_POST['IRTo'];
    $BookIssueId = $_POST['BookIssueId'];
    $ReturnBooks = $_POST['ReturnBooks'];
    $DOR = strtotime($_POST['DOR']);

    $query = "select Books,BookReturn from bookissue where IRTo='$IRTo' and BookIssueId='$BookIssueId' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    if ($count > 0) {
        $row = mysqli_fetch_array($check);
        $Books = explode(",", $row['Books']);
        $BookReturnOriginal = $row['BookReturn'];
        $BookReturn = explode(",", $row['BookReturn']);
        foreach ($BookReturn as $BookReturnValue) {
            $BookReturnValeWithDateTime = explode("-", $BookReturnValue);
            $ReturnBookId[] = $BookReturnValeWithDateTime[0];
            $ReturnBookDateTime[] = $BookReturnValeWithDateTime[1];
        }
    }

    $CountReturnBooks = count($ReturnBooks);
    print_r($ReturnBooks);
    print_r($Books);
    foreach ($ReturnBooks as $ReturnBooksValue) {
        $SearchForIndex = array_search($ReturnBooksValue, $Books);
        if ($SearchForIndex === FALSE) {
            $NotFoundInIssuedBook++;
        }
        $SearchForIndex2 = array_search($ReturnBooksValue, $ReturnBookId);
        if ($SearchForIndex2 === FALSE) {
            
        } else {
            $FoundInReturnedBook++;
        }
        if ($BookReturnOriginal == "")
            $BookReturnOriginal = "$ReturnBooksValue-$DOR";
        else
            $BookReturnOriginal.=",$ReturnBooksValue-$DOR";
        $UpdateQuery.=" ListBookId='$ReturnBooksValue' ";
        $i++;
        if ($i < $CountReturnBooks)
            $UpdateQuery.=" or ";
    }
    $UpdateQuery = "( $UpdateQuery )";

    if ($IRTo == "" || $BookIssueId == "" || $ReturnBooks == "" || $DOR == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($FoundInReturnedBook > 0) {
        $Message = "Some of the book is already returned!!";
        $Type = error;
    } elseif ($NotFoundInIssuedBook > 0) {
        $Message = "Some of the book are not issued!!";
        $Type = error;
    } elseif ($count != 1) {
        $Message = "This is not a valid URL!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $query1 = "update bookissue set BookReturn='$BookReturnOriginal' where BookIssueId='$BookIssueId' ";
        $query2 = "update listbook set IRStatus='' where $UpdateQuery ";
        mysqli_query($CONNECTION, $query1);
        mysqli_query($CONNECTION, $query2);
        $Message = "Book returned successfully!!";
        $Type = success;
    }
    echo $Message;
    SetNotification($Message, $Type);
    header("Location:IssueAndReturn/$IRTo/$BookIssueId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSCArea") {
    array_walk($_POST, "FilterSqlInjection");
    $SCAreaName = $_POST['SCAreaName'];
    $SCPartId = $_POST['SCPartId'];
    $SCAreaId = $_POST['SCAreaId'];
    $GradingPoint = $_POST['GradingPoint'];
    $Class = $_POST['Class'];
    $Class = implode(",", $Class);

    if ($SCAreaId != "")
        $Update = " and SCAreaId!='$SCAreaId' ";
    $query1 = "select * from scarea where SCAreaName='$SCAreaName' and Session='$CURRENTSESSION' and SCPartId='$SCPartId' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSCAreaStatus = $row1['SCAreaStatus'];
            if ($OldSCAreaStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SCAreaId != "") {
        $addupdate = "updated";
        $query2 = "select SCAreaStatus from scarea where SCAreaId='$SCAreaId' and SCAreaStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSCAreaStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($SCAreaName == "" || $SCPartId == "" || $Class == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This area is already added!!";
        $Type = "error";
    } elseif ($SCAreaId != "" && $CurrentSCAreaStatus == 0) {
        $Message = "This area is deleted. You cannot update the deleted area!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($SCAreaId == "")
            $query = "insert into scarea(GradingPoint,SCAreaName,SCPartId,SCAreaStatus,Session,DOE,SCAreaClass) values('$GradingPoint','$SCAreaName','$SCPartId','Active','$CURRENTSESSION','$DOE','$Class') ";
        else
            $query = "update scarea set GradingPoint='$GradingPoint,SCAreaName='$SCAreaName',SCPartId='$SCPartId',DOL='$DOL',SCAreaClass='$Class' where SCAreaId='$SCAreaId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Area $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SCAreaId == "")
        header("Location:ManageSCArea");
    else
        header("Location:ManageSCArea/UpdateSCArea/$SCAreaId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSCIndicator") {
    array_walk($_POST, "FilterSqlInjection");
    $SCAreaId = $_POST['SCAreaId'];
    $SCIndicatorId = $_POST['SCIndicatorId'];
    $SCIndicatorName = $_POST['SCIndicatorName'];

    if ($SCIndicatorId != "")
        $Update = " and SCIndicatorId!='$SCIndicatorId' ";
    $query1 = "select * from scindicator where SCIndicatorName='$SCIndicatorName' and SCAreaId='$SCAreaId' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldSCIndicatorStatus = $row1['SCIndicatorStatus'];
            if ($OldSCIndicatorStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($SCAreaId != "") {
        $addupdate = "updated";
        $query2 = "select SCIndicatorStatus from scindicator where SCIndicatorId='$SCIndicatorId' and SCIndicatorStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSCIndicatorStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($SCIndicatorName == "" || $SCAreaId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This area is already added!!";
        $Type = "error";
    } elseif ($SCIndicatorId != "" && $CurrentSCIndicatorStatus == 0) {
        $Message = "This indicator is deleted. You cannot update the deleted indicator!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOL = strtotime($Date);
        $DOE = strtotime($Date);
        if ($SCIndicatorId == "")
            $query = "insert into scindicator(SCIndicatorName,SCAreaId,SCIndicatorStatus) values('$SCIndicatorName','$SCAreaId','Active') ";
        else
            $query = "update scindicator set SCIndicatorName='$SCIndicatorName',SCAreaId='$SCAreaId' where SCIndicatorId='$SCIndicatorId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Indicator $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SCIndicatorId == "")
        header("Location:ManageSCIndicator");
    else
        header("Location:ManageSCIndicator/UpdateSCIndicator/$SCIndicatorId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "SCMarksSave") {
    array_walk($_POST, "FilterSqlInjection");
    $SCAreaId = $_POST['SCAreaId'];
    $ExamId = $_POST['ExamId'];
    $FieldNameArray = explode(",", $_POST['FieldNameArray']);

    $query = "select SectionId from exam where ExamId='$ExamId' and Session='$CURRENTSESSION' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    $row = mysqli_fetch_array($check);
    $SectionId = $row['SectionId'];

    $query2 = "select admission.AdmissionId from admission,studentfee where
		admission.AdmissionId=studentfee.AdmissionId and
		studentfee.Session='$CURRENTSESSION' and
		studentfee.SectionId='$SectionId' ";
    $check2 = mysqli_query($CONNECTION, $query2);
    $count2 = mysqli_num_rows($check2);
    while ($row2 = mysqli_fetch_array($check2))
        $AdmissionIdArray[] = $row2['AdmissionId'];

    $query1 = "Select SCAreaClass from scarea where Session='$CURRENTSESSION' and SCAreaId='$SCAreaId' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    $row1 = mysqli_fetch_array($check1);
    $SCAreaClass = explode(",", $row1['SCAreaClass']);
    $Search = array_search($SectionId, $SCAreaClass);
    if ($Search === FALSE) {
        
    } else {
        $ValidSCAreaId = 1;
    }

    $query3 = "select SCExamDetailId from scexamdetail where ExamId='$ExamId' and SCAreaId='$SCAreaId' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    $count3 = mysqli_num_rows($check3);

    if ($SCAreaId == "" || $ExamId == "" || $FieldNameArray == "") {
        $Message = "all the fields are mandatory!!";
        $Type = error;
    } elseif ($count == 0) {
        $Message = "This is not a valid Exam Id!!";
        $Type = error;
    } elseif ($count1 == 0 || $ValidSCAreaId != 1) {
        $Message = "This is not a valid Area Id!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOE = strtotime($Date);
        $DOL = strtotime($Date);
        foreach ($FieldNameArray as $FieldNameArrayValue) {
            $AdmissionIdWithString = explode("-", $FieldNameArrayValue);
            $AdmissionId = $AdmissionIdWithString[1];
            if ($_POST[$FieldNameArrayValue] != "")
                $Indicators = implode(":", $_POST[$FieldNameArrayValue]);
            else
                $Indicators = "";
            $SearchForAdmissionId = array_search($AdmissionId, $AdmissionIdArray);
            if ($SearchForAdmissionId === FALSE) {
                
            } elseif ($Indicators != "")
                $Marks[] = "$AdmissionId-$Indicators";
        }
        $Marks = implode(",", $Marks);
        if ($count3 == 0)
            $query4 = "insert into scexamdetail(ExamId,SCAreaId,Marks,DOE) values('$ExamId','$SCAreaId','$Marks','$DOE') ";
        else
            $query4 = "update scexamdetail set Marks='$Marks',DOL='$DOL',DOLUsername='$USERNAME' where ExamId='$ExamId' and SCAreaId='$SCAreaId' ";
        mysqli_query($CONNECTION, $query4);
        $Message = "Indicators saved successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:SCMarksSetup/$ExamId/$SCAreaId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StaffSalaryStructure") {
    array_walk($_POST, "FilterSqlInjection");
    $SalaryStructureId = $_POST['SalaryStructureId'];
    $PaidLeave = $_POST['PaidLeave'];
    $EffectiveFrom = $_POST['EffectiveFrom'];
    $StaffId = $_POST['StaffId'];
    $Remarks = mynl2br($_POST['Remarks']);
    $EffectiveFrom = strtotime($EffectiveFrom);

    $query = "select FixedSalaryHead from salarystructure where SalaryStructureId='$SalaryStructureId' and SalaryStructureStatus='Active' ";
    $check = mysqli_query($CONNECTION, $query);
    $row = mysqli_fetch_array($check);
    $count = mysqli_num_rows($check);
    $FixedSalaryHeadArray = explode(",", $row['FixedSalaryHead']);
    foreach ($FixedSalaryHeadArray as $FixedSalaryHeadArrayValue) {
        $FieldName = "SalaryHead-$FixedSalaryHeadArrayValue";
        $Salary = $_POST[$FieldName];
        if ($Salary == "" || $Salary < 0 || !is_numeric($Salary))
            $ErrorInSalary++;
        else
            $SalaryString[] = "$FixedSalaryHeadArrayValue-$Salary";
    }
    $SalaryString = implode(",", $SalaryString);

    $query1 = "select StaffDOJ from staff where StaffId='$StaffId' and StaffStatus='Active' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    $row1 = mysqli_fetch_array($check1);
    $StaffDOJ = $row1['StaffDOJ'];

    if ($SalaryStructureId == "" || $PaidLeave == "" || $EffectiveFrom == "" || $StaffId == "" || $SalaryString == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($count == 0) {
        $Message = "This is not a valid Salary Structure!!";
        $Type = error;
    } elseif ($StaffDOJ > $EffectiveFrom) {
        $Message = "Salary can't be effective before staff joining date!!";
        $Type = error;
    } elseif ($ErrorInSalary > 0) {
        $Message = "$ErrorInSalary Salary are set in negative. Please set it greater than or equal to zero!!";
        $Type = error;
    } elseif ($count1 == 0) {
        $Message = "This is not a valid Staff!!";
        $Type = error;
    } elseif (!is_numeric($PaidLeave) || $PaidLeave < 0) {
        $Message = "Paid leave should be numeric!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOE = strtotime($Date);
        $query2 = "insert into staffsalary(StaffSalaryStatus,StaffId,SalaryStructureId,FixedSalary,StaffPaidLeave,EffectiveFrom,DOE,Remarks) 
			values('Active','$StaffId','$SalaryStructureId','$SalaryString','$PaidLeave','$EffectiveFrom','$DOE','$Remarks') ";
        mysqli_query($CONNECTION, $query2);
        $Message = "Salary structure saved successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:ManageStaff/$StaffId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StaffAttendance") {
    array_walk($_POST, "FilterSqlInjection");
    $Attendance = $_POST['box2View'];
    $AttendanceDate = $_POST['Date'];
    $InTime = $_POST['InTime'];
    $OutTime = $_POST['OutTime'];
    $CurrentSessionArray = explode("-", $CURRENTSESSION);
    $StartingYear = $CurrentSessionArray[0];
    $EndingYear = $CurrentSessionArray[1];
    $SessionStartingDate = "01-04-$StartingYear 00:00am";
    $SessionEndingDate = "31-03-$EndingYear 23:59am";
    $SessionStartinDateTS = strtotime($SessionStartingDate);
    $SessionEndingDateTS = strtotime($SessionEndingDate);

    if ($_POST['Present'] != "")
        $Att = "P";
    elseif ($_POST['Absent'] != "")
        $Att = "A";
    elseif ($_POST['HalfDay'] != "")
        $Att = "H";
    elseif ($_POST['Holiday'] != "")
        $Att = "HD";
    elseif ($_POST['OnDuty'] != "")
        $Att = "OD";
    elseif ($_POST['PaidLeave'] != "")
        $Att = "PL";
    elseif ($_POST['Blank'] != "")
        $Att = "";
    $DateTimeStamp = strtotime($Date);

    if ($CountStaff == 1 && $Att == "PL") {
        foreach ($box2View as $Staff)
            $StaffId = $Staff;
        $query2 = "select StaffName,PaidLeave from staff,staffsalary where
			staff.StaffId=staffsalary.StaffId and 
			staff.StaffId='$StaffId' and
			EffectiveFrom<='$DateTimeStamp'
			order by CAST(EffectiveFrom as SIGNED) desc ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $count2 = mysqli_num_rows($check2);
        if ($count2 > 0) {
            $row2 = mysqli_fetch_array($check2);
            $PaidLeave = $row2['PaidLeave'];
        }

        $query1 = "select Attendance from staffattendance where Date>='$SessionStartinDateTS' and Date<='$SessionEndingDateTS' ";
        $check1 = mysqli_query($CONNECTION, $query1);
        while ($row1 = mysqli_fetch_array($check1))
            $MarkedAttendance[] = $row1['Attendance'];

        foreach ($MarkedAttendance as $MarkedAttendanceValue) {
            $StaffAttAttendance = explode("-", $MarkedAttendanceValue);
            $MarkedStaffId = $StaffAttAttendance[0];
            $MarkedAtt = $StaffAttAttendance[1];

            if ($MarkedStaffId == $StaffId && $MarkedAtt == "PL")
                $UsedPL++;
        }
    }

    $CountStaff = count($Attendance);

    if ($Attendance == "" || $AttendanceDate == "" || $InTime == "" || $OutTime == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($InTime == $OutTime) {
        $Message = "In time & out time cannot be same!!";
        $Type = error;
    } elseif ($CountStaff > 1 && $Att == "PL") {
        $Message = "In case of Paid leave please select only one staff at a time!!";
        $Type = error;
    } elseif ($Att == "PL" && $count2 == 0) {
        $Message = "Paid leave is not set yet for selected employee!!";
        $Type = error;
    } elseif ($Att == "PL" && $UsedPL == $PaidLeave) {
        $Message = "Selected staff has used all its paid leave!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $InDateTime = "$AttendanceDate $InTime";
        $OutDateTime = "$AttendanceDate $OutTime";
        $ITS = strtotime($InDateTime);
        $OTS = strtotime($OutDateTime);
        $AttendanceDate = strtotime($AttendanceDate);
        $query = "select Attendance from staffattendance where Date='$AttendanceDate' ";
        $check = mysqli_query($CONNECTION, $query);
        $AlreadyMarked = mysqli_num_rows($check);
        if ($AlreadyMarked > 0) {
            $row = mysqli_fetch_array($check);
            $LastAttendance = explode(",", $row['Attendance']);
            foreach ($LastAttendance as $LastAttendanceValue) {
                $LastStaffAttAttendance = explode("-", $LastAttendanceValue);
                $LastStaffId = $LastStaffAttAttendance[0];
                $LastStaffAtt = $LastStaffAttAttendance[1];
                $LastStaffTime = $LastStaffAttAttendance[2];
                $Search = array_search($LastStaffId, $Attendance);
                if ($Search === FALSE)
                    $NewAttendance[] = "$LastStaffId-$LastStaffAtt-$LastStaffTime-$ITS-$OTS";
                elseif ($Att != "")
                    $NewAttendance[] = "$LastStaffId-$Att-$DateTimeStamp-$ITS-$OTS";
                $Marked[] = $LastStaffId;
            }

            foreach ($Attendance as $AttendanceValue) {
                $SearchForMarkedIndex = array_search($AttendanceValue, $Marked);
                if ($SearchForMarkedIndex === FALSE && $Att != "")
                    $NewAttendance[] = "$AttendanceValue-$Att-$DateTimeStamp-$ITS-$OTS";
            }
            $NewAttendance = implode(",", $NewAttendance);
            if ($NewAttendance != "")
                $queryInsert = "update staffattendance set Attendance='$NewAttendance' where Date='$AttendanceDate' ";
            else
                $queryInsert = "delete from staffattendance where Date='$AttendanceDate' ";
            mysqli_query($CONNECTION, $queryInsert);
        }
        else {
            foreach ($Attendance as $AttendanceValue)
                if ($Att != "")
                    $AttendanceString[] = "$AttendanceValue-$Att-$DateTimeStamp-$ITS-$OTS";
            $AttendanceString = implode(",", $AttendanceString);
            if ($AttendanceString != "") {
                $queryInsert = "insert into staffattendance(Date,Attendance,DOL,DOLUsername) values('$AttendanceDate','$AttendanceString','$DateTimeStamp','$USERNAME') ";
                mysqli_query($CONNECTION, $queryInsert);
            }
        }

        $Message = "Attendance updated successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:StaffAttendance");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StudentAttendance") {
    array_walk($_POST, "FilterSqlInjection");
    $Attendance = $_POST['box2View'];
    $AttendanceDate = $_POST['Date'];
    $SectionId = $_POST['SectionId'];
    $CurrentSessionArray = explode("-", $CURRENTSESSION);
    $StartingYear = $CurrentSessionArray[0];
    $EndingYear = $CurrentSessionArray[1];
    $SessionStartingDate = "01-04-$StartingYear 00:00am";
    $SessionEndingDate = "31-03-$EndingYear 23:59am";
    $SessionStartinDateTS = strtotime($SessionStartingDate);
    $SessionEndingDateTS = strtotime($SessionEndingDate);

    if ($_POST['Present'] != "")
        $Att = "P";
    elseif ($_POST['Absent'] != "")
        $Att = "A";
    elseif ($_POST['HalfDay'] != "")
        $Att = "H";
    elseif ($_POST['Holiday'] != "")
        $Att = "HD";
    elseif ($_POST['Blank'] != "")
        $Att = "";
    $DateTimeStamp = strtotime($Date);


    $CountStudent = count($Attendance);

    if ($Attendance == "" || $AttendanceDate == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $AttendanceDate = strtotime($AttendanceDate);
        $query = "select Attendance from studentattendance where Date='$AttendanceDate' ";
        $check = mysqli_query($CONNECTION, $query);
        $AlreadyMarked = mysqli_num_rows($check);
        if ($AlreadyMarked > 0) {
            $row = mysqli_fetch_array($check);
            $LastAttendance = explode(",", $row['Attendance']);
            foreach ($LastAttendance as $LastAttendanceValue) {
                $LastAttAttendance = explode("-", $LastAttendanceValue);
                $LastAdmissionIdId = $LastAttAttendance[0];
                $LastAtt = $LastAttAttendance[1];
                $LastTime = $LastAttAttendance[2];
                $Search = array_search($LastAdmissionIdId, $Attendance);
                if ($Search === FALSE)
                    $NewAttendance[] = "$LastAdmissionIdId-$LastAtt-$LastTime";
                elseif ($Att != "")
                    $NewAttendance[] = "$LastAdmissionIdId-$Att-$DateTimeStamp";
                $Marked[] = $LastAdmissionIdId;
            }

            foreach ($Attendance as $AttendanceValue) {
                $SearchForMarkedIndex = array_search($AttendanceValue, $Marked);
                if ($SearchForMarkedIndex === FALSE && $Att != "")
                    $NewAttendance[] = "$AttendanceValue-$Att-$DateTimeStamp";
            }
            $NewAttendance = implode(",", $NewAttendance);
            if ($NewAttendance != "")
                $queryInsert = "update studentattendance set Attendance='$NewAttendance' where Date='$AttendanceDate' ";
            else
                $queryInsert = "delete from studentattendance where Date='$AttendanceDate' ";
            mysqli_query($CONNECTION, $queryInsert);
        }
        else {
            foreach ($Attendance as $AttendanceValue)
                if ($Att != "")
                    $AttendanceString[] = "$AttendanceValue-$Att-$DateTimeStamp";
            $AttendanceString = implode(",", $AttendanceString);
            if ($AttendanceString != "") {
                $queryInsert = "insert into studentattendance(Date,Attendance,DOL,DOLUsername) values('$AttendanceDate','$AttendanceString','$DateTimeStamp','$USERNAME') ";
                mysqli_query($CONNECTION, $queryInsert);
            }
        }

        $Message = "Attendance updated successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:StudentAttendance/$SectionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManagePrintOption") {
    array_walk($_POST, "FilterSqlInjection");
    $HeaderId = $_POST['HeaderId'];
    $FooterId = $_POST['FooterId'];
    $PrintCategory = $_POST['PrintCategory'];
    $Width = $_POST['Width'];
    $PrintOptionId = $_POST['PrintOptionId'];

    if ($PrintOptionId != "")
        $Update = " and PrintOptionId!='$PrintOptionId' ";
    $query1 = "select * from printoption where PrintCategory='$PrintCategory' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);
    if ($count1 > 0) {
        while ($row1 = mysqli_fetch_array($check1)) {
            $OldPrintOptionStatus = $row1['PrintOptionStatus'];
            if ($OldPrintOptionStatus == "Deleted" && $count1 > 0)
                $count1 = 0;
            else
                $count1++;
        }
    }

    if ($PrintOptionId != "") {
        $addupdate = "updated";
        $query2 = "select PrintOptionStatus from printoption where PrintOptionId='$PrintOptionId' and PrintOptionStatus='Active'";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentPrintOptionStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($PrintCategory == "" || $Width == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($PrintOptionId != "" && $CurrentPrintOptionStatus == 0) {
        $Message = "This option is deleted. You cannot update the deleted option!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This option is already added!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($PrintOptionId == "")
            $query = "insert into printoption(PrintOptionStatus,HeaderId,FooterId,PrintCategory,Width) values('Active','$HeaderId','$FooterId','$PrintCategory','$Width') ";
        else
            $query = "update printoption set FooterId='$FooterId',HeaderId='$HeaderId',PrintCategory='$PrintCategory',Width='$Width' where PrintOptionId='$PrintOptionId' ";

        mysqli_query($CONNECTION, $query);
        $Message = "Option $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($PrintOptionId == "")
        header("Location:PrintOption");
    else
        header("Location:PrintOption/Update/$PrintOptionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageSchoolMaterial") {
    array_walk($_POST, "FilterSqlInjection");
    $ClassId = $_POST['ClassId'];
    $Name = $_POST['Name'];
    $SchoolMaterialId = $_POST['SchoolMaterialId'];
    $MaterialType = $_POST['MaterialType'];
    $BranchPrice = $_POST['BranchPrice'];
    $SellingPrice = $_POST['SellingPrice'];

    if ($SchoolMaterialId != "")
        $Update = " and SchoolMaterialId!='$SchoolMaterialId' ";

    $query = "select Name,SchoolMaterialStatus from schoolmaterial where Session='$CURRENTSESSION' and Name='$Name' and ClassId='$ClassId' $Update ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    if ($count > 0) {
        $row = mysqli_fetch_array($check);
        $OldSchoolMaterialStatus = $row['SchoolMaterialStatus'];
        if ($OldSchoolMaterialStatus == "Deleted" && $count > 0)
            $count = 0;
        else
            $count++;
    }
    if ($SchoolMaterialId != "") {
        $addupdate = "updated";
        $query2 = "select SchoolMaterialStatus from schoolmaterial where SchoolMaterialId='$SchoolMaterialId' and SchoolMaterialStatus='Active' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $CurrentSchoolMaterialStatus = mysqli_num_rows($check2);
    } else
        $addupdate = "added";

    if ($Name == "" || $MaterialType == "" || $SellingPrice == "" || $BranchPrice == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($MaterialType == "Books" && ($ClassId == "")) {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This material is already added!!";
        $Type = "error";
    } elseif ($SchoolMaterialId != "" && $CurrentSchoolMaterialStatus == 0) {
        $Message = "This school material is deleted. You cannot update the deleted material!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($SchoolMaterialId == "")
            $query = "insert into schoolmaterial(SchoolMaterialStatus,BranchPrice,SellingPrice,SchoolMaterialType,Session,Quantity,ClassId,Name,Date) values
			('Active','$BranchPrice','$SellingPrice','$MaterialType','$CURRENTSESSION','0','$ClassId','$Name','$Date') ";
        else
            $query = "update schoolmaterial set SellingPrice='$SellingPrice',BranchPrice='$BranchPrice',Session='$CURRENTSESSION',ClassId='$ClassId',Name='$Name',DLU='$Date' where SchoolMaterialId='$SchoolMaterialId' and SchoolMaterialType='$MaterialType' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Material $addupdate successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($SchoolMaterialId == "")
        header("Location:ManageSchoolMaterial/$MaterialType");
    else
        header("Location:ManageSchoolMaterial/$MaterialType/Update/$SchoolMaterialId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Purchase") {
    array_walk($_POST, "FilterSqlInjection");
    $Token = $_POST['Token'];
    $Quantity = $_POST['Quantity'];
    $PurchaseType = $_POST['PurchaseType'];
    if ($PurchaseType == "Stock") {
        $StockType = $_POST['StockType'];
        $StockId = $_POST['StockId'];
        $PurchasePrice = $_POST['PurchasePrice'];
        $OtherInfo = mynl2br($_POST['OtherInfo']);

        $query2 = "select Unit from stock where StockId='$StockId' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        $row2 = mysqli_fetch_array($check2);
        $Unit = $row2['Unit'];
        $MaterialType = "Stock";
    } elseif ($PurchaseType == "SchoolMaterial") {
        $StockId = $_POST['SchoolMaterialId'];
        $MaterialType = $_POST['MaterialType'];
    }

    $query = "Select PurchaseListId from purchaselist where 
		Token='$Token' and 
		UniqueId='$StockId' and 
		MaterialType='$MaterialType' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);

    $query3 = "Select PurchaseId from purchase where	
			Token='$Token' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    $count3 = mysqli_num_rows($check3);

    if ($PurchaseType == "Stock" && ($StockType == "" || $StockId == "" || $PurchasePrice == "" || $PurchasePrice <= 0 || $Token == "")) {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($PurchaseType == "Stock" && ($Unit != "0" && ($Quantity == "" || $Quantity <= 0))) {
        $Message = "Quantity is required!!";
        $Type = "error";
    } elseif ($PurchaseType == "SchoolMaterial" && ($Quantity == "" || $Quantity <= 0)) {
        $Message = "Quantity is required!!";
        $Type = "error";
    } elseif ($PurchaseType == "SchoolMaterial" && ($StockId == "" || $Token == "" || $MaterialType == "")) {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This item is already added in the cart!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $query1 = "insert into purchaselist(Token,MaterialType,UniqueId,Quantity,PurchasePrice,OtherInfo,Date) values
		('$Token','$MaterialType','$StockId','$Quantity','$PurchasePrice','$OtherInfo','$Date') ";
        mysqli_query($CONNECTION, $query1);
        if ($count3 == 0)
            mysqli_query($CONNECTION, "insert into purchase(Token,PurchaseStatus) values('$Token','Started') ");
        $Message = "Item added successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($PurchaseType == "Stock")
        header("Location:Purchase/$Token");
    else
        header("Location:PurchaseSchoolMaterial/$MaterialType/$Token");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "PurchaseCheckOut") {
    array_walk($_POST, "FilterSqlInjection");
    $SupplierId = $_POST['SupplierId'];
    $PurchaseDate = $_POST['PurchaseDate'];
    $PurchaseType = $_POST['PurchaseType'];
    $MaterialType = $_POST['MaterialType'];
    $Remarks = mynl2br($_POST['Remarks']);
    $Token = $_POST['Token'];

    $query = "Select PurchaseStatus from purchase where 
		Token='$Token' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    $row = mysqli_fetch_array($check);
    $PurchaseStatus = $row['PurchaseStatus'];

    if ($SupplierId == "" || $PurchaseDate == "" || $Token == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($PurchaseStatus == "Active") {
        $Message = "This purchase is already completed!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $PurchaseDate = strtotime($PurchaseDate);
        $query2 = "select * from purchaselist where Token='$Token' ";
        $check2 = mysqli_query($CONNECTION, $query2);
        while ($row2 = mysqli_fetch_array($check2)) {
            $UniqueId = $row2['UniqueId'];
            $Quantity = round($row2['Quantity'], 2);
            $PurchasePrice = round($row2['PurchasePrice'], 2);
            if ($Quantity != 0 && $PurchaseType == "Stock") {
                mysqli_query($CONNECTION, "update stock set CurrentStock=CurrentStock+$Quantity where StockId='$UniqueId' ");
                $TotalPurchasePrice+=($PurchasePrice * $Quantity);
            } elseif ($Quantity == 0 && $PurchaseType == "Stock")
                $TotalPurchasePrice+=$PurchasePrice;
            elseif ($Quantity != 0 && $PurchaseType == "SchoolMaterial")
                mysqli_query($CONNECTION, "update schoolmaterial set Quantity=Quantity+$Quantity where SchoolMaterialId='$UniqueId' ");
        }
        $query = "update purchase set Total='$TotalPurchasePrice',DOP='$PurchaseDate',DOE='$Date',PurchaseStatus='Active',SupplierId='$SupplierId',Remarks='$Remarks' where Token='$Token' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Purchase done successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($PurchaseType == "Stock")
        header("Location:Purchase/$Token");
    else
        header("Location:PurchaseSchoolMaterial/$MaterialType/$Token");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ManageStock") {
    array_walk($_POST, "FilterSqlInjection");
    $StockType = $_POST['StockType'];
    $StockName = $_POST['StockName'];
    $OpeningStock = $_POST['OpeningStock'];
    $Unit = $_POST['Unit'];
    $StockId = $_POST['StockId'];
    $StockTypeId = $_POST['StockTypeId'];

    if ($StockId != "") {
        $Already = "and StockId!='$StockId' and StockStatus='Active'";
        $MessageContent = "updated";
    } else
        $MessageContent = "added";
    $check = mysqli_query($CONNECTION, "select StockId from stock where StockName='$StockName' and StockType='$StockType' $Already ");
    $count = mysqli_num_rows($check);

    if ($StockType == "" || $StockName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count > 0) {
        $Message = "This stock is already added!!";
        $Type = "error";
    } elseif ($OpeningStock != "" && (!is_numeric($OpeningStock) || $OpeningStock < 0)) {
        $Message = "Opening Quantity can not be less than zero!!";
        $Type = "error";
    } elseif ($OpeningStock != "" && $Unit == "") {
        $Message = "Please select unit if you are entering Opening Stock!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        if ($StockId == "") {
            $query = "insert into stock(StockType,StockName,OpeningStock,Unit,Date,StockStatus) values
			('$StockType','$StockName','$OpeningStock','$Unit','$Date','Active') ";
        } else {
            $query = "update stock set StockType='$StockType',StockName='$StockName',OpeningStock='$OpeningStock',Unit='$Unit',DLU='$Date'
			where StockId='$StockId' and StockStatus='Active'";
        }
        mysqli_query($CONNECTION, $query);
        $Message = "Stock $MessageContent successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($StockId == "")
        header("Location:ManageStock");
    else
        header("Location:ManageStock/UpdateStock/$StockTypeId/$StockId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "TransferIndividualStock") {
    array_walk($_POST, "FilterSqlInjection");
    $TransferType = $_POST['TransferType'];
    if ($TransferType != "StockTransfer" && $TransferType != "StockAssign")
        $TransferType = "StockAssing";
    $AssignTo = $_POST['AssignTo'];
    $StockId = $_POST['StockId'];
    $StockTypeId = $_POST['StockTypeId'];
    $StockAssignId = $_POST['StockAssignId'];
    $AssignToName = GetCategoryValueOfId($AssignTo, 'AssignTo');
    $AssignToDetail = $_POST['AssignToDetail'];
    $Quantity = $_POST['TransferQuantity'];
    $DOT = $_POST['DOT'];
    $DOTTimeStamp = strtotime($DOT);
    $Remarks = mynl2br($_POST['Remarks']);

    if ($TransferType == "StockAssign") {
        $query = "select StockName,(OpeningStock+CurrentStock) as Quantity from stock where StockId='$StockId' ";
        $check = mysqli_query($CONNECTION, $query);
        $row = mysqli_fetch_array($check);
        $StockName = $row['StockName'];
        $AvailableQuantity = round($row['Quantity'], 2);
    } elseif ($TransferType == "StockTransfer") {
        $query = "select StockName,(Quantity-Returning) as Quantity,AssignTo,AssignToDetail from stockassign,stock where stock.StockId=stockassign.StockId and StockAssignId='$StockAssignId' ";
        $check = mysqli_query($CONNECTION, $query);
        $row = mysqli_fetch_array($check);
        $StockName = $row['StockName'];
        $AssignToRow = $row['AssignTo'];
        $AssignToDetailRow = $row['AssignToDetail'];
        $AvailableQuantity = round($row['Quantity'], 2);
    }

    if ($StockTypeId == "" || $AssignTo == "" || $DOT == "" || $Quantity == "" || $Quantity <= 0 || $StockId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($DOTTimeStamp == "") {
        $Message = "Please enter valid transfer date!!";
        $Type = "error";
    } elseif (($AssignToName == "Student" || $AssignToName == "Room" || $AssignToName == "Staff") && $AssignToDetail == "") {
        $Message = "Please select $AssignToName detail!!";
        $Type = "error";
    } elseif ($Quantity > $AvailableQuantity) {
        $Message = "$StockName is only $AvailableQuantity quantity available to transfer!!";
        $Type = "error";
    } elseif ($TransferType == "StockTransfer" && $StockAssignId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TransferType == "StockTransfer" && $AssignToRow == $AssignTo && $AssignToDetailRow == $AssignToDetail) {
        $Message = "Both the locations are same, please choose different!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $query1 = "insert into stockassign(StockId,Quantity,DOT,DOE,Username,StockAssignStatus,AssignTo,AssignToDetail,Remarks) values
			('$StockId','$Quantity','$DOTTimeStamp','$Date','$USERNAME','Active','$AssignTo','$AssignToDetail','$Remarks') ";
        if ($TransferType == "StockAssign")
            $query2 = "update stock set CurrentStock=CurrentStock-$Quantity where StockId='$StockId' ";
        else
            $query2 = "update stockassign set Returning=Returning+$Quantity where StockAssignId='$StockAssignId' ";
        mysqli_query($CONNECTION, $query1);
        mysqli_query($CONNECTION, $query2);
        $Message = "Stock transfered successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($TransferType == "StockAssign")
        header("Location:ManageStock/TransferStock/$StockTypeId/$StockId");
    else
        header("Location:StockTransfer/$StockTypeId/$StockId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "PurchasePayment") {
    array_walk($_POST, "FilterSqlInjection");
    $AmountPaid = $_POST['AmountPaid'];
    $Account = $_POST['Account'];
    $DOP = $_POST['DOP'];
    $Remarks = mynl2br($_POST['PaymentRemarks']);
    $PurchaseId = $_POST['PurchaseId'];
    $Token = $_POST['Token'];

    $query = "Select (OpeningBalance+AccountBalance) as TotalAccountBalance,AccountName from accounts where AccountId='$Account' ";
    $check = mysqli_query($CONNECTION, $query);
    $row = mysqli_fetch_array($check);
    $TotalAccountBalance = round($row['TotalAccountBalance'], 2);
    $AccountName = $row['AccountName'];

    $query3 = "select Paid,Total,SupplierId from purchase where PurchaseId='$PurchaseId' and PurchaseStatus='Active' ";
    $check3 = mysqli_query($CONNECTION, $query3);
    $count3 = mysqli_num_rows($check3);
    $row3 = mysqli_fetch_array($check3);
    $Paid = $row3['Paid'];
    $TotalAmount = $row3['Total'];
    $SupplierId = $row3['SupplierId'];
    $Balance = $TotalAmount - $Paid;

    if ($AmountPaid == "" || $AmountPaid <= 0 || $DOP == "" || $Account == "" || $Remarks == "" || $PurchaseId == "" || $Token == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($TotalAccountBalance < $AmountPaid) {
        $Message = "$AccountName has only $TotalAccountBalance $CURRENCY!!";
        $Type = "error";
    } elseif ($count3 == 0) {
        $Message = "Wrong URL!!";
        $Type = "error";
    } elseif ($AmountPaid > $Balance) {
        $Message = "Only $Balance $CURRENCY is remaining to pay!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $DOP = strtotime($DOP);
        $query1 = "insert into transaction(Username,TransactionAmount,TransactionType,TransactionFrom,TransactionHead,TransactionHeadId,TransactionRemarks,TransactionDate,TransactionDOE,TransactionIP,TransactionStatus) values
		('$USERNAME','$AmountPaid','0','$Account','Purchase','$PurchaseId','$Remarks','$DOP','$Date','$IP','Active') ";
        mysqli_query($CONNECTION, $query1);
        mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance-$AmountPaid where AccountId='$Account' ");
        mysqli_query($CONNECTION, "update purchase set Paid=Paid+$AmountPaid where PurchaseId='$PurchaseId' ");
        $Message = "Payment made successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Purchase/$Token");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Issue") {
    array_walk($_POST, "FilterSqlInjection");
    $AdmissionId = $_POST['AdmissionId'];
    $ClassId = $_POST['ClassId'];
    $MaterialType = $_POST['MaterialType'];
    $box2View = $_POST['box2View'];
    $box2View = explode(",", $box2View);
    $CountItem = count($box2View);
    foreach ($box2View as $Material) {
        $FName = "Name_$Material";
        $RequiredQuantity = $_POST[$FName];
        $query = "select Quantity,SellingPrice from schoolmaterial where SchoolMaterialId='$Material' ";
        $check = mysqli_query($CONNECTION, $query);
        $row = mysqli_fetch_array($check);
        $Quantity = $row['Quantity'];
        $SellingPrice = $row['SellingPrice'];
        $Total+=($SellingPrice * $RequiredQuantity);
        if ($RequiredQuantity > $Quantity)
            $Error = 1;
        $Issue[] = "$Material-$RequiredQuantity";
    }
    $IssueList = implode(",", $Issue);
    $DOI = $_POST['DOI'];
    $Remarks = mynl2br($_POST['Remarks']);

    if ($AdmissionId == "" || $ClassId == "" || $MaterialType == "" || $box2View == "" || $DOI == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($Error == 1) {
        $Message = "One of the item is not available in the store!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $DOI = strtotime($DOI);
        $Date = strtotime($Date);
        $query1 = "insert into issue(Username,IssueStatus,AdmissionId,ClassId,Session,MaterialType,Material,Total,Paid,Remarks,DOI,DOE)  
			values ('$USERNAME','Active','$AdmissionId','$ClassId','$CURRENTSESSION','$MaterialType','$IssueList','$Total','$Paid','$Remarks','$DOI','$Date') ";
        mysqli_query($CONNECTION, $query1);
        $IId = mysqli_insert_id($CONNECTION);
        foreach ($Issue as $IssueValue) {
            $IssueValue = explode("-", $IssueValue);
            $MId = $IssueValue[0];
            $Quan = $IssueValue[1];
            $query2 = "update schoolmaterial set Quantity=Quantity-$Quan where SchoolMaterialId='$MId' ";
            mysqli_query($CONNECTION, $query2);
        }
        $Message = "Material issued successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:IssueSchoolMaterial/$MaterialType/$AdmissionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StaffSalaryPayment") {
    array_walk($_POST, "FilterSqlInjection");
    $StaffId = $_POST['StaffId'];
    $DOP = strtotime($_POST['DOP']);
    $MonthYear = strtotime("01-" . $_POST['MonthYear']);
    $Remarks = mynl2br($_POST['Remarks']);
    $Amount = $_POST['Amount'];
    $Account = $_POST['Account'];
    $SalaryPaymentType = $_POST['SalaryPaymentType'];

    $query = "select StaffName,StaffMobile,StaffDOJ from staff where StaffId='$StaffId' and StaffStatus='Active' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    if ($count > 0) {
        $row = mysqli_fetch_array($check);
        $StaffName = $row['StaffName'];
        $StaffMobile = $row['StaffMobile'];
        $StaffDOJ = $row['StaffDOJ'];
        $StaffDOJName = date("d M Y", $StaffDOJ);
    }

    $query1 = "select (OpeningBalance+AccountBalance) as TotalBalance,AccountName from accounts where AccountId='$Account' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $row1 = mysqli_fetch_array($check1);
    $TotalBalance = $row1['TotalBalance'];
    $AccountName = $row1['AccountName'];

    if ($StaffId == "" || $DOP == "" || $MonthYear == "" || $Amount == "" || $Account == "" || $SalaryPaymentType == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($count == 0) {
        $Message = "Selected staff is either not active or its not a valid Staff Id!!";
        $Type = error;
    } elseif ($Amount <= 0 || !is_numeric($Amount)) {
        $Message = "Amount should be numeric and greater than zero!!";
        $Type = error;
    } elseif ($TotalBalance < $Amount) {
        $Message = "$AccountName has not sufficient balance in it!!";
        $Type = error;
    } elseif ($StaffDOJ > $MonthYear) {
        $Message = "$StaffName has joined the school on $StaffDOJName, You cannot pay salary before that!!";
        $Type = error;
    } elseif ($SCHOOLSTARTDATE > $DOP) {
        $Message = "Date of payment cannot be less than Software start date!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $query2 = "insert into expense(Username,ExpenseStatus,StaffId,SalaryMonthYear,SalaryPaymentType,ExpenseAmount,AmountPaid,ExpenseRemarks,ExpenseDate,DOE) values
			('$USERNAME','Active','$StaffId','$MonthYear','$SalaryPaymentType','$Amount','$Amount','$Remarks','$DOP','$Date')";
        mysqli_query($CONNECTION, $query2);
        $ExpenseId = mysqli_insert_id($CONNECTION);
        $query3 = "insert into transaction(Username,TransactionStatus,TransactionHead,TransactionHeadId,TransactionAmount,TransactionFrom,TransactionDate,TransactionRemarks,TransactionDOE,TransactionType,TransactionIP) values
			('$USERNAME','Active','Expense','$ExpenseId','$Amount','$Account','$DOP','$Remarks','$Date','0','$IP')";
        mysqli_query($CONNECTION, $query3);
        mysqli_query($CONNECTION, "update accounts set AccountBalance=AccountBalance-$Amount where AccountId='$Account' ");
        $Message = "Salary paid to $StaffName ($StaffMobile)!! ";
        $Type = success;
    }

    SetNotification($Message, $Type);
    header("Location:ManageStaff/$StaffId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "UpdateTransportFee") {
    array_walk($_POST, "FilterSqlInjection");
    $AdmissionId = $_POST['AdmissionId'];
    $Password = $_POST['Password'];
    $Distance = $_POST['Distance'];
    $ActionDetail = $_POST['ActionDetail'];
    if (isset($Password))
        $Password = md5($Password);

    $query0 = "select MasterEntryId,MasterEntryValue from masterentry where
		MasterEntryName='Distance' ";
    $check0 = mysqli_query($CONNECTION, $query0);
    while ($row0 = mysqli_fetch_array($check0)) {
        $DistanceIdArray[] = $row0['MasterEntryId'];
        $DistanceNameArray[] = $row0['MasterEntryValue'];
    }

    $query = "Select section.SectionId,Distance,ClassName,SectionName,FeeStructure from studentfee,class,section where 
		class.ClassId=section.ClassId and
		studentfee.SectionId=section.SectionId and 
		studentfee.Session='$CURRENTSESSION' and 
		AdmissionId='$AdmissionId' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    if ($count > 0) {
        $row = mysqli_fetch_array($check);
        $SectionId = $row['SectionId'];
        $SavedDistance = $row['Distance'];
        $DistanceSearchIndex = array_search($SavedDistance, $DistanceIdArray);
        $SavedDistanceName = $DistanceNameArray[$DistanceSearchIndex];
        $ClassName = $row['ClassName'];
        $SectionName = $row['SectionName'];
        $FeeStructure = $row['FeeStructure'];

        if ($ActionDetail == "Save") {
            $NewDistanceSearchIndex = array_search($Distance, $DistanceIdArray);
            $DistanceName = $DistanceNameArray[$NewDistanceSearchIndex];
            $query1 = "select FeeId from fee where 
				Session='$CURRENTSESSION' and 
				SectionId='$SectionId' and 
				FeeStatus='Active' and 
				Distance='$Distance' ";
            $check1 = mysqli_query($CONNECTION, $query1);
            $count1 = mysqli_num_rows($check1);
            if ($count1 > 0) {
                $row1 = mysqli_fetch_array($check1);
                $NewFeeId = $row1['FeeId'];
            }
        } elseif ($ActionDetail == "Remove") {
            $query1 = "select FeeId from fee where Session='$CURRENTSESSION' and SectionId='$SectionId' and FeeStatus='Active' and Distance='$SavedDistance' ";
            $check1 = mysqli_query($CONNECTION, $query1);
            $count1 = mysqli_num_rows($check1);
            if ($count1 > 0) {
                $row1 = mysqli_fetch_array($check1);
                $OldFeeId = $row1['FeeId'];
            }
            $query3 = "select TransactionAmount from transaction,feepayment where 
				feepayment.Token=transaction.Token and 
				FeeType='$OldFeeId' and
				TransactionStatus='Active' and
				TransactionHead='Fee' and
				TransactionHeadId='$AdmissionId' and
				TransactionSession='$CURRENTSESSION' and
				FeePaymentStatus='Active' ";
            $check3 = mysqli_query($CONNECTION, $query3);
            $count3 = mysqli_num_rows($check3);
            if ($count3 > 0) {
                $row3 = mysqli_fetch_array($check3);
                $Amount = $row3['TransactionAmount'];
            }
        }
    }

    if ($AdmissionId == "" || $Password == "") {
        $Message = "All the fields are mandatory!!";
        $Type = error;
    } elseif ($Password != $PASSWORD) {
        $Message = "Password didn't match!!";
        $Type = error;
    } elseif ($ActionDetail == "Remove" && $SavedDistance == "") {
        $Message = "No transport fee is added to selected students!!";
        $Type = error;
    } elseif ($count == 0) {
        $Message = "This is not a valid link!!";
        $Type = error;
    } elseif ($ActionDetail == "Remove" && $count3 > 0) {
        $Message = "$Amount $CURRENY has already been paid as Transport fee, It cannot be removed!! To remove it delete that receipt";
        $Type = error;
    } elseif ($ActionDetail == "Save" && $SavedDistance != "") {
        $Message = "Transport fee already added to selected students!!";
        $Type = error;
    } elseif ($count1 == 0 && $ActionDetail == "Save") {
        $Message = "No fee structure set from $DistanceName for $ClassName $SectionName !!";
        $Type = error;
    } elseif ($ActionDetail == "Save" && $Distance == "") {
        $Message = "Distance is mandatory!!";
        $Type = error;
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($ActionDetail == "Save") {
            $NewFeeStructure = $FeeStructure.=",$NewFeeId-0";
        } else {
            $FeeStructure = explode(",", $FeeStructure);
            foreach ($FeeStructure as $FeeStructureValue) {
                $FeeStructureValue = explode("-", $FeeStructureValue);
                $ArrayFeeId = $FeeStructureValue[0];
                $ArrayAmount = $FeeStructureValue[1];
                if ($ArrayFeeId == $OldFeeId) {
                    
                } else
                    $NewFeeStructure[] = "$ArrayFeeId-$ArrayAmount";
            }
            $Distance = "";
            $NewFeeStructure = implode(",", $NewFeeStructure);
        }
        $query2 = "update studentfee set FeeStructure='$NewFeeStructure',Distance='$Distance' where 
				AdmissionId='$AdmissionId' and 
				Session='$CURRENTSESSION' and
				SectionId='$SectionId' ";
        mysqli_query($CONNECTION, $query2);
        $Message = "Saved successfully!!";
        $Type = success;
    }
    SetNotification($Message, $Type);
    header("Location:UpdateFee/$SectionId/$AdmissionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "UpdateClass") {
    array_walk($_POST, "FilterSqlInjection");
    $NewSectionId = $_POST['NewSectionId'];
    $AdmissionId = $_POST['AdmissionId'];

    $query = "select SectionId,FeeStructure,Distance from studentfee where
		AdmissionId='$AdmissionId' and Session='$CURRENTSESSION' ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    $row = mysqli_fetch_array($check);
    $OldSectionId = $row['SectionId'];
    $OldFeeStructure = $row['FeeStructure'];
    $Distance = $row['Distance'];

    $query1 = "select TransactionId from transaction where TransactionSession='$CURRENTSESSION' and
		TransactionHead='Fee' and
		TransactionHeadId='$AdmissionId' and TransactionStatus='Active' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    $query2 = "select Amount,MasterEntryValue,FeeId from fee,masterentry where
		fee.FeeType=masterentry.MasterEntryId and
		fee.SectionId='$NewSectionId' and
		fee.Session='$CURRENTSESSION' and (Distance='' or Distance='$Distance') ";
    $check2 = mysqli_query($CONNECTION, $query2);
    while ($row2 = mysqli_fetch_array($check2)) {
        $FeeId = $row2['FeeId'];
        $FeeAmount = $_POST[$FeeId];
        if (!CheckNumeric($FeeAmount))
            $ErrorInFee++;
        $FeeString[] = "$FeeId-$FeeAmount";
    }

    if ($NewSectionId == "" || $AdmissionId == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($NewSectionId == $OldSectionId) {
        $Message = "New section & old section cannot be same!!";
        $Type = "error";
    } elseif ($count == 0) {
        $Message = "This is not a valid link!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This student has already paid the fee, Class cannot be changed!!";
        $Type = "error";
    } elseif ($ErrorInFee > 0) {
        $Message = "Fee should be numeric!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $FeeString = implode(",", $FeeString);
        mysqli_query($CONNECTION, "update studentfee set SectionId='$NewSectionId',FeeStructure='$FeeString' where AdmissionId='$AdmissionId' and Session='$CURRENTSESSION' ");
        $Message = "Class updated!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:UpdateFee/$OldSectionId/$AdmissionId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "SendSMS") {
    include("SMSFunction.php");
    $Connected = InternetConnection();
    if ($Connected == true)
        $SMSBalanceCount = CheckBalance($AuthKey, $BaseURL);
    $box2View = $_POST['box2View'];
    $CountNumbers = count($box2View);
    $Content = $_POST['Content'];
    $SMSBalance = $_POST['SMSBalance'];
    $CountName = substr_count($Content, '#NAME#');
    if ($CountName == 0)
        $SameMessage = 1;

    if ($Content == "" || $box2View == "" || $SMSBalance == "") {
        $Message = "Please select atleast one number and enter message content!!";
        $Type = "error";
    } elseif ($Connected == false) {
        $Message = "No internet Connection found!!";
        $Type = "error";
    } elseif ($SMSBalance == 0 || $SMSBalanceCount < $CountNumbers) {
        $Message = "You are going to submit $CountNumbers SMS but you have only $SMSBalanceCount SMS Balance!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $AllNumbers = array();
        foreach ($box2View as $Value) {
            $Content = Escape($_POST['Content']);
            $Content = trim(preg_replace('/\s\s+/', ' ', $Content));
            $i++;
            $Value = explode("-", $Value);
            $AdmissionId = $Value[0];
            $Name = $Value[1];
            $Mobile = $Value[2];
            if ($Mobile != "" && is_numeric($Mobile)) {
                if ($SameMessage == 1) {
                    $FinalMessageContent = $Content;
                    $AllNumbers[] = "$Mobile";
                    $CountValidNumber++;
                }
            }
        }
        $AllNumber = implode(",", $AllNumbers);
        $Date = strtotime($Date);
        if ($CountValidNumber > 0) {
            $FinalMessageContent = urlencode($FinalMessageContent);
            if ($SameMessage == 1)
                $url = "authkey=$AuthKey&mobiles=$AllNumber&message=$FinalMessageContent&sender=$SenderId&route=4";
            $response = SendSMS($url, $BaseURL);
            if ($response != "-1") {
                $Message = "$CountValidNumber SMS Submitted!!";
                $Type = "success";
            } else {
                $Message = "Unknown error!!";
                $Type = "error";
            }
        } else {
            $Message = "No valid numbers found to send SMS!!";
            $Type = "error";
        }
    }
    SetNotification($Message, $Type);
    header("Location:SMS");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "StudentTermination") {
    array_walk($_POST, "FilterSqlInjection");
    $RegistrationId = $_POST['RegistrationId'];
    $StudentFeeId = $_POST['StudentFeeId'];
    $Remarks = mynl2br($_POST['Remarks']);
    $DateOfTermination = $_POST['DateOfTermination'];
    $TerminationReason = $_POST['TerminationReason'];

    $query = "Select AdmissionId from registration,admission where 
		registration.RegistrationId='$RegistrationId' and 
		Status='Studying' and
		registration.RegistrationId=admission.RegistrationId ";
    $check = mysqli_query($CONNECTION, $query);
    $count = mysqli_num_rows($check);
    $row = mysqli_fetch_array($check);
    $AdmissionId = $row['AdmissionId'];

    $query1 = "select StudentFeeId from studentfee,registration,admission where
		registration.RegistrationId=admission.RegistrationId and
		admission.AdmissionId=studentfee.AdmissionId and
		registration.RegistrationId='$RegistrationId' and
		StudentFeeId>'$StudentFeeId' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($RegistrationId == "" || $StudentFeeId == "" || $Remarks == "" || $DateOfTermination == "" || $TerminationReason == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count == 0) {
        $Message = "This student cannot be terminated!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This student is already admission for next session!!";
        $Type = "error";
    } elseif ($TOKEN != $RandomNumber) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        $Date = strtotime($Date);
        $DateOfTermination = strtotime($DateOfTermination);
        mysqli_query($CONNECTION, "update studentfee set StudentFeeStatus='Terminated' where AdmissionId='$AdmissionId' and StudentFeeId='$StudentFeeId' ");
        $query2 = "update registration set TerminationReason='$TerminationReason',Status='Terminated',DateOfTermination='$DateOfTermination',TerminationRemarks='$Remarks',DOT='$Date' where RegistrationId='$RegistrationId' ";
        mysqli_query($CONNECTION, $query2);
        $Message = "Student terminated successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Registration/$RegistrationId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Language") {
    $LanguageName = Escape($_POST['LanguageName']);
    $LanguageId = Escape($_POST['LanguageId']);
    $RandomToken = Escape($_POST['RandomToken']);

    if ($LanguageId != "")
        $Update = " and LanguageId!='$LanguageId' ";
    $query1 = "select * from lang where LanguageName='$LanguageName' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($LanguageName == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This language is already added!!";
        $Type = "error";
    } elseif ($RandomToken != $TOKEN) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($LanguageId == "")
            $query = "insert into lang(LanguageName) values('$LanguageName') ";
        else
            $query = "update lang set LanguageName='$LanguageName' where LanguageId='$LanguageId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($LanguageId == "" || ($LanguageId != "" && $Type == "success"))
        header("Location:Language");
    else
        header("Location:Language/UpdateLanguage/$LanguageId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Phrase") {
    $Phrase = Escape($_POST['Phrase']);
    $PhraseId = Escape($_POST['PhraseId']);
    $RandomToken = Escape($_POST['RandomToken']);

    if ($PhraseId != "")
        $Update = " and PhraseId!='$PhraseId' ";
    $query1 = "select * from phrase where Phrase='$Phrase' $Update ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($Phrase == "") {
        $Message = "All the fields are mandatory!!";
        $Type = "error";
    } elseif ($count1 > 0) {
        $Message = "This language is already added!!";
        $Type = "error";
    } elseif ($RandomToken != $TOKEN) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($PhraseId == "")
            $query = "insert into phrase(Phrase) values('$Phrase') ";
        else
            $query = "update phrase set Phrase='$Phrase' where PhraseId='$PhraseId' ";
        mysqli_query($CONNECTION, $query);
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    if ($PhraseId == "" || ($PhraseId != "" && $Type == "success"))
        header("Location:Language");
    else
        header("Location:Language/UpdatePhrase/$PhraseId");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "Translation") {
    $RandomToken = Escape($_POST['RandomToken']);

    $query1 = "select TranslateId from translate where LanguageId='$LANGUAGE' ";
    $check1 = mysqli_query($CONNECTION, $query1);
    $count1 = mysqli_num_rows($check1);

    if ($RandomToken != $TOKEN) {
        $Message = "Illegal data posted!!";
        $Type = "error";
    } else {
        if ($PhraseIdArray != "")
            foreach ($PhraseIdArray as $PhraseIdArrayValue) {
                $Field = "T_$PhraseIdArrayValue";
                $PhraseTranslate = Escape($_POST[$Field]);
                $Translation[] = "$PhraseIdArrayValue**$PhraseTranslate";
            }
        $Translation = implode("||", $Translation);
        if ($count1 > 0)
            mysqli_query($CONNECTION, "update translate set Translation='$Translation' where LanguageId='$LANGUAGE' ");
        else
            mysqli_query($CONNECTION, "insert into translate (Translation,LanguageId) values ('$Translation','$LANGUAGE') ");
        $Message = "Saved successfully!!";
        $Type = "success";
    }
    SetNotification($Message, $Type);
    header("Location:Language");
}
///////////////////////////////////////////////////////////////////////////////////////////
elseif ($Action == "ExportStudentData") {
    $file = fopen('studentData.csv', 'w+');
    //$data = array('RegistrationId', 'Session', 'StudentName', 'FatherName', 'FatherMobile', 'FatherDateOfBirth', 'FatherEmail', 'FatherQualification', 'FatherOccupation', 'FatherDesignation', 'FatherOrganization', 'MotherName', 'MotherMobile', 'MotherDateOfBirth', 'MotherEmail', 'MotherQualification', 'MotherOccupation', 'MotherDesignation', 'MotherOrganization', 'Mobile', 'SectionId', 'DOB', 'DOR', 'DOE', 'Landline', 'AlternateMobile', 'PresentAddress', 'PermanentAddress', 'BloodGroup', 'Caste', 'Category', 'Gender', 'Nationality', 'Username', 'DOL', 'DOLUsername', 'DOD', 'DODUsername', 'DateOfTermination', 'TerminationReason', 'TerminationRemarks', 'DOT', 'SSSMID', 'Family_SSSMID', 'Aadhar_No');
    $data = array('StudentName', 'FatherName', 'MotherName', 'SSSMID', 'Family_SSSMID', 'Aadhar_No', 'Bank_Account_Number', 'IFSC_Code', 'Mobile', 'Class', 'Gender', 'DOR');
    fputcsv($file, $data);

    $data = '';
    //$items = mysqli_query($CONNECTION,"SELECT RegistrationId,Session,StudentName,FatherName,FatherMobile,FatherDateOfBirth,FatherEmail,FatherQualification,FatherOccupation,FatherDesignation,FatherOrganization,MotherName,MotherMobile,MotherDateOfBirth,MotherEmail,MotherQualification,MotherOccupation,MotherDesignation,MotherOrganization,Mobile,SectionId,DOB,DOR,DOE,Landline,AlternateMobile,PresentAddress,PermanentAddress,BloodGroup,Caste,Category,Gender,Nationality,Username,DOL,DOLUsername,DOD,DODUsername,DateOfTermination,TerminationReason,TerminationRemarks,DOT,SSSMID,Family_SSSMID,Aadhar_No FROM registration");
    $items = mysqli_query($CONNECTION, "SELECT StudentName,FatherName,MotherName,SSSMID,Family_SSSMID,Aadhar_No,Bank_Account_Number,IFSC_Code,
            Mobile,
            (SELECT CONCAT(c.ClassName,'-',s.SectionName) FROM class AS c,section AS s WHERE c.ClassId=s.ClassId AND c.ClassStatus='Active' AND s.SectionStatus='Active' AND c.Session='$CURRENTSESSION' AND s.SectionId =r.SectionId) AS Class,
            Gender,DOR
            FROM registration AS r");

    while ($row = mysqli_fetch_assoc($items)) {

        $row = array_map('strval', $row);
        $row = array_map('html_entity_decode', $row);

        $row['Gender'] = GetCategoryValueOfId($row['Gender'], 'Gender');
        $row['DOR'] = date('d/m/Y', $row['DOR']);
        $row['SSSMID'] = '="' . $row['SSSMID'] . '"';
        $row['Family_SSSMID'] = '="' . $row['Family_SSSMID'] . '"';
        $row['Aadhar_No'] = '="' . $row['Aadhar_No'] . '"';
        $row['Bank_Account_Number'] = '="' . $row['Bank_Account_Number'] . '"';
        $row['IFSC_Code'] = '="' . $row['IFSC_Code'] . '"';
        $row['Mobile'] = '="' . $row['Mobile'] . '"';

        $data = array($row['StudentName'], $row['FatherName'], $row['MotherName'], $row['SSSMID'], $row['Family_SSSMID'], $row['Aadhar_No'], $row['Bank_Account_Number'], $row['IFSC_Code'],
            $row['Mobile'], $row['Class'], $row['Gender'], $row['DOR']);
        fputcsv($file, $data);
    }
    fclose($file);
    $file = 'studentData.csv';

    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Transfer-Encoding: binary');
    readfile($file);
} elseif ($Action == "ImportStudentData") {

    if (isset($_FILES) && count($_FILES) > 0) {

        $target_path = '';
        if (isset($_FILES['csv_file']) && ($_FILES['csv_file']['tmp_name'] != '')) {
            $extension = pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION);
            $target_path = "upload/csv/";
            if (!is_dir($target_path)) {
                createDir($target_path);
            }
            $target_path = $target_path . 'studentData.' . $extension;
            //$target_path = $target_path . "one_csv_file" . '.' . $extension;
            move_uploaded_file($_FILES['csv_file']['tmp_name'], $target_path);
        }

        $dataFileArr = array();
        if (($handle = fopen($target_path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, "|||")) !== FALSE) {
                $num = count($data);

                $dataArr = array();
                $dataStr = "";
                for ($c = 0; $c < $num; $c++) {
                    $dataArr[] = $data[$c];
                }
                $dataStr = $dataArr;
                $dataFileArr[] = $dataStr;
            }
            fclose($handle);
        }



        $heading = array();
        $rheading = $heading = $dataFileArr[0];

        // /*
        $required_key = array();
        $required_key[] = array_search('StudentName', $heading);
        $required_key[] = array_search('FatherName', $heading);
        $required_key[] = array_search('MotherName', $heading);
        $required_key[] = array_search('SSSMID', $heading);
        $required_key[] = array_search('Family_SSSMID', $heading);
        $required_key[] = array_search('Aadhar_No', $heading);
        $required_key[] = array_search('Bank_Account_Number', $heading);
        $required_key[] = array_search('IFSC_Code', $heading);
        $required_key[] = array_search('Mobile', $heading);
        $required_key[] = array_search('Class', $heading);
        $required_key[] = array_search('Gender', $heading);
        $required_key[] = array_search('DOR', $heading);
        $required_key[] = array_search('DORaa', $heading);
        //* 
        //*/
        krsort($rheading);

        //remove all other value (field which are no longer need)
        foreach ($rheading as $r_key => $value) {
            if (!in_array($r_key, $required_key)) {
                delete_col($dataFileArr, $r_key);
            }
        }

        //once we get the mpn key for insert or update record
        $heading = $dataFileArr[0];

        $tableDataArr = array();

        foreach ($dataFileArr as $cell_key => $cell) {
            if ($cell_key > 0) {
                for($i=0;$i<count($heading);$i++){
                    if($i>=3 && $i<=8){
                        $cell[$i]=rtrim(ltrim(trim($cell[$i],'="')),'"');
                    }else{
                        $cell[$i]=trim($cell[$i]);
                    }
                }
                $tableDataArr[] = $cell;
            }
        }
        
        $update_record = $insert_record = 0;
        foreach ($tableDataArr as $mpn => $tableData) {
            array_walk($_POST, "FilterSqlInjection");
            $check_query = mysql_query("SELECT RegistrationId FROM `registration` WHERE `StudentName`='" . $tableData[$required_key[0]] . "' AND `FatherName`='".$tableData[$required_key[1]]."' AND Mobile='".$tableData[$required_key[7]]."' LIMIT 1; ");

            if (mysql_num_rows($check_query) > 0) {
                $check_query_data = mysql_fetch_array($check_query);
                $update = mysql_query("UPDATE `registration` SET StudentName='" . $tableData[$required_key[0]] . "',
                        `FatherName`='" . $tableData[$required_key[1]] . "',
                        `MotherName`='" . $tableData[$required_key[2]] . "',
                        `SSSMID`='" . $tableData[$required_key[3]] . "',
                        `Family_SSSMID`='" . $tableData[$required_key[4]] . "',
                        `Aadhar_No`='" . $tableData[$required_key[5]] . "',
                        `Bank_Account_Number`='" . $tableData[$required_key[6]] . "',
                        `IFSC_Code`='" . $tableData[$required_key[7]] . "',
                        `Mobile`='" . $tableData[$required_key[8]] . "',
                        `Class`='" . $tableData[$required_key[9]] . "',
                        `Gender`='" . $tableData[$required_key[10]] . "',
                        `DOR`='" . $tableData[$required_key[11]] . "'
                        WHERE `RegistrationId`=" . $check_query_data['RegistrationId']);
                $update_record++;
            } else {
                $insert = mysql_query("INSERT INTO `registration` 
                        (`StudentName`,`FatherName`, `MotherName`, `SSSMID`,`Family_SSSMID`, `Aadhar_No`, `Bank_Account_Number`,`IFSC_Code`, `Mobile`, `Class`,`Gender`, `DOR`) 
                        VALUES ('" . $tableData[$required_key[0]] . "',
                        '" . $tableData[$required_key[1]] . "',
                        '" . $tableData[$required_key[2]] . "',
                        '" . $tableData[$required_key[3]] . "',
                        '" . $tableData[$required_key[4]] . "',
                        '" . $tableData[$required_key[5]] . "',
                        '" . $tableData[$required_key[6]] . "',
                        '" . $tableData[$required_key[7]] . "',
                        '" . $tableData[$required_key[8]] . "',
                        '" . $tableData[$required_key[9]] . "',
                        '" . $tableData[$required_key[10]] . "',
                        '" . $tableData[$required_key[11]] . "');");
                $insert_record++;
            }
        }

        echo "Record Updated: $update_record <br>";
        echo "Record Inserted:$insert_record <br>";
        echo 'Success';
    }
}
///////////////////////////////////////////////////////////////////////////////////////////
else
    header("location:DashBoard");
?>