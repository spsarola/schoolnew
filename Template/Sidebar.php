       <div class="resBtn">
            <a href="#"><span class="icon16 minia-icon-list-3"></span></a>
        </div>
        
        <div class="collapseBtn leftbar">
             <a href="#" class="tipR" title="Hide sidebar"><span class="icon12 minia-icon-layout"></span></a>
        </div>

        <div id="sidebarbg"></div>
        <div id="sidebar">

            <div class="shortcuts">
                <ul>
                    <li><a href="DashBoard" title="DashBoard" class="tip"><span class="icon24 icomoon-icon-home-7"></span></a></li>
					<li><a href="Language" title="Language Setting" class="tip"><span class="icon24 icomoon-icon-pencil"></span></a></li>
                    <li><a href="Circular" title="Circular" class="tip"><span class="icon24 icomoon-icon-support"></span></a></li>
                    <li><a href="Calendar" title="Calendar" class="tip"><span class="icon24 brocco-icon-calendar"></span></a></li>
                </ul>
            </div> 
            <div class="sidenav">
				
                <div class="sidebar-widget" style="margin: -1px 0 0 0;">
                    <h5 class="title" style="margin-bottom:0"><?php echo Translate('Navigation'); ?></h5>
                </div>
				
                <div class="mainnav">
                    <ul>
					<?php if(!is_numeric($USERTYPEID) && ($USERTYPEID=="Parents" || $USERTYPEID=="Student")) { ?>
					<li><a href="Payment" id="PaymentLink"><span class="icon16 icomoon-icon-target "></span><?php echo Translate('Fee Payment'); ?></a></li>
                    <?php } else { ?>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-vcard"></span><?php echo Translate('Front Office'); ?></a>
                            <ul class="sub">
                                <li><a href="Call"><span class="icon16 icomoon-icon-phone-2"></span><?php echo Translate('Call & Follow-up'); ?></a></li>
                                <li><a href="OCall"><span class="icon16 icomoon-icon-phone-2"></span><?php echo Translate('Other Call'); ?></a></li>
                                <li><a href="Enquiry"><span class="icon16 icomoon-icon-tag-3"></span><?php echo Translate('Enquiry'); ?></a></li>
                                <li><a href="Complaint"><span class="icon16 icomoon-icon-shocked-2"></span><?php echo Translate('Complaint'); ?></a></li>
                                <li><a href="VisitorBook"><span class="icon16 typ-icon-users"></span><?php echo Translate('Visitor Book'); ?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-puzzle"></span><?php echo Translate('Admission'); ?></a>
                            <ul class="sub">
                                <li><a href="Registration" id="RegistrationLink"><span class="icon16 icomoon-icon-license"></span><?php echo Translate('Registration'); ?></a></li>
                                <li><a href="Admission" id="AdmissionLink"><span class="icon16 icomoon-icon-stack"></span><?php echo Translate('Admission'); ?></a></li>
                                <li><a href="Promotion"><span class="icon16 icomoon-icon-graduation"></span><?php echo Translate('Promotion'); ?></a></li>
                                <li><a href="UpdateFee"><span class="icon16 icomoon-icon-pencil"></span><?php echo Translate('Update Fee'); ?></a></li>	
								<li><a href="#" ><span class="icon16 icomoon-icon-cube"></span><?php echo Translate('Reports'); ?></a>
                                     <ul class="sub">
                                        <li><a href="AdmissionReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Admission Report'); ?></a></li>
                                    </ul>								
								</li>		
                            </ul>
                        </li>
                        <li><a href="Payment" id="PaymentLink"><span class="icon16 icomoon-icon-target "></span><?php echo Translate('Fee Payment'); ?></a></li>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-radio-unchecked"></span><?php echo Translate('Transaction'); ?></a>
                            <ul class="sub">
								<li><a href="Expense" id="ExpenseLink"><span class="icon16 icomoon-icon-arrow-last "></span><?php echo Translate('Expense'); ?></a></li>
								<li><a href="Income" id="IncomeLink"><span class="icon16 icomoon-icon-arrow-first "></span><?php echo Translate('Income'); ?></a></li>
							</ul>
						</li>
                        <li>
                            <a href="#"><span class="icon16 minia-icon-bars-2"></span><?php echo Translate('Attendance'); ?></a>
                            <ul class="sub">
								<li><a href="StaffAttendance" id="ExpenseLink"><span class="icon16 minia-icon-list-3"></span><?php echo Translate('Staff Attendance'); ?></a></li>
								<li><a href="StudentAttendance" id="IncomeLink"><span class="icon16 minia-icon-list-4"></span><?php echo Translate('Student Attendance'); ?></a></li>	
								<li><a href="#" ><span class="icon16 icomoon-icon-cube"></span><?php echo Translate('Reports'); ?></a>
                                     <ul class="sub">
                                        <li><a href="StudentAttendanceReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Student Attendance'); ?></a></li>
                                        <li><a href="StaffAttendanceReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Staff Attendance'); ?></a></li>
                                    </ul>								
								</li>
							</ul>
						</li>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-bus"></span><?php echo Translate('Transport'); ?></a>
                            <ul class="sub">
								<li><a href="Transport" id=""><span class="icon16 icomoon-icon-truck"></span><?php echo Translate('Transport'); ?></a></li>
								<li><a href="TransportRoute" id=""><span class="icon16 icomoon-icon-tree-view"></span><?php echo Translate('Transport Route'); ?></a></li>
							</ul>
						</li>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-clipboard"></span><?php echo Translate('Exam'); ?></a>
                            <ul class="sub">
								<li><a href="MarksSetup" id=""><span class="icon16 icomoon-icon-chess"></span><?php echo Translate('Scholastic Grade'); ?></a></li>	
								<li><a href="SCMarksSetup" id=""><span class="icon16 icomoon-icon-pacman "></span><?php echo Translate('Co Scholastic Grade'); ?></a></li>	
								<li><a href="#" ><span class="icon16 icomoon-icon-cube"></span><?php echo Translate('Reports'); ?></a>
                                     <ul class="sub">
                                        <li><a href="ExamReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('ExamReport'); ?></a></li>
                                    </ul>								
								</li>
							</ul>
						</li>
                        <li><a href="ManageStaff"><span class="icon16 entypo-icon-user"></span><?php echo Translate('Manage Staff'); ?></a></li>
                        <li>
							<a href="#"><span class="icon16 icomoon-icon-books"></span><?php echo Translate('Library'); ?></a>
							<ul class="sub">
								<li><a href="ManageBooks"><span class="icon16 icomoon-icon-book"></span><?php echo Translate('Manage Books</a>'); ?></li>				
								<li><a href="IssueAndReturn"><span class="icon16 icomoon-icon-book-2"></span><?php echo Translate('Issue & Return'); ?></a></li>	
							</ul>
						</li>
                        <li>
                            <a href="#"><span class="icon16 icomoon-icon-tab"></span><?php echo Translate('Dispatch & Receiving'); ?></a>
                            <ul class="sub">
                                <li><a href="DR/Dispatch"><span class="icon16 icomoon-icon-arrow-right-5"></span><?php echo Translate('Dispatch'); ?></a></li>
                                <li><a href="DR/Receiving"><span class="icon16 icomoon-icon-arrow-left-5"></span><?php echo Translate('Receiving'); ?></a></li>
                            </ul>
                        </li>
                        <li>
							<a href="#"><span class="icon16 icomoon-icon-briefcase"></span><?php echo Translate('Stock'); ?></a>
							<ul class="sub">
								<li><a href="ManageStock" id="StockLink"><span class="icon16 cut-icon-cart "></span><?php echo Translate('Manage Stock'); ?></a></li>			
								<li><a href="StockTransfer" id="StockTransferLink"><span class="icon16 icomoon-icon-cart-4 "></span><?php echo Translate('Stock Transfer'); ?></a></li>				
								<li><a href="PurchaseSchoolMaterial"><span class="icon16 icomoon-icon-bag"></span><?php echo Translate('Purchase Material'); ?></a></li>	
								<li><a href="Supplier" id=""><span class="icon16 icomoon-icon-user-4"></span><?php echo Translate('Supplier'); ?></a></li>	
								<li><a href="Purchase" id="PurchaseLink"><span class="icon16 minia-icon-cart "></span><?php echo Translate('Purchase'); ?></a></li>	
								<li><a href="IssueSchoolMaterial" id="IssueSchoolMaterialLink"><span class="icon16   icomoon-icon-book "></span><?php echo Translate('Issue Material'); ?></a></li>
								<li><a href="#" ><span class="icon16 icomoon-icon-cube"></span><?php echo Translate('Reports'); ?></a>
                                     <ul class="sub">
                                        <li><a href="StockReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Stock Report'); ?></a></li>
                                        <li><a href="SchoolMaterialReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('School Material'); ?></a></li>
                                        <li><a href="IssueReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Issue Report'); ?></a></li>
                                        <li><a href="PurchaseReport"><span class="icon16 icomoon-icon-arrow-right-2"></span><?php echo Translate('Purchase Report'); ?></a></li>
                                    </ul>								
								</li>
							</ul>
						</li>
						<li class="tip" title="SMS"><a href="SMS"><span class="icon16 icomoon-icon-mail-3 "></span><?php echo Translate('SMS'); ?></a></li>
                    <?php } ?>
					</ul>
                </div>
            </div>
        </div>
		<div class="modal fade" id="myModal"></div>