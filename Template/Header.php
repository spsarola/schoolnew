    <div id="header">
        <div class="navbar">
            <div class="navbar-inner">
              <div class="container-fluid">
                <a class="brand" href="DashBoard"><?php if($SCHOOLNAME=="") echo $APPLICATIONNAME; else echo $SCHOOLNAME; ?></a>
                <div class="nav-no-collapse">
                    <ul class="nav">
						<?php if(is_numeric($USERTYPEID)) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="icon16 icomoon-icon-cog"></span> <?php echo Translate('Setting'); ?>
                                <b class="caret"></b>
                            </a>
							<ul class="dropdown-menu scroll" style="height:200px; overflow:auto; margin-top:10px;">
								<li><a href="GeneralSetting"><span class="icon16 icomoon-icon-cogs"></span><?php echo Translate('General Setting'); ?></a></li>
								<li><a href="MasterEntry"><span class="icon16 icomoon-icon-tools"></span><?php echo Translate('Master Entry'); ?></a></li>
								<li><a href="ManageUser"><span class="icon16 icomoon-icon-users"></span><?php echo Translate('Manage User'); ?></a></li>
								<li><a href="ManageAccounts"><span class="icon16 icomoon-icon-basket-2"></span><?php echo Translate('Manage Accounts'); ?></a></li>
								<li><a href="ManageClass"><span class="icon16 icomoon-icon-picture-2"></span><?php echo Translate('Manage Class'); ?></a></li>
								<li><a href="ManageSubject"><span class="icon16 icomoon-icon-picture-3"></span><?php echo Translate('Manage Subject'); ?></a></li>
								<li><a href="ManageExam"><span class="icon16 icomoon-icon-meter-slow"></span><?php echo Translate('Manage Exam'); ?></a></li>
								<li><a href="ManageSCArea"><span class="icon16 icomoon-icon-flower"></span><?php echo Translate('Manage SC Area'); ?></a></li>
								<li><a href="ManageSCIndicator"><span class="icon16 icomoon-icon-balance"></span><?php echo Translate('Manage SC Indicator'); ?></a></li>
								<li><a href="ManageFee"><span class="icon16 entypo-icon-write"></span><?php echo Translate('Manage Fees'); ?></a></li>
								<li><a href="SalaryHead"><span class="icon16 icomoon-icon-copy-2"></span><?php echo Translate('Salary Head'); ?></a></li>
								<li><a href="SalaryStructureTemplate"><span class="icon16 icomoon-icon-stack"></span><?php echo Translate('Salary Structure'); ?></a></li>
								<li><a href="ManageSchoolMaterial"><span class="icon16 icomoon-icon-briefcase-2"></span><?php echo Translate('School Material'); ?></a></li>
								<li><a href="ManageLocation"><span class="icon16 icomoon-icon-home-4 "></span><?php echo Translate('Manage Location'); ?></a></li>
								<li><a href="ManageHeaderAndFooter"><span class="icon16 icomoon-icon-home-3 "></span><?php echo Translate('Header & Footer'); ?></a></li>
								<li><a href="PrintOption"><span class="icon16 icomoon-icon-printer-2"></span><?php echo Translate('Print Option'); ?></a></li>
								<li><a href="Permission"><span class="icon16 icomoon-icon-checkmark-2"></span><?php echo Translate('Permission'); ?></a></li>
							</ul>
                        </li>
						<?php } ?>
							<?php
							$SCHOOLSESSION=isset($_SESSION['SCHOOLSESSION']) ? $_SESSION['SCHOOLSESSION'] : '';
							if($SCHOOLSESSION!="")
							{
							?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<?php if(!isset($CURRENTSESSION)) echo "<b>Choose Session</b>"; else echo "<b>$CURRENTSESSION</b>"; ?>
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu"> 
							
							<?php
								foreach($SCHOOLSESSION as $SchoolSession)
								{
									$_SESSION['LastPage']=CurrentPageURL();
									echo "<li><a href=\"ActionGet/SetSession/$SchoolSession\"><b>Go to $SchoolSession</b></a></li>";
								}
								?>
								</ul>
							</li>
							<?php
							}
							?>
						<?php if($ACCOUNTLIST!="" && $USERTYPE!='Parents' && $USERTYPE!='Student') { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon16 icomoon-icon-coins"></span> Balance <b class="caret"></b></a>
                                
                            <ul class="dropdown-menu">
							<?php echo $ACCOUNTLIST; ?>
                            </ul>
                        </li>
						<?php } 
						$query103="select LanguageName,LanguageId from lang";
						$check103=mysqli_query($CONNECTION,$query103);
						$count103=mysqli_num_rows($check103);
						$ListLang=$SelectedLang="";
						if($count103>0)
						{
							while($row103=mysqli_fetch_array($check103))
							{
								$ListLanguageName=$row103['LanguageName'];
								$ListLanguageId=$row103['LanguageId'];
								if($LANGUAGE==$ListLanguageId)
								$SelectedLang=$ListLanguageName;
								$ListLang.="<li><a href=\"ActionGet/Language/$ListLanguageId\"><b>$ListLanguageName</b></a></li>";
							}
						}							
						if($SelectedLang=="")
						$SelectedLang="English";
						if($SelectedLang!="English")
						$ListLang.="<li><a href=\"ActionGet/Language/0\"><b>English</b></a></li>";
						$ListLang.="<li><a href=Language><b>Add more Language</b></a></li>";
							?>
						<li><a href="#" id="request" class="tip" title="Go to Full Screen"><span class="icon16 icomoon-icon-expand-2" ></span></a></li>
						<li><a href="#" id="exit" class="tip" title="Exit Full Screen"><span class="icon16 icomoon-icon-contract-2" ></span></a></li>
						<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon16 icomoon-icon-pencil" id="Select Language"></span><?php echo $SelectedLang; ?> <b class="caret"></b></a>
							<ul class="dropdown-menu scroll" style="height:200px; overflow:auto; margin-top:10px;">
							<?php echo $ListLang; ?>
							</ul>
						</li>
                    </ul>
                  
                    <ul class="nav pull-right usernav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hi!! <font color="red"><?php echo $USERNAME; ?></font>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
								<li><a href="ChangePassword"><span class="icon16 brocco-icon-key"></span>Change Password</a></li>
								<li><a href="LogOut"><span class="icon16 icomoon-icon-exit"></span> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
              </div>
            </div>
          </div>
    </div>
    <div id="wrapper">	